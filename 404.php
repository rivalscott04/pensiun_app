<?php require_once __DIR__ . '/config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4b49ac',
                        secondary: '#98bdff',
                        supporting: {
                            100: '#7da0fa',
                            200: '#7978e9',
                            300: '#f3797e'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="text-center">
        <!-- SVG Illustration -->
        <svg class="w-64 h-64 mx-auto mb-8" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="250" cy="250" r="200" fill="#4b49ac" fill-opacity="0.1"/>
            <path d="M316.9 194.3L183.1 305.7" stroke="#4b49ac" stroke-width="20" stroke-linecap="round"/>
            <path d="M183.1 194.3L316.9 305.7" stroke="#4b49ac" stroke-width="20" stroke-linecap="round"/>
            <circle cx="250" cy="250" r="150" stroke="#4b49ac" stroke-width="20" stroke-dasharray="20 20"/>
        </svg>

        <h1 class="text-4xl font-bold text-gray-800 mb-4">Oops! Halaman Tidak Ditemukan</h1>
        <p class="text-gray-600 text-lg mb-8">Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin telah dipindahkan atau dihapus.</p>
        
        <a href="<?= BASE_URL ?>/login.php" class="inline-flex items-center px-6 py-3 bg-primary text-white font-semibold rounded-lg shadow-md hover:bg-supporting-200 transform hover:scale-105 transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Kembali ke Login
        </a>
    </div>
</body>
</html>