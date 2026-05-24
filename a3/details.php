<?php
$pageTitle = 'Pet Details';
require_once 'includes/db_connect.inc';

// Stage 5: 处理删除请求（在页面顶部，确保无输出）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (empty($_SESSION['user_id'])) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => '请先登录。'];
        header('Location: login.php');
        exit;
    }

    $deleteId = (int)($_POST['pet_id'] ?? 0);
    $stmt = mysqli_prepare($conn, "SELECT pet_id, user_id, image_path FROM pets WHERE pet_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $deleteId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $target = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$target) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => '宠物不存在。'];
        header('Location: index.php');
        exit;
    }

    if ((int)$target['user_id'] !== (int)$_SESSION['user_id']) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => '无权删除此宠物。'];
        header('Location: index.php');
        exit;
    }

    // 删除数据库记录
    $stmt = mysqli_prepare($conn, "DELETE FROM pets WHERE pet_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $deleteId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 删除图片文件
    if (!empty($target['image_path'])) {
        $filePath = 'assets/images/pets/' . $target['image_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $_SESSION['flash'] = ['type' => 'success', 'message' => '宠物已成功删除。'];
    header('Location: pets.php');
    exit;
}

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
$isOwner = false;

if ($error === null) {
    $petId = (int)$petId;

    $stmt = mysqli_prepare($conn, "SELECT * FROM pets WHERE pet_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $petId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $pet = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$pet) {
        $error = '未找到该宠物。该宠物可能已被移除。';
    } else {
        // 所有权判断（服务端条件渲染用）
        $isOwner = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$pet['user_id'];

        // 查询主人信息
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $pet['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $owner = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

function formatAge($years, $months) {
    $parts = [];
    if ($years !== null && $years > 0) $parts[] = $years . ' 岁';
    if ($months !== null && $months > 0) $parts[] = $months . ' 个月';
    if (empty($parts)) return '不足 1 个月';
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
        <div class="col-md-6 mb-4">
            <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                 class="img-fluid rounded w-100" style="object-fit: cover; max-height: 450px;"
                 alt="<?= htmlspecialchars($pet['name']) ?>">
        </div>

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

    <!-- 编辑/删除按钮（仅主人可见） -->
    <?php if ($isOwner): ?>
    <div class="d-flex gap-2 mt-4">
        <a href="edit.php?id=<?= (int)$pet['pet_id'] ?>" class="btn btn-primary">编辑</a>
        <button type="button" id="deleteBtn" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#deleteModal">
            删除
        </button>
    </div>

    <!-- 删除确认模态框 -->
    <div id="deleteModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">确认删除</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>确定要删除 <strong><?= htmlspecialchars($pet['name']) ?></strong> 吗？</p>
                    <p class="text-danger">此操作不可撤销，宠物的所有数据和图片将被永久删除。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">取消</button>
                    <form id="deleteForm" action="details.php" method="post" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="pet_id" value="<?= (int)$pet['pet_id'] ?>">
                        <button type="submit" id="confirmDelete" class="btn btn-danger">确认删除</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php endif; ?>

<?php
require_once 'includes/footer.inc';
