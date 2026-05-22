<?php
$pageTitle = 'Pet Details';
require_once 'includes/db_connect.inc';

// 输入验证
$petId = $_GET['id'] ?? null;
$error = null;

if ($petId === null || $petId === '') {
    $error = '未选择宠物。请从宠物列表中选择一只宠物。';
} elseif (!is_numeric($petId) || (int)$petId <= 0) {
    $error = '无效的宠物 ID。';
}

$pet = null;
$owner = null;

if ($error === null) {
    $petId = (int)$petId;

    // 查询宠物记录
    $stmt = mysqli_prepare($conn, "SELECT * FROM pets WHERE pet_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $petId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $pet = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$pet) {
        $error = '未找到该宠物。该宠物可能已被移除。';
    } else {
        // 查询主人信息
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $pet['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $owner = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

// 年龄格式化辅助函数
function formatAge($years, $months) {
    $parts = [];
    if ($years !== null && $years > 0) {
        $parts[] = $years . ' 岁';
    }
    if ($months !== null && $months > 0) {
        $parts[] = $months . ' 个月';
    }
    if (empty($parts)) {
        return '不足 1 个月';
    }
    return implode(' ', $parts);
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<?php if ($error !== null): ?>
    <div class="text-center py-5">
        <span class="material-icons" style="font-size: 4rem; color: var(--text-muted);">pets</span>
        <h2 class="mt-3"><?= htmlspecialchars($error) ?></h2>
        <a href="pets.php" class="btn btn-primary mt-3">浏览所有宠物</a>
    </div>
<?php else: ?>
    <div class="row">
        <!-- 左侧大图 -->
        <div class="col-md-6 mb-4">
            <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                 class="img-fluid rounded w-100" style="object-fit: cover; max-height: 450px;"
                 alt="<?= htmlspecialchars($pet['name']) ?>">
        </div>

        <!-- 右侧详情 -->
        <div class="col-md-6">
            <h2><?= htmlspecialchars($pet['name']) ?></h2>
            <span class="badge badge-<?= strtolower($pet['status']) ?> mb-3 fs-6">
                <?= htmlspecialchars($pet['status']) ?>
            </span>

            <table class="table table-borderless">
                <tr><th class="ps-0 text-muted">物种</th><td><?= htmlspecialchars($pet['species']) ?></td></tr>
                <?php if (!empty($pet['breed'])): ?>
                <tr><th class="ps-0 text-muted">品种</th><td><?= htmlspecialchars($pet['breed']) ?></td></tr>
                <?php endif; ?>
                <tr><th class="ps-0 text-muted">年龄</th><td><?= formatAge($pet['age_years'], $pet['age_months']) ?></td></tr>
                <tr><th class="ps-0 text-muted">性别</th><td><?= htmlspecialchars($pet['gender']) ?></td></tr>
                <tr><th class="ps-0 text-muted">体型</th><td><?= htmlspecialchars($pet['size']) ?></td></tr>
                <tr><th class="ps-0 text-muted">领养费用</th><td>$<?= number_format($pet['adoption_fee'], 2) ?></td></tr>
                <tr><th class="ps-0 text-muted">发布时间</th><td><?= date('Y-m-d', strtotime($pet['created_at'])) ?></td></tr>
            </table>

            <h5 class="mt-3">简介</h5>
            <p><?= nl2br(htmlspecialchars($pet['description'])) ?></p>

            <?php if (!empty($pet['health_info'])): ?>
            <h5>健康信息</h5>
            <p><?= nl2br(htmlspecialchars($pet['health_info'])) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 主人信息卡片 -->
    <?php if ($owner): ?>
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="card-title">联系主人</h4>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>用户名：</strong><?= htmlspecialchars($owner['username']) ?></p>
                    <p class="mb-1"><strong>邮箱：</strong><?= htmlspecialchars($owner['email']) ?></p>
                    <?php if (!empty($owner['phone'])): ?>
                    <p class="mb-1"><strong>电话：</strong><?= htmlspecialchars($owner['phone']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if (!empty($owner['location'])): ?>
                    <p class="mb-1"><strong>所在地：</strong><?= htmlspecialchars($owner['location']) ?></p>
                    <?php endif; ?>
                    <p class="mb-0"><strong>加入时间：</strong><?= date('Y-m-d', strtotime($owner['joined_at'])) ?></p>
                </div>
            </div>
            <a href="owner.php?user_id=<?= (int)$owner['user_id'] ?>" class="btn btn-outline-light mt-3">
                查看 <?= htmlspecialchars($owner['username']) ?> 的所有宠物
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Edit/Delete 按钮（Stage 5 添加所有权检查） -->
    <div class="d-flex gap-2 mt-4">
        <a href="edit.php?id=<?= (int)$pet['pet_id'] ?>" class="btn btn-primary">编辑</a>
        <form action="process_delete.php" method="post" class="d-inline">
            <input type="hidden" name="pet_id" value="<?= (int)$pet['pet_id'] ?>">
            <button type="submit" class="btn btn-secondary">删除</button>
        </form>
    </div>
<?php endif; ?>

<?php
require_once 'includes/footer.inc';
