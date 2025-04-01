<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';
require_once __DIR__ . '/../functions/helper.php';

header('Content-Type: application/json');

$pensiunManager = new PensiunManager();
$response = ['status' => false, 'message' => ''];

try {
    // For debugging
    error_log('POST data: ' . print_r($_POST, true));
    error_log('FILES data: ' . print_r($_FILES, true));

    // Handle delete request
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
    if (!isset($_POST['nip']) && !isset($_POST['id'])) {
        throw new Exception('Data pegawai tidak valid');
    }
    
    if (!isset($_POST['jenis_pensiun']) || empty($_POST['jenis_pensiun'])) {
        throw new Exception('Jenis pensiun harus dipilih');
    }
    
    if (!isset($_POST['status']) || empty($_POST['status'])) {
        throw new Exception('Status harus dipilih');
    }
    
    // Persiapkan data
    $data = [
        'jenis_pensiun' => $_POST['jenis_pensiun'],
        'status' => $_POST['status'],
        'file_sk' => ''
    ];
    
    // Jika ada ID, ini adalah update
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $data['id'] = $_POST['id'];
    } else {
        // Jika tidak ada ID, ini adalah insert baru
        if (!isset($_POST['nip']) || empty($_POST['nip'])) {
            throw new Exception('Data pegawai harus dipilih');
        }
        $data['nip'] = $_POST['nip'];
    }
    
    // Handle file upload jika status Selesai
    if ($_POST['status'] === 'Selesai') {
        // Jika update dan tidak ada file baru
        if (isset($_POST['id']) && (!isset($_FILES['file_sk']) || $_FILES['file_sk']['error'] === UPLOAD_ERR_NO_FILE)) {
            // Cek apakah sudah ada file sebelumnya
            $query = "SELECT file_sk FROM pensiun WHERE id = :id";
            $stmt = $pensiunManager->conn->prepare($query);
            $stmt->bindParam(':id', $_POST['id']);
            $stmt->execute();
            $result = $stmt->fetch();
            
            if (!$result || empty($result['file_sk'])) {
                throw new Exception('File SK harus diunggah untuk status Selesai');
            }
            
            // Gunakan file yang sudah ada
            $data['file_sk'] = $result['file_sk'];
        } else {
            // Validasi file baru
            if (!isset($_FILES['file_sk']) || $_FILES['file_sk']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File SK harus diunggah untuk status Selesai');
            }
            
            // Validasi tipe file
            $fileType = $_FILES['file_sk']['type'];
            if ($fileType !== 'application/pdf') {
                throw new Exception('File harus berformat PDF');
            }
            
            // Validasi ukuran file (max 5MB)
            $fileSize = $_FILES['file_sk']['size'];
            if ($fileSize > 5 * 1024 * 1024) {
                throw new Exception('Ukuran file maksimal 5MB');
            }
            
            // Generate nama file unik
            $fileName = 'SK_' . time() . '_' . uniqid() . '.pdf';
            $uploadPath = UPLOAD_DIR . '/' . $fileName;
            
            // Pindahkan file
            if (!move_uploaded_file($_FILES['file_sk']['tmp_name'], $uploadPath)) {
                throw new Exception('Gagal mengunggah file');
            }
            
            $data['file_sk'] = $fileName;
        }
    }
    
    // Simpan data
    $id = $pensiunManager->savePensiun($data);
    
    $response['status'] = true;
    $response['message'] = 'Data pensiun berhasil disimpan';
    $response['id'] = $id;
} catch (Exception $e) {
    error_log('Error in pensiun-save: ' . $e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);