<?php require_once __DIR__ . '/../config/configdatin.php'; ?>

<?php
function nav_link($href, $label, $match, $icon = 'default')
{
    $isActive = strpos($_SERVER['REQUEST_URI'], $match) !== false;
    $baseClass = 'flex items-center px-3 py-2 rounded-md hover:bg-primary-light transition-all';
    $activeClass = $isActive ? 'bg-white/10 text-white font-medium' : '';
    $icons = [
        'default' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" /></svg>',
        'map' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2m0 18v-4m0 4l6-3m0 0v-4m0 4l6-3m-6 3V4m0 0L9 7m0 0L3 4" /></svg>',
        'dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm10 8h8v-6h-8v6zm0-8h8V3h-8v10zM3 21h8v-6H3v6z"/></svg>'
    ];
    return "<a href=\"" . BASE_URL . $href . "\" class=\"$baseClass $activeClass\">{$icons[$icon]}$label</a>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/toast.php'; ?>

    <!-- NAVBAR -->
    <nav class="bg-primary text-white shadow-md" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-semibold"><?= APP_NAME ?></span>
                </div>
                <div class="flex md:hidden">
                    <button @click="open = !open" class="focus:outline-none text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <div class="hidden md:flex flex-wrap gap-2 items-center justify-end">
                    <?= nav_link('/kanwil_informasi.php', 'Dashboard', 'kanwil_informasi.php', 'dashboard') ?>
                    <?= nav_link('/', 'Data Pensiun', 'index.php') ?>
                    <?= nav_link('/pages/pegawai.php', 'Data Pegawai', 'pegawai.php') ?>
                    <?= nav_link('/pages/peta_interaktif.php', 'Sebaran Pegawai', 'peta_interaktif.php', 'map') ?>
                </div>
            </div>
            <!-- menu mobile -->
            <div class="md:hidden mt-2" x-show="open" x-transition>
                <div class="flex flex-col gap-2">
                    <?= nav_link('/kanwil_informasi.php', 'Dashboard', 'kanwil_informasi.php', 'dashboard') ?>
                    <?= nav_link('/', 'Data Pensiun', 'index.php') ?>
                    <?= nav_link('/pages/pegawai.php', 'Data Pegawai', 'pegawai.php') ?>
                    <?= nav_link('/pages/peta_interaktif.php', 'Sebaran Pegawai', 'peta_interaktif.php', 'map') ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">