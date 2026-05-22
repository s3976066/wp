<?php
$pageTitle = 'Edit Pet';
require_once 'includes/db_connect.inc';
require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>
<h1>编辑宠物</h1>
<p>Stage 5 内容即将实现。</p>

<!-- 图片预览钩子（Stage 3 JS 需要） -->
<div class="mt-4">
    <h5>更新图片</h5>
    <div class="mb-3">
        <label for="imageInput" class="form-label">选择新图片（可选）</label>
        <input type="file" name="image" id="imageInput" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
        <small id="imageError" class="text-danger d-none"></small>
        <div id="previewWrapper" class="d-none mt-2">
            <p class="text-success">已选择有效图片：<span id="previewMeta"></span></p>
            <img id="imagePreview" class="img-thumbnail" style="max-width: 200px;" alt="预览图片">
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.inc';
