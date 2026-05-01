<?php
/**
 * details.php
 * PetConnect – Pet Details
 * Fetches a single pet via ?id=N using a prepared statement (SQL injection safe).
 * Student: Yizhao Zheng | s3976066
 */

require_once 'includes/db_connect.inc';

// --- Validate and sanitise the query string ---
if (!isset($_GET['id']) || !ctype_digit((string)$_GET['id'])) {
    header('Location: pets.php');
    exit;
}

$pet_id = (int)$_GET['id'];

// --- Fetch pet by primary key using prepared statement ---
$sql  = 'SELECT * FROM pets WHERE pet_id = ?';
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $pet_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pet    = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// If no matching record, redirect
if (!$pet) {
    header('Location: pets.php');
    exit;
}

// Build age string
$ageStr = '';
if ((int)$pet['age_years'] > 0) {
    $ageStr .= $pet['age_years'] . ' year' . ((int)$pet['age_years'] !== 1 ? 's' : '');
}
if ((int)$pet['age_months'] > 0) {
    $ageStr .= ($ageStr ? ', ' : '') . $pet['age_months'] . ' month' . ((int)$pet['age_months'] !== 1 ? 's' : '');
}
if (!$ageStr) $ageStr = 'Unknown';

$page_title = htmlspecialchars($pet['name']);
$statusLow  = strtolower($pet['status']);
$badgeCls   = 'badge-status badge-' . $statusLow;

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<main class="container mt-4 mb-5">
    <div class="row g-5 align-items-start">

        <!-- Left: Pet Image -->
        <div class="col-12 col-md-5">
            <img
                src="assets/images/pets/<?php echo htmlspecialchars($pet['image_path']); ?>"
                alt="<?php echo htmlspecialchars($pet['name']); ?>"
                class="detail-pet-img img-fluid"
                onerror="this.src='assets/images/pets_banner.jpg'">
        </div>

        <!-- Right: Details -->
        <div class="col-12 col-md-7">
            <h1 class="detail-name"><?php echo htmlspecialchars($pet['name']); ?></h1>

            <!-- Species + Status badges -->
            <div class="gap-badges mb-3">
                <span class="badge-species"><?php echo htmlspecialchars($pet['species']); ?></span>
                <span class="<?php echo $badgeCls; ?>"><?php echo htmlspecialchars($pet['status']); ?></span>
            </div>

            <!-- Detail rows table -->
            <table class="detail-table w-100 mb-3">
                <tbody>
                    <tr>
                        <td>Breed:</td>
                        <td><?php echo htmlspecialchars($pet['breed'] ?? '—'); ?></td>
                    </tr>
                    <tr>
                        <td>Age:</td>
                        <td><?php echo $ageStr; ?></td>
                    </tr>
                    <tr>
                        <td>Gender:</td>
                        <td><?php echo htmlspecialchars($pet['gender']); ?></td>
                    </tr>
                    <tr>
                        <td>Size:</td>
                        <td><?php echo htmlspecialchars($pet['size']); ?></td>
                    </tr>
                    <tr>
                        <td>Adoption Fee:</td>
                        <td><strong>$<?php echo number_format((float)$pet['adoption_fee'], 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <!-- Description -->
            <div class="detail-section-label">
                <span class="material-icons">description</span>
                Description
            </div>
            <p><?php echo nl2br(htmlspecialchars($pet['description'])); ?></p>

            <!-- Health Information -->
            <?php if (!empty($pet['health_info'])) : ?>
            <div class="detail-section-label">
                <span class="material-icons">favorite</span>
                Health Information
            </div>
            <p><?php echo nl2br(htmlspecialchars($pet['health_info'])); ?></p>
            <?php endif; ?>

            <a href="pets.php" class="btn-back mt-2">
                <span class="material-icons" style="font-size:1rem;">arrow_back</span>
                Back to Pets
            </a>
        </div>

    </div>
</main>

<?php require_once 'includes/footer.inc'; ?>
