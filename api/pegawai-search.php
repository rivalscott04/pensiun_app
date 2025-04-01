<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/helper.php';
require_once __DIR__ . '/../functions/logic.php';

header('Content-Type: application/json');

try {
    $term = isset($_GET['term']) ? sanitize($_GET['term']) : '';
    
    if (strlen($term) < 3) {
        echo json_encode([]);
        exit;
    }

    $pensiunManager = new PensiunManager();
    $result = $pensiunManager->searchPegawai($term);
    
    // Format response sesuai dengan format yang diharapkan Select2
    $formattedResult = array_map(function($item) {
        return [
            'id' => $item['id'],
            'text' => $item['nip'] . ' - ' . $item['nama'],
            'pegawai' => $item
        ];
    }, $result);
    
    echo json_encode($formattedResult);

} catch (Exception $e) {
    jsonResponse(false, $e->getMessage(), []);
}