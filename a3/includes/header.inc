<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect — <?= htmlspecialchars($pageTitle ?? '') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/images/favicon.ico">
    <script src="assets/js/scripts.js" defer></script>
</head>
<body>
<?php
// Flash 消息显示
if (!empty($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    $type = $flash['type'] ?? 'info';
    $message = $flash['message'] ?? '';
    echo '<div class="alert alert-' . htmlspecialchars($type) . ' alert-dismissible fade show mb-0 rounded-0" role="alert">';
    echo htmlspecialchars($message);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    unset($_SESSION['flash']);
}
?>
<main class="container py-4">
