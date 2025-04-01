<?php

// Format tanggal ke format Indonesia
function formatTanggal($date) {
    if (!$date) return '-';
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Sanitasi input
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Generate nama file unik untuk upload
function generateUniqueFileName($originalName, $extension) {
    return uniqid() . '_' . time() . '.' . $extension;
}

// Validasi file upload
function validateFileUpload($file, $allowedTypes = ['pdf'], $maxSize = 5242880) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Error dalam upload file'];
    }

    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileType, $allowedTypes)) {
        return ['status' => false, 'message' => 'Tipe file tidak diizinkan'];
    }

    if ($file['size'] > $maxSize) {
        return ['status' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }

    return ['status' => true];
}

// Format response JSON
function jsonResponse($status, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Validasi NIP
function validateNIP($nip) {
    return preg_match('/^\d{18}$/', $nip);
}

// Get status badge class
function getStatusBadgeClass($status) {
    $classes = [
        'Menunggu' => 'bg-yellow-100 text-yellow-800',
        'Diproses' => 'bg-blue-100 text-blue-800',
        'Selesai' => 'bg-green-100 text-green-800',
        'Ditolak' => 'bg-red-100 text-red-800'
    ];
    return $classes[$status] ?? 'bg-gray-100 text-gray-800';
}

// Format file size
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}