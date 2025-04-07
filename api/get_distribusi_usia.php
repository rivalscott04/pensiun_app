<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("
        SELECT 
            CASE
                WHEN TIMESTAMPDIFF(YEAR, STR_TO_DATE(LEFT(nip, 8), '%Y%m%d'), CURDATE()) < 30 THEN 'Di bawah 30 tahun'
                WHEN TIMESTAMPDIFF(YEAR, STR_TO_DATE(LEFT(nip, 8), '%Y%m%d'), CURDATE()) BETWEEN 30 AND 40 THEN '30-40 tahun'
                WHEN TIMESTAMPDIFF(YEAR, STR_TO_DATE(LEFT(nip, 8), '%Y%m%d'), CURDATE()) BETWEEN 41 AND 50 THEN '41-50 tahun'
                ELSE 'Di atas 50 tahun'
            END as rentang_usia,
            COUNT(*) as jumlah
        FROM pegawai
        WHERE nip IS NOT NULL AND LENGTH(nip) >= 8
        GROUP BY rentang_usia
        ORDER BY FIELD(rentang_usia, 'Di bawah 30 tahun', '30-40 tahun', '41-50 tahun', 'Di atas 50 tahun')
    ");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}