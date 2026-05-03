<?php
/**
 * index.php
 * PetConnect – Homepage
 * Bootstrap carousel (same 4 images as AT1) + grid of 4 latest pets from DB.
 * Student: Yizhao Zheng | s3976066
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page_title = 'Home';
require_once 'includes/db_connect.inc';
require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<main>

   
    <div id="petCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#petCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#petCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#petCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#petCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/images/pets/Buddy.jpg" class="d-block w-100" alt="Buddy the Golden Retriever">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Buddy</h5>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/pets/Max.jpg" class="d-block w-100" alt="Max the Labrador">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Max</h5>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/pets/Charlie.jpg" class="d-block w-100" alt="Charlie the Cockatiel">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Charlie</h5>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/pets/Luna.jpg" class="d-block w-100" alt="Luna the Siamese">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Luna</h5>
                </div>
            </div>
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

    <!-- ===== Recently Added Pets Grid (from database) ===== -->
    <div class="container mt-5 mb-5">
        <h2 class="section-heading">
            <span class="material-icons">favorite</span>
            Recently Added Pets
        </h2>

        <?php
        // Fetch the 4 most recently added pets – prepared statement (no user input)
        $sql  = 'SELECT pet_id, name, species, adoption_fee, image_path, status FROM pets ORDER BY created_at DESC LIMIT 4';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        ?>

        <div class="row g-3">
            <?php while ($pet = mysqli_fetch_assoc($result)) : ?>
            <div class="col-6 col-md-3">
                <a href="details.php?id=<?php echo (int)$pet['pet_id']; ?>" class="text-decoration-none">
                    <div class="pet-card">
                        <img
                            src="assets/images/pets/<?php echo htmlspecialchars($pet['image_path']); ?>"
                            alt="<?php echo htmlspecialchars($pet['name']); ?>"
                            class="pet-card-img"
                            onerror="this.src='assets/images/pets_banner.jpg'">
                        <div class="pet-card-body">
                            <div class="pet-card-name"><?php echo htmlspecialchars($pet['name']); ?></div>
                            <div class="pet-card-fee">$<?php echo number_format((float)$pet['adoption_fee'], 2); ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; mysqli_stmt_close($stmt); ?>
        </div>

    </div>

</main>

<?php require_once 'includes/footer.inc'; ?>
