<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';

$pensiunManager = new PensiunManager();
$kabupatenData = $pensiunManager->getPegawaiByKabupaten();

include __DIR__ . '/../components/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Kabupaten/Kota Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <?php foreach ($kabupatenData as $data): ?>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-primary hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-800 font-semibold text-lg mb-2"><?= htmlspecialchars($data['induk_unit']) ?></h3>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600">Total Pegawai</span>
                        <span class="text-2xl font-bold text-primary"><?= $data['total'] ?></span>
                    </div>
                </div>
                <div class="bg-primary/10 p-3 rounded-full">
                    <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Daftar Pegawai</h2>
        </div>

        <div class="overflow-x-auto">
            <table id="pegawaiTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Induk Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Kerja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TMT Pensiun</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#pegawaiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '../api/pegawai-list.php',
            type: 'POST'
        },
        columns: [
            { data: 'nip' },
            { data: 'nama' },
            { data: 'induk_unit' },
            { data: 'unit_kerja' },
            { 
                data: 'tmt_pensiun',
                render: function(data) {
                    return data ? new Date(data).toISOString().split('T')[0] : '-';
                }
            }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        responsive: true,
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data per halaman',
            zeroRecords: 'Tidak ada data yang ditemukan',
            info: 'Menampilkan halaman _PAGE_ dari _PAGES_',
            infoEmpty: 'Tidak ada data yang tersedia',
            infoFiltered: '(difilter dari _MAX_ total data)',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Selanjutnya',
                previous: 'Sebelumnya'
            }
        },
        drawCallback: function() {
            $(this).find('tbody tr').addClass('bg-white border-b hover:bg-gray-50');
            $(this).find('tbody tr:nth-child(even)').addClass('bg-gray-50');
        }
    });
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>