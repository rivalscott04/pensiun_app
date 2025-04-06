<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("
        SELECT jabatan, induk_unit, COUNT(*) as jumlah
        FROM pegawai
        WHERE jabatan IS NOT NULL AND jabatan != '' AND induk_unit IS NOT NULL AND induk_unit != ''
        GROUP BY induk_unit, jabatan
    ");
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
