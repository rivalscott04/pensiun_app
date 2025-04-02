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
        $query = "SELECT p.*, jp.nama_jenis as jenis_pensiun_nama 
                 FROM pensiun p 
                 LEFT JOIN jenis_pensiun jp ON p.jenis_pensiun_id = jp.id 
                 WHERE p.id = :id";
        
        $stmt = $pensiunManager->getConn()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $response['data'] = $stmt->fetchAll();
        $response['status'] = true;
    } else {
        $data = $pensiunManager->getAllPensiun($start, $length, $search, $order);
        
        // Add action buttons to each row
        foreach ($data['data'] as &$row) {
            $row['actions'] = '<button class="btn btn-sm btn-primary me-1" onclick="editPensiun(' . $row['id'] . ')"><i class="bi bi-pencil"></i></button>' .
                             '<button class="btn btn-sm btn-danger" onclick="deletePensiun(' . $row['id'] . ')"><i class="bi bi-trash"></i></button>';
        }
        
        $response = [
            'draw' => $draw,
            'recordsTotal' => $data['total'],
            'recordsFiltered' => $data['filtered'],
            'data' => $data['data']
        ];
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
