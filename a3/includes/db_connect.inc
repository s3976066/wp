<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dual-environment detection: localhost vs Jacob 5
$server = $_SERVER['SERVER_NAME'] ?? 'localhost';
$isProd = (stripos($server, 'rmit.edu.au') !== false) ||
          (stripos($server, 'jupiter') !== false) ||
          (stripos($server, 'saturn') !== false) ||
          (stripos($server, 'titan') !== false);

if ($isProd) {
    $dbHost = 'talsprddb02.int.its.rmit.edu.au';
    $dbUser = 's3976066';
    $dbPass = 'zyz00311';
    $dbName = 's3976066';
} else {
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'petconnect';
}

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');
