<?php
/**
 * db_connect.inc
 * PetConnect - Database Connection
 * Supports localhost (XAMPP) and Jacob 5 (talsprddb02.int.its.rmit.edu.au)
 * Student: Yizhao Zheng | s3976066
 */

// Detect environment by hostname
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if ($host === 'localhost' || $host === '127.0.0.1') {
    // ---- Local XAMPP ----
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = 'zyz00311';
    $db_name = 'petconnect';
} else {
    // ---- Jacob 5 (RMIT Coreteaching) ----
    $db_host = 'talsprddb02.int.its.rmit.edu.au';
    $db_user = 's3976066';
    $db_pass = 's3976066';   // Update with your actual Jacob5 password
    $db_name = 's3976066';   // Database named after student ID on Jacob5
}

// Enable MySQLi exception-based error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die('<p style="color:red;font-family:sans-serif;padding:2rem;">
        Database connection failed: ' . htmlspecialchars(mysqli_connect_error()) . '
    </p>');
}

// Set character encoding
mysqli_set_charset($conn, 'utf8mb4');
