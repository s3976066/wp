<?php
$pageTitle = 'Owner Profile';
require_once 'includes/db_connect.inc';

$userId = $_GET['user_id'] ?? null;
$error = null;
$user = null;
$pets = [];

if ($userId === null || $userId === '') {
    $error = '未指定用户。';
} elseif (!is_numeric($userId) || (int)$userId <= 0) {
    $error = '无效的用户 ID。';
}

if ($error === null) {
    $userId = (int)$userId;

    // 查询用户信息
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$user) {
        $error = '未找到该用户。';
    } else {
        // 查询该用户的所有宠物
        $stmt = mysqli_prepare($conn,
            "SELECT * FROM pets WHERE user_id = ? ORDER BY created_at DESC");
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $pets = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
    }
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<?php if ($error !== null): ?>
    <div class="text-center py-5">
        <span class="material-icons" style="font-size: 4rem; color: var(--text-muted);">person_off</span>
        <h2 class="mt-3"><?= htmlspecialchars($error) ?></h2>
        <a href="pets.php" class="btn btn-primary mt-3">浏览所有宠物</a>
    </div>
<?php else: ?>
    <h1 class="mb-4">主人资料</h1>

    <!-- 主人信息卡片 -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h2><?= htmlspecialchars($user['username']) ?></h2>
                    <p class="mb-1">
                        <span class="material-icons" style="font-size: 1rem; vertical-align: middle;">email</span>
                        <?= htmlspecialchars($user['email']) ?>
                    </p>
                    <?php if (!empty($user['phone'])): ?>
                    <p class="mb-1"><strong>电话：</strong><?= htmlspecialchars($user['phone']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($user['location'])): ?>
                    <p class="mb-1"><strong>所在地：</strong><?= htmlspecialchars($user['location']) ?></p>
                    <?php endif; ?>
                    <p class="mb-0 text-muted">
                        <small>加入时间：<?= date('Y年m月', strtotime($user['joined_at'])) ?></small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 主人的宠物 -->
    <h3 class="mb-3"><?= htmlspecialchars($user['username']) ?> 的宠物 (<?= count($pets) ?>)</h3>

    <?php if (empty($pets)): ?>
        <div class="text-center text-muted py-4">
            <p>该主人暂无宠物。</p>
            <a href="pets.php" class="btn btn-outline-light">浏览所有宠物</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($pets as $pet): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                        <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                             class="card-img-top" alt="<?= htmlspecialchars($pet['name']) ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                                <?= htmlspecialchars($pet['name']) ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($pet['species']) ?></p>
                        <span class="badge badge-<?= strtolower($pet['status']) ?>"><?= htmlspecialchars($pet['status']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
require_once 'includes/footer.inc';
