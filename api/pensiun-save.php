<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';
require_once __DIR__ . '/../functions/helper.php';

header('Content-Type: application/json');

function write_log($message) {
    $logFile = __DIR__ . '/../logs/debug-log.txt';
    $time = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$time] $message\n", FILE_APPEND);
}

// Pastikan path upload tersedia
if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', __DIR__ . '/../uploads');
}

$pensiunManager = new PensiunManager();
$response = ['status' => false, 'message' => ''];

try {
    // Log POST & FILES
    write_log('POST data: ' . print_r($_POST, true));
    write_log('FILES data: ' . print_r($_FILES, true));

    // Handle delete
    if (isset($_POST['delete']) && ($_POST['delete'] === 'true' || $_POST['delete'] === true)) {
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            throw new Exception('ID data tidak valid');
        }

        $id = sanitize($_POST['id']);
        $pensiunManager->deletePensiun($id);

        $response['status'] = true;
        $response['message'] = 'Data pensiun berhasil dihapus';
        echo json_encode($response);
        exit;
    }

    // Validasi input
    if (!isset($_POST['nip']) || empty($_POST['nip'])) {
        throw new Exception('Data pegawai tidak valid');
    }

    if (!isset($_POST['jenis_pensiun']) || empty($_POST['jenis_pensiun'])) {
        throw new Exception('Jenis pensiun harus dipilih');
    }

    if (!isset($_POST['status']) || empty($_POST['status'])) {
        throw new Exception('Status harus dipilih');
    }

    // Siapkan data
    $data = [
        'jenis_pensiun_id' => $_POST['jenis_pensiun'],
        'status' => $_POST['status'],
        'file_sk' => ''
    ];

    // Proses data baru atau update
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $data['id'] = $_POST['id'];
    } else {
        $data['nip'] = $_POST['nip'];

        $nip = $_POST['nip'];
        write_log('NIP dari POST: ' . $nip);

        // Cek NIP
        $stmt = $pensiunManager->getConn()->prepare("SELECT COUNT(*) FROM pegawai WHERE nip = :nip");
        $stmt->bindParam(':nip', $nip);
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {
            write_log('NIP tidak ditemukan: ' . $nip);
            throw new Exception('NIP tidak ditemukan di database pegawai');
        }
    }

    // Handle file upload kalau status "Selesai"
    if ($_POST['status'] === 'Selesai') {
        if (isset($_POST['id']) && (!isset($_FILES['file_sk']) || $_FILES['file_sk']['error'] === UPLOAD_ERR_NO_FILE)) {
            $query = "SELECT file_sk FROM pensiun WHERE id = :id";
            $stmt = $pensiunManager->getConn()->prepare($query);
            $stmt->bindParam(':id', $_POST['id']);
            $stmt->execute();
            $result = $stmt->fetch();

            if (!$result || empty($result['file_sk'])) {
                throw new Exception('File SK harus diunggah untuk status Selesai');
            }

            $data['file_sk'] = $result['file_sk'];
        } else {
            if (!isset($_FILES['file_sk']) || $_FILES['file_sk']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File SK harus diunggah untuk status Selesai');
            }

            if ($_FILES['file_sk']['type'] !== 'application/pdf') {
                throw new Exception('File harus berformat PDF');
            }

            if ($_FILES['file_sk']['size'] > 5 * 1024 * 1024) {
                throw new Exception('Ukuran file maksimal 5MB');
            }

            $fileName = 'SK_' . time() . '_' . uniqid() . '.pdf';
            $uploadPath = UPLOAD_DIR . '/' . $fileName;

            write_log('Upload path: ' . $uploadPath);

            if (!move_uploaded_file($_FILES['file_sk']['tmp_name'], $uploadPath)) {
                write_log('Gagal upload file');
                throw new Exception('Gagal mengunggah file');
            }

            $data['file_sk'] = $fileName;
        }
    }

    write_log('Data yang akan disimpan: ' . print_r($data, true));
    $id = $pensiunManager->savePensiun($data);

    $response['status'] = true;
    $response['message'] = 'Data pensiun berhasil disimpan';
    $response['id'] = $id;
} catch (Exception $e) {
    write_log('Exception: ' . $e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
