<?php 
require_once __DIR__ . '/components/header_datin.php';
require_once __DIR__ . '/components/ui/accordion.php';
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-primary mb-6">Data dan Informasi Kanwil NTB</h1>

    <div class="space-y-6">
        <?php
        echo accordion_item(
            'Distribusi Jabatan',
            '<div class="bg-white rounded-lg p-4">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-4">
                <h2 class="text-lg font-semibold text-gray-800">5 Kabupaten/Kota dengan Jumlah Ragam Jabatan Terbanyak</h2>
                <select id="filterJabatan" class="border border-gray-300 rounded px-3 py-1 text-sm">
                    <option value="">-- Semua Kabupaten/Kota --</option>
                </select>
            </div>
            <div id="jabatanChart" class="w-full h-[400px]"></div>
        </div>',
            'jabatan'
        );

        echo accordion_item(
            'Distribusi Golongan',
            '<div class="bg-white rounded-lg p-4">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-4">
                <h2 class="text-lg font-semibold text-gray-800">5 Kabupaten/Kota dengan Jumlah Ragam Golongan Terbanyak</h2>
                <select id="filterGolongan" class="border border-gray-300 rounded px-3 py-1 text-sm">
                    <option value="">-- Semua Kabupaten/Kota --</option>
                </select>
            </div>
            <div id="golonganChart" class="w-full h-[400px]"></div>
        </div>',
            'golongan'
        );

        echo accordion_item(
            'Distribusi Status Kepegawaian',
            '<div class="bg-white rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Pegawai Berdasarkan Status Kepegawaian</h2>
            <div id="statusChart" class="w-full h-[400px]"></div>
        </div>',
            'status'
        );

        echo accordion_item(
            'Unit Kerja dengan Jumlah Pegawai Terbanyak',
            '<div class="bg-white rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">10 Unit Kerja dengan Jumlah Pegawai Terbanyak</h2>
            <div id="unitKerjaChart" class="w-full h-[400px]"></div>
        </div>',
            'unit-kerja'
        );

        echo accordion_item(
            'Distribusi Usia Pegawai',
            '<div class="bg-white rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Pegawai Berdasarkan Rentang Usia</h2>
            <div id="usiaChart" class="w-full h-[400px]"></div>
        </div>',
            'usia'
        );



        echo accordion_item(
            'Tren Pensiun Pegawai per Tahun',
            '<div class="bg-white rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tren Pensiun Pegawai per Tahun</h2>
            <div id="pensiunTrendChart" class="w-full h-[400px]"></div>
        </div>',
            'pensiun-trend'
        );
        ?>
    </div>
