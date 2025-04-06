<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/functions/auth.php';

$database = new Database();
$conn = $database->getConnection();
$auth = new Auth($conn);

$auth->logout();
header('Location: ' . BASE_URL . '/kanwil_informasi.php');
exit;