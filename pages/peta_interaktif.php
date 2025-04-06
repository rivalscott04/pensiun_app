<?php require_once __DIR__ . '/../components/header_datin.php'; ?>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-primary mb-6">Peta Interaktif Pegawai per Kabupaten/Kota</h1>

    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200 relative">
        <!-- Map -->
        <div id="map" class="w-full h-[500px] rounded transition duration-300"></div>

        <!-- Spinner overlay -->
        <div id="loading" class="absolute inset-0 z-50 flex flex-col items-center justify-center backdrop-blur-sm bg-white/10">
            <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin mb-3"></div>
            <p class="text-sm text-gray-700 font-medium">Memuat data pegawai...</p>
        </div>
    </div>
</main>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" crossorigin=""></script>

<!-- Tailwind CDN (pastikan bisa diakses di lokal atau ganti ke lokal JS jika perlu) -->
<script src="/assets/js/tailwind.min.js"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#5B21B6' // Warna ungu custom
                }
            }
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        if (typeof L === 'undefined') {
            console.error('Leaflet gagal dimuat.');
            return;
        }

        const map = L.map('map').setView([-8.6, 117.5], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const kantorLocations = {
            "Kanwil Kementerian Agama NTB": [-8.578233, 116.102478],
            "Kota Mataram": [-8.586579, 116.117589],
            "Kabupaten Lombok Barat": [-8.683423, 116.138591],
            "Kabupaten Bima": [-8.4576, 118.7287],
            "Kabupaten Dompu": [-8.5315, 118.4620],
            "Kabupaten Lombok Tengah": [-8.6926, 116.2770],
            "Kabupaten Lombok Timur": [-8.6500, 116.5280],
            "Kabupaten Lombok Utara": [-8.3702, 116.2770],
            "Kabupaten Sumbawa": [-8.7707, 117.4074],
            "Kabupaten Sumbawa Barat": [-8.7869, 116.9865],
            "Kota Bima": [-8.4438, 118.7346]
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
                const marker = L.marker(coord).addTo(map);
                marker.bindPopup(`<strong>${nama}</strong><br>Jumlah Pegawai: <strong>${jumlah}</strong>`);
            });

        } catch (error) {
            console.error('Gagal memuat data pegawai:', error);
            alert('Terjadi kesalahan saat memuat data pegawai.');
        } finally {
            // Hilangkan loading spinner
            document.getElementById('loading').classList.add('hidden');
        }
    });
</script>