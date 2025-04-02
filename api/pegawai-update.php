<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';

header('Content-Type: application/json');

$response = [
    'status' => false,
    'message' => ''
];

try {
    if (!isset($_POST['nip']) || empty($_POST['nip'])) {
        throw new Exception('NIP harus diisi');
    }

    $nip = $_POST['nip'];
    $jabatan = isset($_POST['jabatan']) ? $_POST['jabatan'] : '';
    $golongan = isset($_POST['golongan']) ? $_POST['golongan'] : '';

    $pensiunManager = new PensiunManager();
    $conn = $pensiunManager->getConn();

    $query = "UPDATE pegawai SET jabatan = :jabatan, golongan = :golongan WHERE nip = :nip";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nip', $nip);
    $stmt->bindParam(':jabatan', $jabatan);
    $stmt->bindParam(':golongan', $golongan);
    
    if ($stmt->execute()) {
        $response['status'] = true;
        $response['message'] = 'Data berhasil diperbarui';
    } else {
        throw new Exception('Gagal memperbarui data');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);