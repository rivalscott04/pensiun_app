<?php
// Disable error display and enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set error handler to return JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Internal server error',
        'error' => $errstr
    ]);
    exit;
});

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../functions/logic.php';

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'ID tidak valid']);
    exit;
}

$id = $_GET['id'];
$pensiunManager = new PensiunManager();

// Ambil data pensiun
$query = "SELECT 
    p.id,
    p.nip,
    p.nama,
    p.tmt_pensiun,
    p.tempat_tugas,
    j.nama_jenis AS jenis_pensiun,
    p.status,
    p.file_sk
FROM pensiun p
LEFT JOIN jenis_pensiun j ON p.jenis_pensiun_id = j.id
WHERE p.id = :id";

$stmt = $pensiunManager->getConn()->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$pensiun = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data tidak ditemukan
if (!$pensiun) {
    http_response_code(404);
    echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
    exit;
}

// Format tanggal
$pensiun['tmt_pensiun'] = date('Y-m-d', strtotime($pensiun['tmt_pensiun']));

echo json_encode(['status' => true, 'data' => $pensiun]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Internal server error',
        'error' => $e->getMessage()
    ]);
}