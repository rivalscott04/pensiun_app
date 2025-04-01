<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';

header('Content-Type: application/json');

$pensiunManager = new PensiunManager();

// Get parameters from DataTables
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$order = isset($_POST['order']) ? $_POST['order'] : [];

// Convert DataTables column index to actual column names
$columns = ['nip', 'nama', 'induk_unit', 'unit_kerja', 'tmt_pensiun'];
if (!empty($order)) {
    foreach ($order as &$ord) {
        if (isset($ord['column']) && isset($columns[$ord['column']])) {
            $ord['column'] = $columns[$ord['column']];
        }
    }
}

// Get data with pagination and search
$data = $pensiunManager->getAllPegawai($start, $length, $search, $order);

// Prepare response
$response = [
    'draw' => $draw,
    'recordsTotal' => $data['total'],
    'recordsFiltered' => $data['filtered'],
    'data' => $data['data']
];

echo json_encode($response);