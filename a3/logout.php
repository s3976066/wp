<?php
// Stage 4: Logout — clear user data + redirect
require_once 'includes/db_connect.inc';

unset($_SESSION['user_id'], $_SESSION['username']);
$_SESSION['flash'] = ['type' => 'success', 'message' => 'You have been logged out.'];
session_regenerate_id(true);
header('Location: index.php');
exit;
