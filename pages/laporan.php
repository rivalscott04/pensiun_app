<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/logic.php';

$database = new Database();
$conn = $database->getConnection();
$auth = new Auth($conn);
$auth->checkAuth();

include __DIR__ . '/../components/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" id="summaryCards">
    <!-- Cards will be injected here -->
</div>

<!-- DataTable Section -->
<div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Daftar Pegawai Sesuai Rentang TMT Pensiun</h2>
    <table id="laporanTable" class="w-full">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>Tempat Tugas</th>
                <th>TMT Pensiun</th>
                <th>Jenis Pensiun</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    let laporanTable;
    let selectedFilter = 'tahun_ini';

    $(document).ready(function() {
        loadSummaryCards();
        initDataTable();
    });

    function loadSummaryCards() {
        $.getJSON('<?= BASE_URL ?>/api/get_pensiun_per_tahun.php', function(data) {
            const now = new Date().getFullYear();
            let counts = {
                tahun_ini: 0,
                lima: 0,
                lima_lima_belas: 0,
                enambelas_lima_puluh: 0
            };

            data.forEach(row => {
                const tahun = parseInt(row.tahun);
                const jumlah = parseInt(row.jumlah);
                const selisih = tahun - now;

                if (selisih === 0) counts.tahun_ini += jumlah;
                else if (selisih >= 1 && selisih <= 5) counts.lima += jumlah;
                else if (selisih >= 6 && selisih <= 15) counts.lima_lima_belas += jumlah;
                else if (selisih >= 16 && selisih <= 50) counts.enambelas_lima_puluh += jumlah;
            });

            const icons = {
                tahun_ini: `<svg class="h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                </svg>`,
                lima: `<svg class="h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-6 4h6m2 5H5a2 2 0 01-2-2V7h18v12a2 2 0 01-2 2z" />
           </svg>`,
                lima_lima_belas: `<svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-4 8h4m-9 4h2m2 4h6a2 2 0 002-2V7H4v12a2 2 0 002 2h2" />
                     </svg>`,
                enambelas_lima_puluh: `<svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m4 8H4m5 4h6m-2 4h2a2 2 0 002-2V7H5v12a2 2 0 002 2h2" />
                            </svg>`
            };


            const borderColors = {
                tahun_ini: 'border-l-primary',
                lima: 'border-l-indigo-400',
                lima_lima_belas: 'border-l-blue-400',
                enambelas_lima_puluh: 'border-l-red-400'
            };

            const cards = [{
                    key: 'tahun_ini',
                    label: 'Tahun Ini',
                    count: counts.tahun_ini
                },
                {
                    key: 'lima',
                    label: '1–5 Tahun',
                    count: counts.lima
                },
                {
                    key: 'lima_lima_belas',
                    label: '6–15 Tahun',
                    count: counts.lima_lima_belas
                },
                {
                    key: 'enambelas_lima_puluh',
                    label: '16–50 Tahun',
                    count: counts.enambelas_lima_puluh
                }
            ];

            const $container = $('#summaryCards');
            $container.empty();

            cards.forEach(card => {
                $container.append(`
                <div class="bg-white p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg transition flex items-center gap-4 border-l-4 ${borderColors[card.key]}"
                     onclick="filterData('${card.key}')"
                     id="card-${card.key}">
                    <div class="bg-gray-100 p-2 rounded-full shadow-sm">
                        ${icons[card.key]}
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">${card.label}</h3>
                        <p class="text-2xl font-semibold text-primary">${card.count}</p>
                    </div>
                </div>
            `);
            });

            // Highlight default selected filter
            $(`#card-${selectedFilter}`).addClass('border-l-8');
        });
    }


    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#laporanTable')) {
            $('#laporanTable').DataTable().clear().destroy(); // <- tambahkan clear()
        }

        laporanTable = $('#laporanTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '<?= BASE_URL ?>/api/laporan_pensiun.php',
                type: 'POST',
                data: function(d) {
                    d.filter = selectedFilter;
                }
            },
            columns: [{
                    data: 'nama'
                },
                {
                    data: 'nip'
                },
                {
                    data: 'tempat_tugas'
                },
                {
                    data: 'tmt_pensiun'
                },
                {
                    data: 'jenis_pensiun_nama'
                }
            ],
            order: [
                [3, 'asc']
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });
    }


    function filterData(filterKey) {
        selectedFilter = filterKey;
        laporanTable.ajax.reload();

        // Highlight selected card
        $('#summaryCards > div').removeClass('border-l-primary');
        $(`#card-${filterKey}`).addClass('border-l-primary');
    }
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>