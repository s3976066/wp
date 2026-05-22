<?php
$pageTitle = 'Pet Gallery';
require_once 'includes/db_connect.inc';

$stmt = mysqli_prepare($conn, "SELECT * FROM pets ORDER BY created_at DESC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pets = $result->fetch_all(MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">宠物画廊</h1>

<!-- 筛选按钮（Stage 3 将添加 JS 功能） -->
<div class="mb-4 d-flex flex-wrap gap-2" id="galleryFilters">
    <button class="btn btn-outline-light filter-btn active" data-filter="all">全部</button>
    <button class="btn btn-outline-light filter-btn" data-filter="Available">可领养</button>
    <button class="btn btn-outline-light filter-btn" data-filter="Pending">待处理</button>
    <button class="btn btn-outline-light filter-btn" data-filter="Adopted">已领养</button>
</div>

<?php if (empty($pets)): ?>
    <div class="text-center py-5">
        <span class="material-icons" style="font-size: 4rem; color: var(--text-muted);">photo_library</span>
        <h2 class="mt-3">画廊中暂无宠物图片</h2>
    </div>
<?php else: ?>
    <div class="row" id="galleryGrid">
        <?php foreach ($pets as $pet): ?>
        <div class="col-md-4 col-lg-3 mb-4 gallery-item" data-status="<?= htmlspecialchars($pet['status']) ?>">
            <div class="card h-100">
                <a href="details.php?id=<?= (int)$pet['pet_id'] ?>" class="img-hover">
                    <img src="assets/images/pets/<?= htmlspecialchars($pet['image_path']) ?>"
                         class="card-img-top gallery-img"
                         alt="<?= htmlspecialchars($pet['name']) ?>">
                </a>
                <div class="card-body text-center">
                    <h6 class="card-title mb-1"><?= htmlspecialchars($pet['name']) ?></h6>
                    <p class="card-text mb-2"><small class="text-muted"><?= htmlspecialchars($pet['species']) ?></small></p>
                    <span class="badge badge-<?= strtolower($pet['status']) ?>"><?= htmlspecialchars($pet['status']) ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
require_once 'includes/footer.inc';
