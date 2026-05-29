<?php
$pageTitle = 'Pet Details';
require_once 'includes/db_connect.inc';

// Stage 5: Process delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (empty($_SESSION['user_id'])) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Please log in first.'];
        header('Location: login.php');
        exit;
    }

    $deleteId = (int)($_POST['pet_id'] ?? 0);
    $stmt = mysqli_prepare($conn, "SELECT pet_id, user_id, image_path FROM pets WHERE pet_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $deleteId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $target = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$target) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Pet not found.'];
        header('Location: index.php');
        exit;
    }

    if ((int)$target['user_id'] !== (int)$_SESSION['user_id']) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'You do not own this pet.'];
        header('Location: index.php');
        exit;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM pets WHERE pet_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $deleteId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!empty($target['image_path'])) {
        $filePath = 'assets/images/pets/' . $target['image_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pet deleted successfully.'];
    header('Location: pets.php');
    exit;
}

// Input validation
$petId = $_GET['id'] ?? null;
$error = null;

if ($petId === null || $petId === '') {
    $error = 'No pet selected. Please choose one from the list.';
} elseif (!is_numeric($petId) || (int)$petId <= 0) {
    $error = 'Invalid pet ID.';
}

$pet = null;
$owner = null;
$isOwner = false;

if ($error === null) {
    $petId = (int)$petId;

    $stmt = mysqli_prepare($conn, "SELECT * FROM pets WHERE pet_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $petId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $pet = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$pet) {
        $error = 'Pet not found. It may have been removed.';
    } else {
        $isOwner = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$pet['user_id'];

        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $pet['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $owner = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

function formatAge($years, $months) {
    $parts = [];
    if ($years !== null && $years > 0) $parts[] = $years . ' yr';
    if ($months !== null && $months > 0) $parts[] = $months . ' mo';
    if (empty($parts)) return 'Under 1 month';
    return implode(' ', $parts);
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<?php if ($error !== null): ?>
    <div class="text-center py-5">
        <span class="material-icons" style="font-size: 4rem; color: var(--text-muted);">pets</span>
        <h2 class="mt-3"><?= htmlspecialchars($error) ?></h2>
        <a href="pets.php" class="btn btn-primary mt-3">Browse All Pets</a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-6 mb-4">
            <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                 class="img-fluid rounded w-100" style="object-fit: cover; max-height: 450px;"
                 alt="<?= htmlspecialchars($pet['name']) ?>">
        </div>

        <div class="col-md-6">
            <h2><?= htmlspecialchars($pet['name']) ?></h2>
            <span class="badge badge-<?= strtolower($pet['status']) ?> mb-3 fs-6">
                <?= htmlspecialchars($pet['status']) ?>
            </span>

            <table class="table table-borderless">
                <tr><th class="ps-0 text-muted">Species</th><td><?= htmlspecialchars($pet['species']) ?></td></tr>
                <?php if (!empty($pet['breed'])): ?>
                <tr><th class="ps-0 text-muted">Breed</th><td><?= htmlspecialchars($pet['breed']) ?></td></tr>
                <?php endif; ?>
                <tr><th class="ps-0 text-muted">Age</th><td><?= formatAge($pet['age_years'], $pet['age_months']) ?></td></tr>
                <tr><th class="ps-0 text-muted">Gender</th><td><?= htmlspecialchars($pet['gender']) ?></td></tr>
                <tr><th class="ps-0 text-muted">Size</th><td><?= htmlspecialchars($pet['size']) ?></td></tr>
                <tr><th class="ps-0 text-muted">Adoption Fee</th><td>$<?= number_format($pet['adoption_fee'], 2) ?></td></tr>
                <tr><th class="ps-0 text-muted">Posted</th><td><?= date('Y-m-d', strtotime($pet['created_at'])) ?></td></tr>
            </table>

            <h5 class="mt-3">About</h5>
            <p><?= nl2br(htmlspecialchars($pet['description'])) ?></p>

            <?php if (!empty($pet['health_info'])): ?>
            <h5>Health Info</h5>
            <p><?= nl2br(htmlspecialchars($pet['health_info'])) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Owner Card -->
    <?php if ($owner): ?>
    <div class="card mt-4" style="color: #f1f5f9;">
        <div class="card-body">
            <h4 class="card-title" style="color: #f1f5f9;">Contact Owner</h4>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Username: </strong>
                        <a href="owner.php?user_id=<?= (int)$owner['user_id'] ?>" style="color: var(--primary-light);">
                            <?= htmlspecialchars($owner['username']) ?>
                        </a>
                    </p>
                    <p class="mb-1">
                        <strong>Email: </strong>
                        <a href="mailto:<?= htmlspecialchars($owner['email']) ?>" style="color: var(--primary-light);">
                            <?= htmlspecialchars($owner['email']) ?>
                        </a>
                    </p>
                    <?php if (!empty($owner['phone'])): ?>
                    <p class="mb-1"><strong>Phone: </strong><?= htmlspecialchars($owner['phone']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if (!empty($owner['location'])): ?>
                    <p class="mb-1"><strong>Location: </strong><?= htmlspecialchars($owner['location']) ?></p>
                    <?php endif; ?>
                    <p class="mb-0"><strong>Joined: </strong><?= date('Y-m-d', strtotime($owner['joined_at'])) ?></p>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="owner.php?user_id=<?= (int)$owner['user_id'] ?>" class="btn btn-outline-light">
                    View all pets by <?= htmlspecialchars($owner['username']) ?>
                </a>
                <?php if ($isOwner): ?>
                <a href="edit.php?id=<?= (int)$pet['pet_id'] ?>" class="btn btn-primary">Edit</a>
                <button type="button" id="deleteBtn" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    Delete
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Delete Confirmation Modal -->
    <?php if ($isOwner): ?>
    <div id="deleteModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong><?= htmlspecialchars($pet['name']) ?></strong>?</p>
                    <p class="text-danger">This action cannot be undone. All pet data and images will be permanently removed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" action="details.php" method="post" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="pet_id" value="<?= (int)$pet['pet_id'] ?>">
                        <button type="submit" id="confirmDelete" class="btn btn-danger">Confirm Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php endif; ?>

<?php
require_once 'includes/footer.inc';
