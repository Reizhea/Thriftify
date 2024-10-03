<?php
require_once('config.php');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);

// Set error mode to exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function get_db_connection() {
    global $pdo;
    return $pdo;
}
?>