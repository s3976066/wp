<?php
$pageTitle = 'Edit Pet';
require_once 'includes/db_connect.inc';

// Stage 5: 登录检查
if (empty($_SESSION['user_id'])) {
    $_SESSION['flash'] = ['type' => 'warning', 'message' => '请先登录后再编辑宠物。'];
    header('Location: login.php');
    exit;
}

// 获取宠物信息 + 所有权验证
$petId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
if ($petId <= 0) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => '无效的宠物 ID。'];
    header('Location: pets.php');
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM pets WHERE pet_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $petId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pet = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$pet) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => '宠物不存在。'];
    header('Location: pets.php');
    exit;
}

if ((int)$pet['user_id'] !== (int)$_SESSION['user_id']) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => '无权编辑此宠物。'];
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $species = $_POST['species'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($name === '') $errors[] = '宠物名称为必填项。';
    if ($species === '') $errors[] = '请选择物种。';
    $validSpecies = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
    if (!in_array($species, $validSpecies, true)) $errors[] = '无效的物种选择。';
    if ($description === '') $errors[] = '描述为必填项。';

    $breed = trim($_POST['breed'] ?? '') ?: '';
    $ageYears = $_POST['age_years'] !== '' ? (int)$_POST['age_years'] : 0;
    $ageMonths = $_POST['age_months'] !== '' ? (int)$_POST['age_months'] : 0;
    $gender = $_POST['gender'] ?? 'Unknown';
    $size = $_POST['size'] ?? 'Medium';
    $healthInfo = trim($_POST['health_info'] ?? '') ?: '';
    $adoptionFee = $_POST['adoption_fee'] !== '' ? (float)$_POST['adoption_fee'] : 0.00;
    $status = $_POST['status'] ?? 'Available';

    $imagePath = $pet['image_path'];
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            $errors[] = '无效的图片格式。仅支持：jpg, jpeg, png, gif, webp。';
        } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = '图片上传失败。';
        } else {
            $newName = uniqid() . '.' . $ext;
            $target = 'assets/images/pets/' . $newName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                // 删除旧图片
                if (!empty($pet['image_path'])) {
                    $oldFile = 'assets/images/pets/' . $pet['image_path'];
                    if (file_exists($oldFile)) unlink($oldFile);
                }
                $imagePath = $newName;
            } else {
                $errors[] = '保存上传图片失败。';
            }
        }
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn,
            "UPDATE pets SET name=?, species=?, breed=?, age_years=?, age_months=?,
             gender=?, size=?, description=?, health_info=?, image_path=?,
             adoption_fee=?, status=? WHERE pet_id=?");
        mysqli_stmt_bind_param($stmt, 'sssiisssssdsi',
            $name, $species, $breed, $ageYears, $ageMonths,
            $gender, $size, $description, $healthInfo, $imagePath,
            $adoptionFee, $status, $petId);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => '宠物 "' . htmlspecialchars($name) . '" 更新成功！'];
            header('Location: details.php?id=' . $petId);
            exit;
        } else {
            $errors[] = '数据库错误：更新失败。';
        }
        mysqli_stmt_close($stmt);
    }
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">编辑宠物</h1>

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
                <label for="name" class="form-label">宠物名称 *</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="<?= htmlspecialchars($_POST['name'] ?? $pet['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="species" class="form-label">物种 *</label>
                <select name="species" id="species" class="form-select" required>
                    <option value="">-- 请选择 --</option>
                    <?php foreach (['Dog' => '狗', 'Cat' => '猫', 'Bird' => '鸟', 'Rabbit' => '兔', 'Other' => '其他'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['species'] ?? $pet['species']) === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="breed" class="form-label">品种</label>
                <input type="text" name="breed" id="breed" class="form-control"
                       value="<?= htmlspecialchars($_POST['breed'] ?? $pet['breed'] ?? '') ?>">
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="age_years" class="form-label">年龄（年）</label>
                    <input type="number" name="age_years" id="age_years" class="form-control" min="0" max="30"
                           value="<?= htmlspecialchars($_POST['age_years'] ?? $pet['age_years'] ?? '') ?>">
                </div>
                <div class="col-6">
                    <label for="age_months" class="form-label">年龄（月）</label>
                    <input type="number" name="age_months" id="age_months" class="form-control" min="0" max="11"
                           value="<?= htmlspecialchars($_POST['age_months'] ?? $pet['age_months'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">性别</label>
                <select name="gender" id="gender" class="form-select">
                    <?php foreach (['Male' => '雄性', 'Female' => '雌性', 'Unknown' => '未知'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['gender'] ?? $pet['gender']) === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="size" class="form-label">体型</label>
                <select name="size" id="size" class="form-select">
                    <?php foreach (['Small' => '小型', 'Medium' => '中型', 'Large' => '大型', 'Extra Large' => '超大型'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['size'] ?? $pet['size']) === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="description" class="form-label">描述 *</label>
                <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($_POST['description'] ?? $pet['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="health_info" class="form-label">健康信息</label>
                <textarea name="health_info" id="health_info" class="form-control" rows="3"><?= htmlspecialchars($_POST['health_info'] ?? $pet['health_info'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="adoption_fee" class="form-label">领养费用 ($)</label>
                <input type="number" name="adoption_fee" id="adoption_fee" class="form-control" min="0" step="0.01"
                       value="<?= htmlspecialchars($_POST['adoption_fee'] ?? $pet['adoption_fee']) ?>">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">状态</label>
                <select name="status" id="status" class="form-select">
                    <?php foreach (['Available' => '可领养', 'Pending' => '待处理', 'Adopted' => '已领养'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['status'] ?? $pet['status']) === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="imageInput" class="form-label">宠物图片</label>
                <?php if (!empty($pet['image_path'])): ?>
                <div class="mb-2">
                    <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>" alt="当前图片"
                         class="img-thumbnail" style="max-width: 150px;">
                    <small class="text-muted d-block">当前图片</small>
                </div>
                <?php endif; ?>
                <input type="file" name="image" id="imageInput" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
                <small id="imageError" class="text-danger d-none"></small>
                <div id="previewWrapper" class="d-none mt-2">
                    <p class="text-success">已选择有效图片：<span id="previewMeta"></span></p>
                    <img id="imagePreview" class="img-thumbnail" style="max-width: 200px;" alt="预览图片">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">保存修改</button>
        <a href="details.php?id=<?= (int)$pet['pet_id'] ?>" class="btn btn-outline-light">取消</a>
    </div>
</form>

<?php
require_once 'includes/footer.inc';
