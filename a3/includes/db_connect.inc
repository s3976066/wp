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
    // Jacob 5 (RMIT MySQL) — from the official "SQL server account Jacob5" email.
    // Host/Port/DB/Username are fixed below; ONLY the password is something you
    // must set yourself at https://access.csit.rmit.edu.au and paste in here.
    $dbHost = 'talsprddb02.int.its.rmit.edu.au';
    $dbUser = 's3976066';          // your student ID
    $dbPass = 'zyz00311';          // <-- your Jacob 5 password (set via access.csit.rmit.edu.au)
    $dbName = 's3976066';          // Jacob 5 DB = your student ID (you cannot create new DBs)
    $dbPort = 3306;
} else {
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'petconnect';
    $dbPort = 3306;
}

// PHP 8.1+ makes mysqli throw exceptions by default, which would bypass the
// if (!$conn) check below and leak connection details in the stack trace.
// Turn that off so the simple procedural error check works on Coreteaching.
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');