</main>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    function simplifyLabel(label) {
        if (label.includes("Kabupaten")) {
            return label.replace("Kantor Kementerian Agama Kabupaten ", "Kab. ");
        }
        if (label.includes("Kota")) {
            return label.replace("Kantor Kementerian Agama Kota ", "Kota ");
        }
        if (label.includes("Kanwil")) {
            return "Kanwil";
        }
        return label;
    }

    const jabatanRes = await fetch('/api/get_distribusi_jabatan.php');
    const jabatanData = await jabatanRes.json();

    const golonganRes = await fetch('/api/get_distribusi_golongan.php');
    const golonganData = await golonganRes.json();

    const unitKerjaRes = await fetch('/api/get_unit_kerja_terbanyak.php');
    const unitKerjaData = await unitKerjaRes.json();

    const pensiunRes = await fetch('/api/get_pensiun_per_tahun.php');
    const pensiunData = await pensiunRes.json();

    const usiaRes = await fetch('/api/get_distribusi_usia.php');
    const usiaData = await usiaRes.json();



    const filterJabatan = document.getElementById('filterJabatan');
    const filterGolongan = document.getElementById('filterGolongan');

    const allUnits = new Set([
        ...jabatanData.map(d => d.induk_unit),
        ...golonganData.map(d => d.induk_unit)
    ]);
    Array.from(allUnits).sort().forEach(unit => {
        const label = simplifyLabel(unit);
        const opt1 = new Option(label, unit);
        const opt2 = new Option(label, unit);
        filterJabatan.appendChild(opt1);
        filterGolongan.appendChild(opt2);
    });

    let jabatanChart, golonganChart, statusChart, unitKerjaChart, pensiunTrendChart, usiaChart;

    function renderJabatanChart(selectedUnit = '') {
        if (jabatanChart) jabatanChart.destroy();
        const filtered = jabatanData
            .filter(d => !selectedUnit || d.induk_unit === selectedUnit)
            .sort((a, b) => b.jumlah - a.jumlah)
            .slice(0, 5);
        jabatanChart = new ApexCharts(document.querySelector("#jabatanChart"), {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Jumlah Ragam Jabatan',
                data: filtered.map(d => d.jumlah)
            }],
            xaxis: {
                categories: filtered.map(d => simplifyLabel(d.induk_unit))
            },
            colors: ['#5C80BC']
        });
        jabatanChart.render();
    }

    function renderGolonganChart(selectedUnit = '') {
        if (golonganChart) golonganChart.destroy();
        const filtered = golonganData
            .filter(d => !selectedUnit || d.induk_unit === selectedUnit)
            .sort((a, b) => b.jumlah - a.jumlah)
            .slice(0, 5);
        golonganChart = new ApexCharts(document.querySelector("#golonganChart"), {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Jumlah Ragam Golongan',
                data: filtered.map(d => d.jumlah)
            }],
            xaxis: {
                categories: filtered.map(d => simplifyLabel(d.induk_unit))
            },
            colors: ['#6DA39D']
        });
        golonganChart.render();
    }

    function renderStatusChart() {
        if (statusChart) statusChart.destroy();
        const counts = {
            PNS: 0,
            PPPK: 0
        };
        golonganData.forEach(d => {
            if (/^[IV]+/.test(d.golongan)) {
                counts.PNS += d.jumlah;
            } else {
                counts.PPPK += d.jumlah;
            }
        });
        statusChart = new ApexCharts(document.querySelector("#statusChart"), {
            chart: {
                type: 'donut',
                height: 400
            },
            labels: ['PNS', 'PPPK'],
            series: [counts.PNS, counts.PPPK],
            colors: ['#457B9D', '#E76F51']
        });
        statusChart.render();
    }

    function renderUnitKerjaChart() {
        if (unitKerjaChart) unitKerjaChart.destroy();
        const sorted = unitKerjaData.sort((a, b) => b.jumlah - a.jumlah).slice(0, 10);
        unitKerjaChart = new ApexCharts(document.querySelector("#unitKerjaChart"), {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Jumlah Pegawai',
                data: sorted.map(d => d.jumlah)
            }],
            xaxis: {
                categories: sorted.map(d => d.unit_kerja.length > 40 ? d.unit_kerja.slice(0, 40) + '…' : d.unit_kerja),
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            tooltip: {
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    const item = unitKerjaData[dataPointIndex];
                    return '<div class="px-2 py-1 text-sm">' +
                        '<strong>' + item.unit_kerja + '</strong><br>' +
                        '<span class="text-gray-600">' + item.induk_unit + '</span><br>' +
                        'Jumlah: <strong>' + item.jumlah + '</strong>' +
                        '</div>';
                }
            },
            colors: ['#A67DB8']
        });
        unitKerjaChart.render();
    }

    function renderUsiaChart() {
        if (usiaChart) usiaChart.destroy();
        usiaChart = new ApexCharts(document.querySelector("#usiaChart"), {
            chart: {
                type: 'pie',
                height: 400
            },
            labels: usiaData.map(d => d.rentang_usia),
            series: usiaData.map(d => d.jumlah),
            colors: ['#4CAF50', '#2196F3', '#FFC107', '#F44336']
        });
        usiaChart.render();
    }



    function renderPensiunTrendChart() {
        if (pensiunTrendChart) pensiunTrendChart.destroy();
        pensiunTrendChart = new ApexCharts(document.querySelector("#pensiunTrendChart"), {
            chart: {
                type: 'line',
                height: 400
            },
            series: [{
                name: 'Jumlah Pensiun',
                data: pensiunData.map(d => d.jumlah)
            }],
            xaxis: {
                categories: pensiunData.map(d => d.tahun)
            },
            colors: ['#E63946']
        });
        pensiunTrendChart.render();
    }

    renderJabatanChart();
    renderGolonganChart();
    renderStatusChart();
    renderUnitKerjaChart();
    renderUsiaChart();

    renderPensiunTrendChart();

    filterJabatan.addEventListener('change', () => {
        renderJabatanChart(filterJabatan.value);
    });
    filterGolongan.addEventListener('change', () => {
        renderGolonganChart(filterGolongan.value);
    });
});
</script>