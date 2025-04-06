<?php
require_once __DIR__ . '/../functions/logic.php';

$manager = new PensiunManager();
$conn = $manager->getConn();

try {
    $query = "SELECT golongan, COUNT(*) AS jumlah 
              FROM pegawai 
              WHERE golongan IS NOT NULL AND golongan != '' 
              GROUP BY golongan 
              ORDER BY golongan";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
