<?php
$pageTitle = 'Add a Pet';
require_once 'includes/db_connect.inc';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 必填字段验证
    $name = trim($_POST['name'] ?? '');
    $species = $_POST['species'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($name === '') {
        $errors[] = '宠物名称为必填项。';
    }
    if ($species === '') {
        $errors[] = '请选择物种。';
    }
    $validSpecies = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
    if (!in_array($species, $validSpecies, true)) {
        $errors[] = '无效的物种选择。';
    }
    if ($description === '') {
        $errors[] = '描述为必填项。';
    }

    // 可选字段
    $breed = trim($_POST['breed'] ?? '') ?: '';
    $ageYears = $_POST['age_years'] !== '' ? (int)$_POST['age_years'] : 0;
    $ageMonths = $_POST['age_months'] !== '' ? (int)$_POST['age_months'] : 0;
    $gender = $_POST['gender'] ?? 'Unknown';
    $size = $_POST['size'] ?? 'Medium';
    $healthInfo = trim($_POST['health_info'] ?? '') ?: '';
    $adoptionFee = $_POST['adoption_fee'] !== '' ? (float)$_POST['adoption_fee'] : 0.00;
    $status = $_POST['status'] ?? 'Available';

    // 图片上传
    $imagePath = '';
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            $errors[] = '无效的图片格式。仅支持：jpg, jpeg, png, gif, webp。';
        } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = '图片上传失败。';
        } else {
            $imagePath = uniqid() . '.' . $ext;
            $target = 'assets/images/pets/' . $imagePath;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $errors[] = '保存上传图片失败。';
                $imagePath = '';
            }
        }
    }

    // 数据库插入
    if (empty($errors)) {
        // TODO Stage 5: 将 user_id 替换为 $_SESSION['user_id']
        $userId = 1;

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
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '宠物 "' . htmlspecialchars($name) . '" 添加成功！'
            ];
            header('Location: details.php?id=' . $newId);
            exit;
        } else {
            $errors[] = '数据库错误：保存宠物失败。';
        }
        mysqli_stmt_close($stmt);
    }
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">添加宠物</h1>

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
            <!-- 宠物名称 -->
            <div class="mb-3">
                <label for="name" class="form-label">宠物名称 *</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>

            <!-- 物种 -->
            <div class="mb-3">
                <label for="species" class="form-label">物种 *</label>
                <select name="species" id="species" class="form-select" required>
                    <option value="">-- 请选择 --</option>
                    <?php foreach (['Dog' => '狗', 'Cat' => '猫', 'Bird' => '鸟', 'Rabbit' => '兔', 'Other' => '其他'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['species'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 品种 -->
            <div class="mb-3">
                <label for="breed" class="form-label">品种</label>
                <input type="text" name="breed" id="breed" class="form-control"
                       value="<?= htmlspecialchars($_POST['breed'] ?? '') ?>">
            </div>

            <!-- 年龄 -->
            <div class="row mb-3">
                <div class="col-6">
                    <label for="age_years" class="form-label">年龄（年）</label>
                    <input type="number" name="age_years" id="age_years" class="form-control" min="0" max="30"
                           value="<?= htmlspecialchars($_POST['age_years'] ?? '') ?>">
                </div>
                <div class="col-6">
                    <label for="age_months" class="form-label">年龄（月）</label>
                    <input type="number" name="age_months" id="age_months" class="form-control" min="0" max="11"
                           value="<?= htmlspecialchars($_POST['age_months'] ?? '') ?>">
                </div>
            </div>

            <!-- 性别 -->
            <div class="mb-3">
                <label for="gender" class="form-label">性别</label>
                <select name="gender" id="gender" class="form-select">
                    <?php foreach (['Male' => '雄性', 'Female' => '雌性', 'Unknown' => '未知'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['gender'] ?? 'Unknown') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 体型 -->
            <div class="mb-3">
                <label for="size" class="form-label">体型</label>
                <select name="size" id="size" class="form-select">
                    <?php foreach (['Small' => '小型', 'Medium' => '中型', 'Large' => '大型', 'Extra Large' => '超大型'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['size'] ?? 'Medium') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <!-- 描述 -->
            <div class="mb-3">
                <label for="description" class="form-label">描述 *</label>
                <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <!-- 健康信息 -->
            <div class="mb-3">
                <label for="health_info" class="form-label">健康信息</label>
                <textarea name="health_info" id="health_info" class="form-control" rows="3"><?= htmlspecialchars($_POST['health_info'] ?? '') ?></textarea>
            </div>

            <!-- 领养费用 -->
            <div class="mb-3">
                <label for="adoption_fee" class="form-label">领养费用 ($)</label>
                <input type="number" name="adoption_fee" id="adoption_fee" class="form-control" min="0" step="0.01"
                       value="<?= htmlspecialchars($_POST['adoption_fee'] ?? '0.00') ?>">
            </div>

            <!-- 状态 -->
            <div class="mb-3">
                <label for="status" class="form-label">状态</label>
                <select name="status" id="status" class="form-select">
                    <?php foreach (['Available' => '可领养', 'Pending' => '待处理', 'Adopted' => '已领养'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($_POST['status'] ?? 'Available') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 图片上传 -->
            <div class="mb-3">
                <label for="imageInput" class="form-label">宠物图片</label>
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
        <button type="submit" class="btn btn-primary">添加宠物</button>
        <a href="index.php" class="btn btn-outline-light">取消</a>
    </div>
</form>

<?php
require_once 'includes/footer.inc';
