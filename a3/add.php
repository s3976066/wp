<?php
$pageTitle = 'Add a Pet';
require_once 'includes/db_connect.inc';

// Stage 5: Login check
if (empty($_SESSION['user_id'])) {
    $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Please log in before adding a pet.'];
    header('Location: login.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $species = $_POST['species'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($name === '') $errors[] = 'Pet name is required.';
    if ($species === '') $errors[] = 'Please select a species.';
    $validSpecies = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
    if (!in_array($species, $validSpecies, true)) $errors[] = 'Invalid species selected.';
    if ($description === '') $errors[] = 'Description is required.';

    $breed = trim($_POST['breed'] ?? '') ?: '';
    $ageYears = $_POST['age_years'] !== '' ? (int)$_POST['age_years'] : 0;
    $ageMonths = $_POST['age_months'] !== '' ? (int)$_POST['age_months'] : 0;
    $gender = $_POST['gender'] ?? 'Unknown';
    $size = $_POST['size'] ?? 'Medium';
    $healthInfo = trim($_POST['health_info'] ?? '') ?: '';
    $adoptionFee = $_POST['adoption_fee'] !== '' ? (float)$_POST['adoption_fee'] : 0.00;
    $status = $_POST['status'] ?? 'Available';

    $imagePath = '';
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            $errors[] = 'Invalid image format. Allowed: jpg, jpeg, png, gif, webp.';
        } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Image upload failed.';
        } else {
            $imagePath = uniqid() . '.' . $ext;
            $target = 'assets/images/pets/' . $imagePath;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $errors[] = 'Failed to save uploaded image.';
                $imagePath = '';
            }
        }
    }

    if (empty($errors)) {
        $userId = (int)$_SESSION['user_id'];

        $stmt = mysqli_prepare($conn,
            "INSERT INTO pets (user_id, name, species, breed, age_years, age_months,
             gender, size, description, health_info, image_path, adoption_fee, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'isssiisssssds',
            $userId, $name, $species, $breed,
            $ageYears, $ageMonths, $gender, $size,
            $description, $healthInfo, $imagePath,
            $adoptionFee, $status);

        if (mysqli_stmt_execute($stmt)) {
            $newId = mysqli_insert_id($conn);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pet "' . htmlspecialchars($name) . '" added successfully!'];
            header('Location: details.php?id=' . $newId);
            exit;
        } else {
            $errors[] = 'Database error: Could not save pet.';
        }
        mysqli_stmt_close($stmt);
    }
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">Add a Pet</h1>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" action="" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">Pet Name *</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="species" class="form-label">Species *</label>
                <select name="species" id="species" class="form-select" required>
                    <option value="">-- Select --</option>
                    <?php foreach (['Dog' => 'Dog', 'Cat' => 'Cat', 'Bird' => 'Bird', 'Rabbit' => 'Rabbit', 'Other' => 'Other'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['species'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="breed" class="form-label">Breed</label>
                <input type="text" name="breed" id="breed" class="form-control"
                       value="<?= htmlspecialchars($_POST['breed'] ?? '') ?>">
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="age_years" class="form-label">Age (years)</label>
                    <input type="number" name="age_years" id="age_years" class="form-control" min="0" max="30"
                           value="<?= htmlspecialchars($_POST['age_years'] ?? '') ?>">
                </div>
                <div class="col-6">
                    <label for="age_months" class="form-label">Age (months)</label>
                    <input type="number" name="age_months" id="age_months" class="form-control" min="0" max="11"
                           value="<?= htmlspecialchars($_POST['age_months'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-select">
                    <?php foreach (['Male' => 'Male', 'Female' => 'Female', 'Unknown' => 'Unknown'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['gender'] ?? 'Unknown') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="size" class="form-label">Size</label>
                <select name="size" id="size" class="form-select">
                    <?php foreach (['Small' => 'Small', 'Medium' => 'Medium', 'Large' => 'Large', 'Extra Large' => 'Extra Large'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['size'] ?? 'Medium') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="health_info" class="form-label">Health Information</label>
                <textarea name="health_info" id="health_info" class="form-control" rows="3"><?= htmlspecialchars($_POST['health_info'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="adoption_fee" class="form-label">Adoption Fee ($)</label>
                <input type="number" name="adoption_fee" id="adoption_fee" class="form-control" min="0" step="0.01"
                       value="<?= htmlspecialchars($_POST['adoption_fee'] ?? '0.00') ?>">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <?php foreach (['Available' => 'Available', 'Pending' => 'Pending', 'Adopted' => 'Adopted'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['status'] ?? 'Available') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="imageInput" class="form-label">Pet Image</label>
                <input type="file" name="image" id="imageInput" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
                <small id="imageError" class="text-danger d-none"></small>
                <div id="previewWrapper" class="d-none mt-2">
                    <p class="text-success">Valid image selected: <span id="previewMeta"></span></p>
                    <img id="imagePreview" class="img-thumbnail" style="max-width: 200px;" alt="Image Preview">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Add Pet</button>
        <a href="index.php" class="btn btn-outline-light">Cancel</a>
    </div>
</form>

<?php
require_once 'includes/footer.inc';
