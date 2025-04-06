<?php
require_once __DIR__ . '/../functions/logic.php';

$manager = new PensiunManager();
$conn = $manager->getConn();

try {
    $query = "SELECT 
                CASE 
                    WHEN tmt_pensiun IS NOT NULL AND tmt_pensiun <= CURDATE() THEN 'Sudah Pensiun'
                    ELSE 'Aktif'
                END AS status,
                COUNT(*) AS jumlah
              FROM pegawai
              GROUP BY status";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
