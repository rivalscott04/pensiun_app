<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $pensiunManager = new PensiunManager();
    $data = $pensiunManager->getPegawaiByKabupaten();

    // Format output jadi hanya nama induk_unit (kab/kota) dan jumlahnya
    $result = array_map(function ($item) {
        return [
            'nama' => $item['induk_unit'],
            'jumlah' => (int) $item['total']
        ];
    }, $data);

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode([]);
}
