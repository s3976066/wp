<?php
/**
 * gallery.php
 * PetConnect – Pet Gallery
 * Grid of all pets from DB. Click = Bootstrap modal (full size).
 * Dropdown filter by status (pure JavaScript, data-status attribute).
 * Student: Yizhao Zheng | s3976066
 */

$page_title = 'Gallery';
require_once 'includes/db_connect.inc';
require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<main class="container mt-4 mb-5">

    <!-- Heading + Filter bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h1 class="gallery-heading mb-0">
            <span class="material-icons align-middle" style="color:var(--secondary-color);font-size:2rem;">photo_library</span>
            Pet Gallery
        </h1>

        <!-- Status filter (dropdown, JS show/hide) -->
        <div class="filter-bar">
            <span class="material-icons" style="color:var(--primary-color);font-size:1.1rem;">filter_list</span>
            <label for="statusFilter">Filter by Status:</label>
            <select id="statusFilter" aria-label="Filter pets by status">
                <option value="all">Show All</option>
                <option value="available">Available</option>
                <option value="pending">Pending</option>
                <option value="adopted">Adopted</option>
            </select>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="row g-4" id="galleryGrid">
        <?php
        $sql  = 'SELECT pet_id, name, species, image_path, status, adoption_fee FROM pets ORDER BY created_at DESC';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($pet = mysqli_fetch_assoc($result)) :
            $imgPath    = 'assets/images/pets/' . htmlspecialchars($pet['image_path']);
            $petName    = htmlspecialchars($pet['name']);
            $statusLow  = strtolower($pet['status']);
            $badgeCls   = 'badge-status badge-' . $statusLow;
        ?>
        <!-- data-status used by JS filter -->
        <div class="col-6 col-sm-4 col-md-3" data-status="<?php echo $statusLow; ?>">
            <div class="gallery-card"
                 data-bs-toggle="modal"
                 data-bs-target="#petModal"
                 data-img-src="<?php echo $imgPath; ?>"
                 data-pet-name="<?php echo $petName; ?>"
                 role="button"
                 tabindex="0"
                 aria-label="View <?php echo $petName; ?> in full size">

                <img src="<?php echo $imgPath; ?>"
                     alt="<?php echo $petName; ?>"
                     loading="lazy"
                     onerror="this.src='assets/images/pets_banner.jpg'">

                <div class="gallery-card-body">
                    <div class="gallery-card-name"><?php echo $petName; ?></div>
                    <div class="gap-badges">
                        <span class="badge-species"><?php echo htmlspecialchars($pet['species']); ?></span>
                        <span class="<?php echo $badgeCls; ?>"><?php echo htmlspecialchars($pet['status']); ?></span>
                    </div>
                    <div class="gallery-card-fee">$<?php echo number_format((float)$pet['adoption_fee'], 2); ?></div>
                </div>
            </div>
        </div>
        <?php endwhile; mysqli_stmt_close($stmt); ?>
    </div>

</main>

<!-- ===== Bootstrap Modal for full-size image ===== -->
<div class="modal fade" id="petModal" tabindex="-1" aria-labelledby="petModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="petModalLabel">Pet Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="modalPetImg" src="" alt="Full size pet photo">
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.inc'; ?>
