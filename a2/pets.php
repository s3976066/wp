<?php
/**
 * pets.php
 * PetConnect – Browse Pets
 * Two-column layout: left banner image (from AT1), right table from database.
 * Pet names are hyperlinks to details.php.
 * Student: Yizhao Zheng | s3976066
 */

$page_title = 'Browse Pets';
require_once 'includes/db_connect.inc';
require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<main class="container mt-4 mb-5">
    <div class="row g-4 align-items-start">

        <!-- Left Column: Banner Image (same image from AT1) -->
        <div class="col-12 col-lg-4">
            <img
                src="assets/images/pets_banner.jpg"
                alt="All available pets"
                class="pets-banner-img img-fluid rounded shadow-sm">
        </div>

        <!-- Right Column: Pets Table from Database -->
        <div class="col-12 col-lg-8">
            <h1 class="section-heading mb-4">
                <span class="material-icons">menu</span>
                All Available Pets
            </h1>

            <div class="table-responsive">
                <table class="table table-hover pets-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Species</th>
                            <th>Breed</th>
                            <th>Size</th>
                            <th>Fee ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all pets ordered alphabetically – prepared statement
                        $sql  = 'SELECT pet_id, name, species, breed, size, adoption_fee FROM pets ORDER BY name ASC';
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        while ($pet = mysqli_fetch_assoc($result)) :
                        ?>
                        <tr>
                            <td>
                                <!-- Pink hyperlink to details.php -->
                                <a href="details.php?id=<?php echo (int)$pet['pet_id']; ?>">
                                    <?php echo htmlspecialchars($pet['name']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($pet['species']); ?></td>
                            <td><?php echo htmlspecialchars($pet['breed'] ?? '—'); ?></td>
                            <td><?php echo htmlspecialchars($pet['size']); ?></td>
                            <td><?php echo number_format((float)$pet['adoption_fee'], 2); ?></td>
                        </tr>
                        <?php endwhile; mysqli_stmt_close($stmt); ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<?php require_once 'includes/footer.inc'; ?>
