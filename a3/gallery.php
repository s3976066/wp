<?php
$pageTitle = 'Pet Gallery';
require_once 'includes/db_connect.inc';

$stmt = mysqli_prepare($conn, "SELECT * FROM pets ORDER BY created_at DESC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pets = $result->fetch_all(MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">Pet Gallery</h1>

<div class="mb-4 d-flex gap-3 flex-wrap">
    <div>
        <label for="speciesFilter" class="form-label">Filter by Species</label>
        <select id="speciesFilter" class="form-select" style="max-width: 200px;">
            <option value="all">Show All</option>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Bird">Bird</option>
        </select>
    </div>
    <div>
        <label for="statusFilter" class="form-label">Filter by Status</label>
        <select id="statusFilter" class="form-select" style="max-width: 200px;">
            <option value="all">Show All</option>
            <option value="Available">Available</option>
            <option value="Pending">Pending</option>
            <option value="Adopted">Adopted</option>
        </select>
    </div>
</div>

<?php if (empty($pets)): ?>
    <div class="text-center py-5">
        <span class="material-icons" style="font-size: 4rem; color: var(--text-muted);">photo_library</span>
        <h2 class="mt-3">No pets in the gallery yet.</h2>
    </div>
<?php else: ?>
    <div class="row" id="galleryGrid">
        <?php foreach ($pets as $pet): ?>
        <div class="col-md-4 col-lg-3 mb-4 gallery-item pet-card" data-species="<?= htmlspecialchars($pet['species']) ?>" data-status="<?= htmlspecialchars($pet['status']) ?>">
            <div class="card h-100">
                <a href="details.php?id=<?= (int)$pet['pet_id'] ?>" class="img-hover gallery-img-link">
                    <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                         class="card-img-top gallery-img"
                         data-pet-name="<?= htmlspecialchars($pet['name']) ?>"
                         alt="<?= htmlspecialchars($pet['name']) ?>">
                </a>
                <div class="card-body text-center">
                    <h6 class="card-title mb-1"><?= htmlspecialchars($pet['name']) ?></h6>
                    <p class="card-text mb-2">
                        <span class="badge bg-secondary"><?= htmlspecialchars($pet['species']) ?></span>
                        <span class="badge badge-<?= strtolower($pet['status']) ?>"><?= htmlspecialchars($pet['status']) ?></span>
                    </p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div id="galleryModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalLabel">Image Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="galleryModalImage" src="" class="img-fluid rounded" alt="Pet image preview">
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.inc';
