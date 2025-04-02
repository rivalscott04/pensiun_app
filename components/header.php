<?php require_once __DIR__ . '/../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>

    <!-- Tailwind CSS -->
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border-radius: 0.375rem;
            border-color: rgb(209 213 219);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.375rem;
            border: 1px solid rgb(209 213 219);
            padding: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 0.375rem;
            border: 1px solid rgb(209 213 219);
            padding: 0.25rem;
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/toast.php'; ?>
    <nav class="bg-primary text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-semibold"><?= APP_NAME ?></span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="<?= BASE_URL ?>" class="flex items-center px-3 py-2 rounded-md hover:bg-primary-light transition-all <?= strpos($_SERVER['REQUEST_URI'], 'index.php') !== false || $_SERVER['REQUEST_URI'] == BASE_URL ? 'bg-white/10 text-white font-medium' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        Beranda
                    </a>
                    <a href="<?= BASE_URL ?>/pages/pegawai.php" class="flex items-center px-3 py-2 rounded-md hover:bg-primary-light transition-all <?= strpos($_SERVER['REQUEST_URI'], 'pegawai.php') !== false ? 'bg-white/10 text-white font-medium' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        Data Pegawai
                    </a>
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center px-3 py-2 rounded-md hover:bg-primary-light transition-all" :class="{ 'bg-white/10': open }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User' ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 transform transition-transform duration-200" :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="<?= BASE_URL ?>/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary transition-colors duration-200">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">