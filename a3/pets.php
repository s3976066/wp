<?php
$pageTitle = 'Browse Pets';
require_once 'includes/db_connect.inc';

$stmt = mysqli_prepare($conn, "SELECT * FROM pets ORDER BY created_at DESC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pets = $result->fetch_all(MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

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

<!-- 横幅图片 -->
<div class="mb-5">
    <img src="assets/images/pets_banner.jpg" alt="Pets Banner"
         class="img-fluid rounded w-100" style="max-height: 300px; object-fit: cover;">
</div>

<h1 class="mb-4">浏览所有宠物</h1>

<?php if (empty($pets)): ?>
    <div class="text-center py-5">
        <span class="material-icons" style="font-size: 4rem; color: var(--text-muted);">pets</span>
        <h2 class="mt-3">暂无可领养的宠物</h2>
        <p class="text-muted">请稍后再来查看！</p>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($pets as $pet): ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="row g-0">
                    <div class="col-md-5">
                        <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                            <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                                 class="img-fluid rounded-start h-100 w-100"
                                 style="object-fit: cover; min-height: 220px;"
                                 alt="<?= htmlspecialchars($pet['name']) ?>">
                        </a>
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                                    <?= htmlspecialchars($pet['name']) ?>
                                </a>
                            </h5>
                            <p class="card-text mb-1"><strong>物种：</strong><?= htmlspecialchars($pet['species']) ?></p>
                            <?php if (!empty($pet['breed'])): ?>
                            <p class="card-text mb-1"><strong>品种：</strong><?= htmlspecialchars($pet['breed']) ?></p>
                            <?php endif; ?>
                            <p class="card-text mb-1"><strong>年龄：</strong><?= formatAge($pet['age_years'], $pet['age_months']) ?></p>
                            <p class="card-text mb-1"><strong>性别：</strong><?= htmlspecialchars($pet['gender']) ?></p>
                            <p class="card-text mb-2">
                                <strong>体型：</strong>
                                <span class="badge bg-secondary"><?= htmlspecialchars($pet['size']) ?></span>
                            </p>
                            <p class="card-text mb-2">
                                <span class="badge badge-<?= strtolower($pet['status']) ?>"><?= htmlspecialchars($pet['status']) ?></span>
                            </p>
                            <p class="card-text"><strong>费用：</strong>$<?= number_format($pet['adoption_fee'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
require_once 'includes/footer.inc';
