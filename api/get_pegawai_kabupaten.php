<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $pensiunManager = new PensiunManager();

    // Ambil data jumlah pegawai berdasarkan induk_unit (yang sudah diisi)
    $query = "SELECT induk_unit, COUNT(*) as total
              FROM pegawai
              WHERE induk_unit IS NOT NULL AND induk_unit != ''
              GROUP BY induk_unit";

    $data = $pensiunManager->getPegawaiByKabupaten($query); // pastikan method ini ada

    // Format output
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
