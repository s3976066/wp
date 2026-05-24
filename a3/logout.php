<?php
// Stage 4: 登出 — 清除用户数据 + 重定向
require_once 'includes/db_connect.inc';

unset($_SESSION['user_id'], $_SESSION['username']);
$_SESSION['flash'] = ['type' => 'success', 'message' => '已成功登出。'];
session_regenerate_id(true);
header('Location: index.php');
exit;
