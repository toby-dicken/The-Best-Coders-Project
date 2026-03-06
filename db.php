<?php
// Load local config (not committed)
if (!file_exists(__DIR__ . '/config.php')) {
    die('Server configuration missing.');
}
require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $mysqli->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Don’t expose internal DB errors to users
    error_log('DB connection failed: ' . $e->getMessage());
    die('Database connection failed.');
}
