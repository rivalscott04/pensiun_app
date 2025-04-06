<?php
require_once __DIR__ . '/../functions/logic.php';

$manager = new PensiunManager();
$conn = $manager->getConn();

try {
    $query = "SELECT YEAR(tmt_pensiun) AS tahun, COUNT(*) AS jumlah 
              FROM pegawai 
              WHERE tmt_pensiun IS NOT NULL 
              GROUP BY YEAR(tmt_pensiun) 
              ORDER BY tahun";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
