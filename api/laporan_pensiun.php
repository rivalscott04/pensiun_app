<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once __DIR__ . '/../functions/logic.php';

try {
    $manager = new PensiunManager();
    $conn = $manager->getConn();

    $filter = $_POST['filter'] ?? 'tahun_ini';
    $startYear = (int)date('Y');
    $yearFilter = '';
    $params = [];

    switch ($filter) {
        case 'tahun_ini':
            $yearFilter = "AND YEAR(pg.tmt_pensiun) = ?";
            $params[] = $startYear;
            break;
        case 'lima':
            $yearFilter = "AND YEAR(pg.tmt_pensiun) BETWEEN ? AND ?";
            $params = [$startYear + 1, $startYear + 5];
            break;
        case 'lima_lima_belas':
            $yearFilter = "AND YEAR(pg.tmt_pensiun) BETWEEN ? AND ?";
            $params = [$startYear + 6, $startYear + 15];
            break;
        case 'enambelas_lima_puluh':
            $yearFilter = "AND YEAR(pg.tmt_pensiun) BETWEEN ? AND ?";
            $params = [$startYear + 16, $startYear + 50];
            break;
        default:
            $yearFilter = "";
    }

    $draw = intval($_POST['draw'] ?? 1);
    $start = intval($_POST['start'] ?? 0);
    $length = intval($_POST['length'] ?? 10);
    $search = $_POST['search']['value'] ?? '';

    // Total count
    $totalQuery = "SELECT COUNT(*) 
                   FROM pegawai pg
                   LEFT JOIN pensiun p ON pg.nip = p.nip
                   LEFT JOIN jenis_pensiun j ON p.jenis_pensiun_id = j.id
                   WHERE pg.tmt_pensiun IS NOT NULL $yearFilter";

    $stmtTotal = $conn->prepare($totalQuery);
    foreach ($params as $i => $param) {
        $stmtTotal->bindValue($i + 1, $param);
    }
    $stmtTotal->execute();
    $recordsTotal = $stmtTotal->fetchColumn();

    // Select data utama
    $sql = "SELECT 
                pg.nama, 
                pg.nip, 
                CONCAT(pg.unit_kerja, ' - ', pg.induk_unit) AS tempat_tugas,
                pg.tmt_pensiun,
                COALESCE(j.nama_jenis, 'BUP') AS jenis_pensiun_nama
            FROM pegawai pg
            LEFT JOIN pensiun p ON pg.nip = p.nip
            LEFT JOIN jenis_pensiun j ON p.jenis_pensiun_id = j.id
            WHERE pg.tmt_pensiun IS NOT NULL $yearFilter";

    if ($search) {
        $sql .= " AND (pg.nama LIKE :search OR pg.nip LIKE :search)";
    }

    $sql .= " ORDER BY pg.tmt_pensiun ASC LIMIT $start, $length";

    $stmt = $conn->prepare($sql);
    foreach ($params as $i => $param) {
        $stmt->bindValue($i + 1, $param);
    }
    if ($search) {
        $stmt->bindValue(':search', "%$search%");
    }

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsTotal,
        'data' => $data
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
