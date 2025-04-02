<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';
require_once __DIR__ . '/../functions/helper.php';

header('Content-Type: application/json');

$pensiunManager = new PensiunManager();

// Get parameters from DataTables
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$order = isset($_POST['order']) ? $_POST['order'] : [];

// Get data with pagination and search
$data = $pensiunManager->getAllPensiun($start, $length, $search, $order);

// Prepare response
$response = [
    'draw' => $draw,
    'recordsTotal' => $data['total'],
    'recordsFiltered' => $data['filtered'],
    'data' => $data['data']
];

try {
    if (isset($_GET['summary']) && $_GET['summary'] === 'true') {
        $response['summary'] = $pensiunManager->getSummary();
        $response['status'] = true;
    }
    else if (isset($_GET['id'])) {
        $id = sanitize($_GET['id']);
        $query = "SELECT p.*, pg.nip, pg.nama, pg.induk_unit, pg.unit_kerja, 
                 CONCAT(pg.induk_unit, ' - ', pg.unit_kerja) as tempat_tugas 
                 FROM pensiun p 
                 JOIN pegawai pg ON p.pegawai_id = pg.id 
                 WHERE p.id = :id";
        
        $stmt = $pensiunManager->getConn()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $response['data'] = $stmt->fetchAll();
        $response['status'] = true;
    } else {
        $search = isset($_GET['search']) ? $_GET['search']['value'] : '';
        $response['data'] = $pensiunManager->getAllPensiun($search);
        $response['status'] = true;
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
