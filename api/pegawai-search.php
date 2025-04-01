<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/helper.php';
require_once __DIR__ . '/../functions/logic.php';

header('Content-Type: application/json');

try {
    $term = isset($_GET['term']) ? sanitize($_GET['term']) : '';
    
    if (strlen($term) < 3) {
        jsonResponse(false, 'Minimal 3 karakter', []);
    }

    $pensiunManager = new PensiunManager();
    $result = $pensiunManager->searchPegawai($term);
    
    echo json_encode($result);

} catch (Exception $e) {
    jsonResponse(false, $e->getMessage(), []);
}