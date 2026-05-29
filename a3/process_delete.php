<?php
// Stage 5: Process delete request (ownership + prepared statement + image cleanup)
// Note: Delete logic now lives in details.php POST handler. This file is a fallback.

require_once 'includes/db_connect.inc';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Please log in first.'];
    header('Location: login.php');
    exit;
}

$_SESSION['flash'] = ['type' => 'danger', 'message' => 'Delete action must be submitted from the pet details page.'];
header('Location: index.php');
exit;
