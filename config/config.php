<?php

// Base configuration
define('BASE_URL', 'http://localhost/pensiun_app');
define('UPLOAD_DIR', __DIR__ . '/../uploads');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'sdma');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application settings
define('APP_NAME', 'Sistem Informasi Pensiun');
define('APP_VERSION', '1.0.0');

// Status pensiun
define('STATUS_MENUNGGU', 'Menunggu');
define('STATUS_DIPROSES', 'Diproses');
define('STATUS_SELESAI', 'Selesai');
define('STATUS_DITOLAK', 'Ditolak');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session start
session_start();

// Time zone
date_default_timezone_set('Asia/Jakarta');