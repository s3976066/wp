<?php
$pageTitle = 'Search';
require_once 'includes/db_connect.inc';

$searchTerm = trim($_GET['q'] ?? '');
$pets = [];

if ($searchTerm !== '') {
    $like = '%' . $searchTerm . '%';
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM pets WHERE name LIKE ? OR description LIKE ? OR species LIKE ? ORDER BY created_at DESC");
    mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $pets = $result->fetch_all(MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">Search Pets</h1>

<form method="get" action="search.php" class="mb-4">
    <div class="input-group input-group-lg">
        <input type="text" name="q" class="form-control"
               placeholder="Search by name, species, or description..."
               value="<?= htmlspecialchars($searchTerm) ?>"
               aria-label="Search pets">
        <button type="submit" class="btn btn-primary">
            <span class="material-icons">search</span> Search
        </button>
    </div>
</form>

<?php if ($searchTerm === ''): ?>
    <div class="text-center text-muted py-5">
        <span class="material-icons" style="font-size: 3rem;">pets</span>
        <p class="mt-3">Enter a pet name, species (e.g. cat, dog, bird), or description keyword to begin searching.</p>
    </div>
<?php elseif (empty($pets)): ?>
    <div class="text-center py-5">
        <p>No pets found matching "<strong><?= htmlspecialchars($searchTerm) ?></strong>".</p>
        <p class="text-muted">Try a different keyword.</p>
    </div>
<?php else: ?>
    <p class="text-muted mb-3">
        Found <?= count($pets) ?> result<?= count($pets) !== 1 ? 's' : '' ?> for "<strong><?= htmlspecialchars($searchTerm) ?></strong>"
    </p>
    <div class="row">
        <?php foreach ($pets as $pet): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                    <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                         class="card-img-top" alt="<?= htmlspecialchars($pet['name']) ?>">
                </a>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                            <?= htmlspecialchars($pet['name']) ?>
                        </a>
                    </h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($pet['species']) ?></p>
                    <p class="card-text"><small><?= htmlspecialchars(substr($pet['description'], 0, 100)) ?>&hellip;</small></p>
                    <span class="badge badge-<?= strtolower($pet['status']) ?>"><?= htmlspecialchars($pet['status']) ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
require_once 'includes/footer.inc';
