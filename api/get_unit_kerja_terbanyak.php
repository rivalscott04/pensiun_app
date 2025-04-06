<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("
        SELECT unit_kerja, COUNT(*) as jumlah
        FROM pegawai
        WHERE unit_kerja IS NOT NULL AND unit_kerja != ''
        GROUP BY unit_kerja
        ORDER BY jumlah DESC
        LIMIT 20
    ");
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
