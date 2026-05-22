<?php
// Stage 4: 销毁 session 并重定向到首页
require_once 'includes/db_connect.inc';

session_unset();
session_destroy();

$_SESSION['flash'] = ['type' => 'success', 'message' => '已成功登出。'];
header('Location: index.php');
exit;
