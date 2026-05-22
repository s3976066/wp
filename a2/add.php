<?php
/**
 * add.php
 * PetConnect – Add a New Pet
 * Form display + submission handling in one file.
 * Server-side: validation, unique filename (uniqid()), MySQLi prepared INSERT.
 * Client-side: extension validation + preview via scripts.js.
 * Student: Yizhao Zheng | s3976066
 */

$page_title = 'Add a Pet';
require_once 'includes/db_connect.inc';

$success_msg = '';
$error_msg   = '';


$form = [
    'name'         => '',
    'species'      => '',
    'breed'        => '',
    'age_years'    => '',
    'age_months'   => '',
    'gender'       => '',
    'size'         => '',
    'adoption_fee' => '',
    'description'  => '',
    'health_info'  => '',
    'status'       => '',
];

/* ===== Handle POST submission ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve and trim all fields
    $form['name']         = trim($_POST['name']         ?? '');
    $form['species']      = trim($_POST['species']      ?? '');
    $form['breed']        = trim($_POST['breed']        ?? '');
    $form['age_years']    = trim($_POST['age_years']    ?? '');
    $form['age_months']   = trim($_POST['age_months']   ?? '');
    $form['gender']       = trim($_POST['gender']       ?? '');
    $form['size']         = trim($_POST['size']         ?? '');
    $form['adoption_fee'] = trim($_POST['adoption_fee'] ?? '');
    $form['description']  = trim($_POST['description']  ?? '');
    $form['health_info']  = trim($_POST['health_info']  ?? '');
    $form['status']       = trim($_POST['status']       ?? '');

    $errors = [];

    // Required field checks
    if ($form['name']         === '') $errors[] = 'Pet name is required.';
    if ($form['species']      === '') $errors[] = 'Species is required.';
    if ($form['gender']       === '') $errors[] = 'Gender is required.';
    if ($form['size']         === '') $errors[] = 'Size is required.';
    if ($form['adoption_fee'] === '') $errors[] = 'Adoption fee is required.';
    if ($form['description']  === '') $errors[] = 'Description is required.';
    if ($form['status']       === '') $errors[] = 'Status is required.';

    // Whitelist ENUM values (prevents injection through dropdowns)
    $allowed_species = ['Dog','Cat','Bird','Rabbit','Other'];
    $allowed_gender  = ['Male','Female','Unknown'];
    $allowed_size    = ['Small','Medium','Large','Extra Large'];
    $allowed_status  = ['Available','Pending','Adopted'];

    if ($form['species'] !== '' && !in_array($form['species'], $allowed_species)) $errors[] = 'Invalid species value.';
    if ($form['gender']  !== '' && !in_array($form['gender'],  $allowed_gender))  $errors[] = 'Invalid gender value.';
    if ($form['size']    !== '' && !in_array($form['size'],    $allowed_size))    $errors[] = 'Invalid size value.';
    if ($form['status']  !== '' && !in_array($form['status'],  $allowed_status))  $errors[] = 'Invalid status value.';

    if ($form['adoption_fee'] !== '' && (float)$form['adoption_fee'] < 0) $errors[] = 'Adoption fee cannot be negative.';

    // Convert numeric fields
    $age_years    = ($form['age_years']  !== '') ? (int)$form['age_years']  : null;
    $age_months   = ($form['age_months'] !== '') ? (int)$form['age_months'] : null;
    $adoption_fee = ($form['adoption_fee'] !== '') ? (float)$form['adoption_fee'] : 0.00;

    // --- Image upload ---
    $image_path = 'pets_banner.jpg';  // default fallback

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_exts = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_exts)) {
            $errors[] = 'Invalid image type. Allowed: JPG, JPEG, PNG, GIF, WEBP.';
        } else {
            // Unique server-generated filename using uniqid() as required
            $unique_name = uniqid('pet_', true) . '.' . $ext;
            $upload_dir  = __DIR__ . '/assets/images/pets/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $unique_name)) {
                $image_path = $unique_name;
            } else {
                $errors[] = 'Image upload failed. Please try again.';
            }
        }
    }

    // --- Insert into database if no errors ---
    if (empty($errors)) {
        $sql = 'INSERT INTO pets
                    (name, species, breed, age_years, age_months, gender, size,
                     description, health_info, image_path, adoption_fee, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            'sssiisssssds',
            $form['name'], $form['species'], $form['breed'],
            $age_years, $age_months,
            $form['gender'], $form['size'],
            $form['description'], $form['health_info'],
            $image_path, $adoption_fee, $form['status']
        );

        if (mysqli_stmt_execute($stmt)) {
            $success_msg = 'Pet "' . htmlspecialchars($form['name']) . '" added successfully!';
            // Reset form after success
            $form = array_fill_keys(array_keys($form), '');
        } else {
            $error_msg = 'Database error. Please try again.';
        }
        mysqli_stmt_close($stmt);

    } else {
        $error_msg = implode('<br>', array_map('htmlspecialchars', $errors));
    }
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<main class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="form-card">

                <h1 class="add-pet-heading">
                    <span class="material-icons">add_circle</span>
                    Add a New Pet for Adoption
                </h1>

                <?php if ($success_msg) : ?>
                <div class="msg-success">
                    <span class="material-icons">check_circle</span>
                    <?php echo $success_msg; ?>
                    <a href="pets.php" class="ms-2" style="color:inherit;font-weight:700;">View all pets &rarr;</a>
                </div>
                <?php endif; ?>

                <?php if ($error_msg) : ?>
                <div class="msg-error">
                    <span class="material-icons">error</span>
                    <div><?php echo $error_msg; ?></div>
                </div>
                <?php endif; ?>

                <!-- ===== Add Pet Form (matching AT1 field structure) ===== -->
                <form method="POST" action="add.php" enctype="multipart/form-data" id="petForm" novalidate>

                    <!-- Row 1: Name + Species -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="name" class="form-label">Pet Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="Enter pet name" required
                                   value="<?php echo htmlspecialchars($form['name']); ?>">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="species" class="form-label">Species <span class="text-danger">*</span></label>
                            <select id="species" name="species" class="form-select" required>
                                <option value="">Select species</option>
                                <?php foreach (['Dog','Cat','Bird','Rabbit','Other'] as $opt) : ?>
                                <option value="<?php echo $opt; ?>"
                                    <?php echo ($form['species'] === $opt) ? 'selected' : ''; ?>>
                                    <?php echo $opt; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Breed + Age Years + Age Months -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="breed" class="form-label">Breed</label>
                            <input type="text" id="breed" name="breed" class="form-control"
                                   placeholder="Enter breed"
                                   value="<?php echo htmlspecialchars($form['breed']); ?>">
                        </div>
                        <div class="col-6 col-md-3">
                            <label for="age_years" class="form-label">Age (Years)</label>
                            <input type="number" id="age_years" name="age_years" class="form-control"
                                   placeholder="Years" min="0" max="30"
                                   value="<?php echo htmlspecialchars($form['age_years']); ?>">
                        </div>
                        <div class="col-6 col-md-3">
                            <label for="age_months" class="form-label">Age (Months)</label>
                            <input type="number" id="age_months" name="age_months" class="form-control"
                                   placeholder="Months" min="0" max="11"
                                   value="<?php echo htmlspecialchars($form['age_months']); ?>">
                        </div>
                    </div>

                    <!-- Row 3: Gender + Size + Adoption Fee -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select id="gender" name="gender" class="form-select" required>
                                <option value="">Select gender</option>
                                <?php foreach (['Male','Female','Unknown'] as $opt) : ?>
                                <option value="<?php echo $opt; ?>"
                                    <?php echo ($form['gender'] === $opt) ? 'selected' : ''; ?>>
                                    <?php echo $opt; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="size" class="form-label">Size <span class="text-danger">*</span></label>
                            <select id="size" name="size" class="form-select" required>
                                <option value="">Select size</option>
                                <?php foreach (['Small','Medium','Large','Extra Large'] as $opt) : ?>
                                <option value="<?php echo $opt; ?>"
                                    <?php echo ($form['size'] === $opt) ? 'selected' : ''; ?>>
                                    <?php echo $opt; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="adoption_fee" class="form-label">Adoption Fee ($) <span class="text-danger">*</span></label>
                            <input type="number" id="adoption_fee" name="adoption_fee" class="form-control"
                                   placeholder="Enter fee" min="0" step="0.01" required
                                   value="<?php echo htmlspecialchars($form['adoption_fee']); ?>">
                        </div>
                    </div>

                    <!-- Row 4: Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control" rows="4"
                                  placeholder="Describe the pet's personality and characteristics" required><?php echo htmlspecialchars($form['description']); ?></textarea>
                    </div>

                    <!-- Row 5: Health Information -->
                    <div class="mb-3">
                        <label for="health_info" class="form-label">Health Information</label>
                        <textarea id="health_info" name="health_info" class="form-control" rows="3"
                                  placeholder="Enter health and vaccination information"><?php echo htmlspecialchars($form['health_info']); ?></textarea>
                    </div>

                    <!-- Row 6: Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="">Select status</option>
                            <?php foreach (['Available','Pending','Adopted'] as $opt) : ?>
                            <option value="<?php echo $opt; ?>"
                                <?php echo ($form['status'] === $opt) ? 'selected' : ''; ?>>
                                <?php echo $opt; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Row 7: Pet Photo (JS validation in scripts.js) -->
                    <div class="mb-4">
                        <label class="form-label">
                            <span class="material-icons align-middle" style="font-size:1.1rem;color:var(--primary-color);">image</span>
                            Pet Photo
                        </label>
                        <div class="upload-zone">
                            <input type="file" id="image" name="image"
                                   class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp"
                                   style="border:none;background:transparent;">
                            <small class="text-muted">Allowed formats: JPG, JPEG, PNG, GIF, WEBP</small>
                        </div>

                        <!-- JS alert boxes (shown/hidden by scripts.js – no inline JS) -->
                        <div id="alertValid"   class="alert-valid"   role="alert"></div>
                        <div id="alertInvalid" class="alert-invalid" role="alert"></div>

                        <!-- Image preview (shown by scripts.js) -->
                        <div id="imagePreviewContainer">
                            <label class="form-label mt-2">Preview:</label><br>
                            <img id="imagePreview" src="" alt="Image preview" class="img-thumbnail">
                        </div>
                    </div>

                    <!-- Buttons (matching AT1 button layout) -->
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn-submit">
                            <span class="material-icons" style="font-size:1.1rem;">save</span>
                            Add Pet
                        </button>
                        <a href="index.php" class="btn-cancel">
                            <span class="material-icons" style="font-size:1.1rem;">cancel</span>
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.inc'; ?>
