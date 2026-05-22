<?php
// Stage 5: 处理删除请求（含属主验证 + prepared statement + 图片文件删除）
require_once 'includes/db_connect.inc';

// 未登录则重定向
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => '请先登录。'];
    header('Location: login.php');
    exit;
}

// 占位：Stage 5 实现删除逻辑
$_SESSION['flash'] = ['type' => 'warning', 'message' => '删除功能将在 Stage 5 实现。'];
header('Location: index.php');
exit;
