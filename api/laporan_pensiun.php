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

    // Build year filter
    switch ($filter) {
        case 'tahun_ini':
            $yearFilter = "AND YEAR(pg.tmt_pensiun) = :tahun";
            $params[':tahun'] = $startYear;
            break;
        case 'lima':
            $yearFilter = "AND YEAR(pg.tmt_pensiun) BETWEEN :start1 AND :end1";
            $params[':start1'] = $startYear + 1;
            $params[':end1'] = $startYear + 5;
            break;
        case 'lima_lima_belas':
            $yearFilter = "AND YEAR(pg.tmt_pensiun) BETWEEN :start2 AND :end2";
            $params[':start2'] = $startYear + 6;
            $params[':end2'] = $startYear + 15;
            break;
        case 'enambelas_lima_puluh':
            $yearFilter = "AND YEAR(pg.tmt_pensiun) BETWEEN :start3 AND :end3";
            $params[':start3'] = $startYear + 16;
            $params[':end3'] = $startYear + 50;
            break;
    }

    $draw = intval($_POST['draw'] ?? 1);
    $start = intval($_POST['start'] ?? 0);
    $length = intval($_POST['length'] ?? 10);
    $search = $_POST['search']['value'] ?? '';

    // Total records (tanpa search)
    $totalQuery = "SELECT COUNT(*) 
                   FROM pegawai pg
                   LEFT JOIN pensiun p ON pg.nip = p.nip
                   LEFT JOIN jenis_pensiun j ON p.jenis_pensiun_id = j.id
                   WHERE pg.tmt_pensiun IS NOT NULL $yearFilter";
    $stmtTotal = $conn->prepare($totalQuery);
    foreach ($params as $key => $value) {
        $stmtTotal->bindValue($key, $value);
    }
    $stmtTotal->execute();
    $recordsTotal = $stmtTotal->fetchColumn();

    // Filtered count (dengan search)
    $filteredQuery = "SELECT COUNT(*) 
                      FROM pegawai pg
                      LEFT JOIN pensiun p ON pg.nip = p.nip
                      LEFT JOIN jenis_pensiun j ON p.jenis_pensiun_id = j.id
                      WHERE pg.tmt_pensiun IS NOT NULL $yearFilter";
    if (!empty($search)) {
        $filteredQuery .= " AND (pg.nama LIKE :search OR pg.nip LIKE :search)";
    }
    $stmtFiltered = $conn->prepare($filteredQuery);
    foreach ($params as $key => $value) {
        $stmtFiltered->bindValue($key, $value);
    }
    if (!empty($search)) {
        $stmtFiltered->bindValue(':search', "%$search%");
    }
    $stmtFiltered->execute();
    $recordsFiltered = $stmtFiltered->fetchColumn();

    // Ambil data sebenarnya
    $dataQuery = "SELECT 
                    pg.nama, 
                    pg.nip, 
                    CONCAT(pg.unit_kerja, ' - ', pg.induk_unit) AS tempat_tugas,
                    pg.tmt_pensiun,
                    COALESCE(j.nama_jenis, 'BUP') AS jenis_pensiun_nama
                  FROM pegawai pg
                  LEFT JOIN pensiun p ON pg.nip = p.nip
                  LEFT JOIN jenis_pensiun j ON p.jenis_pensiun_id = j.id
                  WHERE pg.tmt_pensiun IS NOT NULL $yearFilter";
    if (!empty($search)) {
        $dataQuery .= " AND (pg.nama LIKE :search OR pg.nip LIKE :search)";
    }
    $dataQuery .= " ORDER BY pg.tmt_pensiun ASC LIMIT $start, $length";

    $stmt = $conn->prepare($dataQuery);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%");
    }
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Final response ke DataTables
    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
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
