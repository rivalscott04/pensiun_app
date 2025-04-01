<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/helper.php';
require_once __DIR__ . '/../functions/logic.php';

header('Content-Type: application/json');

try {
    $pensiunManager = new PensiunManager();
    $result = $pensiunManager->getJenisPensiun();
    
    echo json_encode($result);

} catch (Exception $e) {
    jsonResponse(false, $e->getMessage(), []);
}