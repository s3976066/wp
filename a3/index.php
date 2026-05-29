<?php
$pageTitle = 'Home';
require_once 'includes/db_connect.inc';

$stmt = mysqli_prepare($conn, "SELECT * FROM pets ORDER BY created_at DESC LIMIT 4");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pets = $result->fetch_all(MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="alert alert-info mb-4">
        Welcome back, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!
    </div>
<?php endif; ?>

<?php if (empty($pets)): ?>
    <div class="text-center py-5">
        <span class="material-icons" style="font-size: 4rem; color: var(--text-muted);">pets</span>
        <h2 class="mt-3">No Pets Available</h2>
        <p class="text-muted">No pets available for adoption right now. Please check back later!</p>
    </div>
<?php else: ?>
    <section class="mb-5">
        <div id="petCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($pets as $i => $pet): ?>
                <button type="button" data-bs-target="#petCarousel" data-bs-slide-to="<?= $i ?>"
                        class="<?= $i === 0 ? 'active' : '' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner rounded overflow-hidden">
                <?php foreach ($pets as $i => $pet): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                        <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                             class="d-block w-100" alt="<?= htmlspecialchars($pet['name']) ?>"
                             style="object-fit: cover; height: 400px;">
                    </a>
                    <div class="carousel-caption d-none d-md-block"
                         style="background: rgba(0,0,0,0.5); border-radius: 8px; padding: 0.5rem 1.5rem;">
                        <h5><?= htmlspecialchars($pet['name']) ?></h5>
                        <p><?= htmlspecialchars($pet['species']) ?> &mdash; <?= htmlspecialchars($pet['breed'] ?? '') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#petCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#petCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section>
        <h2 class="mb-4">Latest Pets for Adoption</h2>
        <div class="row">
            <?php foreach ($pets as $pet): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                        <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                             class="card-img-top" alt="<?= htmlspecialchars($pet['name']) ?>">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                                <?= htmlspecialchars($pet['name']) ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted mb-1"><?= htmlspecialchars($pet['species']) ?></p>
                        <?php if (!empty($pet['breed'])): ?>
                        <p class="card-text"><small class="text-muted"><?= htmlspecialchars($pet['breed']) ?></small></p>
                        <?php endif; ?>
                        <span class="badge badge-<?= strtolower($pet['status']) ?> mt-auto align-self-start">
                            <?= htmlspecialchars($pet['status']) ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-3">
            <a href="pets.php" class="btn btn-outline-light">View All Pets</a>
        </div>
    </section>
<?php endif; ?>

<?php
require_once 'includes/footer.inc';
