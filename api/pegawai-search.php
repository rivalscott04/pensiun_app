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
    
    // Debug input term
    error_log("Search term: " . $term);
    
    if (strlen($term) < 3) {
        echo json_encode([]);
        exit;
    }

    $pensiunManager = new PensiunManager();
    $result = $pensiunManager->searchPegawai($term);
    
    // Debug raw result
    error_log("Raw search result: " . json_encode($result));
    
    // Format response sesuai dengan format yang diharapkan Select2
    $formattedResult = array_map(function($item) {
        return [
            'id' => $item['nip'],
            'text' => $item['nip'] . ' - ' . $item['nama'],
            'pegawai' => [
                'id' => $item['nip'],
                'nama' => $item['nama'],
                'induk_unit' => $item['induk_unit'],
                'unit_kerja' => $item['unit_kerja'],
                'tmt_pensiun' => $item['tmt_pensiun']
            ]
        ];
    }, $result);
    
    // Debug formatted result
    error_log("Formatted result: " . json_encode($formattedResult));
    
    echo json_encode($formattedResult);

} catch (Exception $e) {
    error_log("Error in pegawai-search.php: " . $e->getMessage());
    jsonResponse(false, $e->getMessage(), []);
}