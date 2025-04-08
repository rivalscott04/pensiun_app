<?php
require_once __DIR__ . '/../components/header_datin.php';
require_once __DIR__ . '/../components/ui/card.php';
?>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php
    echo Card([
        'className' => 'w-full',
        'children' =>
        CardHeader([
            'children' =>
            CardTitle(['children' => 'Peta Interaktif Pegawai']) .
                CardDescription(['children' => 'Visualisasi sebaran pegawai per Kabupaten/Kota di NTB'])
        ]) .
            CardContent([
                'className' => 'relative pt-4',
                'children' => '
                    <!-- Peta -->
                    <div id="map" class="w-full h-[500px] rounded-lg transition-all duration-300 ease-in-out"></div>

                    <!-- Loading State -->
                    <div id="loading" class="absolute inset-0 z-50 backdrop-blur-[2px] bg-white/60 rounded-lg transition-opacity duration-300">
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <div class="relative w-16 h-16">
                                <div class="absolute inset-0 rounded-full border-4 border-primary opacity-20"></div>
                                <div class="absolute inset-0 rounded-full border-4 border-primary border-t-transparent animate-spin"></div>
                            </div>
                            <p class="mt-4 text-sm font-medium text-primary animate-pulse">Memuat data peta dan pegawai...</p>
                        </div>
                    </div>
                '
            ])
    ]);
    ?>
</main>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" crossorigin=""></script>

<style>
    /* Custom Popup Styling */
    .custom-popup .leaflet-popup-content-wrapper {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .custom-popup .leaflet-popup-tip {
        background: white;
    }

    .custom-popup .leaflet-popup-content {
        margin: 0;
        line-height: 1.4;
    }

    /* Responsive Map Container */
    @media (max-width: 640px) {
        #map {
            height: 400px !important;
        }
    }

    /* Smooth Transitions */
    .leaflet-fade-anim .leaflet-tile,
    .leaflet-zoom-anim .leaflet-zoom-animated {
        will-change: transform;
        transition: opacity 0.25s linear, transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .leaflet-marker-icon {
        transition: transform 0.2s;
    }

    .leaflet-marker-icon:hover {
        transform: scale(1.2);
    }
</style>

<!-- Tailwind CSS (CDN atau ganti ke lokal jika perlu) -->
<script src="/assets/js/tailwind.min.js"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#5B21B6' // Warna ungu khas NTB
                }
            }
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        if (typeof L === 'undefined') {
            showToast('Gagal memuat peta. Silakan muat ulang halaman.', 'error');
            return;
        }

        const map = L.map('map', {
            zoomAnimation: true,
            fadeAnimation: true,
            markerZoomAnimation: true
        }).setView([-8.6, 117.5], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const kantorLocations = {
            "Kanwil Kementerian Agama NTB": [-8.578233, 116.102478],
            "Kota Mataram": [-8.586579, 116.117589],
            "Kabupaten Lombok Barat": [-8.683423, 116.138591],
            "Kabupaten Bima": [-8.462116, 118.746157],
            "Kabupaten Dompu": [-8.534303, 118.463704],
            "Kabupaten Lombok Tengah": [-8.704444, 116.271095],
            "Kabupaten Lombok Timur": [-8.6500, 116.5280],
            "Kabupaten Lombok Utara": [-8.352634, 116.180871],
            "Kabupaten Sumbawa": [-8.48820, 117.42370],
            "Kabupaten Sumbawa Barat": [-8.77324, 116.82535],
            "Kota Bima": [-8.462529, 118.746835]
        };

        try {
            let pegawaiData;

            if (localStorage.getItem('pegawaiData')) {
                pegawaiData = JSON.parse(localStorage.getItem('pegawaiData'));
            } else {
                const res = await fetch('/api/get_pegawai_kabupaten.php');
                pegawaiData = await res.json();
                localStorage.setItem('pegawaiData', JSON.stringify(pegawaiData));
            }

            const dataMap = {};
            pegawaiData.forEach(d => dataMap[d.nama] = d.jumlah);

            Object.entries(kantorLocations).forEach(([nama, coord]) => {
                const jumlah = dataMap[nama] || 0;
                const marker = L.marker(coord, {
                    title: nama,
                    riseOnHover: true,
                    riseOffset: 250
                }).addTo(map);

                const popupContent = `
                    <div class="p-2">
                        <h3 class="font-semibold text-primary mb-2">${nama}</h3>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-gray-700">Jumlah Pegawai: <span class="font-semibold text-primary">${jumlah}</span></span>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent, {
                    className: 'custom-popup',
                    maxWidth: 300,
                    minWidth: 200,
                    autoPan: true,
                    closeButton: false
                });
            });

        } catch (error) {
            console.error('Gagal memuat data pegawai:', error);
            showToast('Terjadi kesalahan saat memuat data pegawai. Silakan coba lagi.', 'error');
        } finally {
            document.getElementById('loading').classList.add('hidden');
        }
    });
</script>