<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/logic.php';

$database = new Database();
$conn = $database->getConnection();
$auth = new Auth($conn);
$auth->checkAuth();

$pensiunManager = new PensiunManager();
$kabupatenData = $pensiunManager->getPegawaiByKabupaten();

include __DIR__ . '/../components/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Kabupaten/Kota Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <?php 
        $colors = ['#7da0fa', '#7978e9', '#f3797e'];
        $colorIndex = 0;
        foreach ($kabupatenData as $data): 
            $currentColor = $colors[$colorIndex % count($colors)];
        ?>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 hover:shadow-xl transition-shadow duration-300" style="border-left-color: <?= $currentColor ?>">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-800 font-semibold text-lg mb-2"><?= htmlspecialchars($data['induk_unit']) ?></h3>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600">Total Pegawai</span>
                        <span class="text-2xl font-bold" style="color: <?= $currentColor ?>"><?= $data['total'] ?></span>
                    </div>
                </div>
                <div class="p-3 rounded-full" style="background-color: <?= $currentColor ?>20">
                    <svg class="h-6 w-6" style="color: <?= $currentColor ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <?php 
            $colorIndex++;
        endforeach; 
        ?>
    </div>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Golongan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 transform transition-all duration-300 ease-in-out translate-x-full">
    <div class="flex items-center p-4 mb-4 rounded-lg shadow-lg min-w-[300px]" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg me-3">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"></svg>
        </div>
        <div class="ms-3 text-sm font-normal" id="toastMessage"></div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 p-1.5 inline-flex items-center justify-center h-8 w-8" onclick="hideToast()">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</div>

<!-- Modal Edit Pegawai -->
<div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full backdrop-blur-sm transition-all duration-300 z-50">
    <div class="relative top-20 mx-auto p-6 border w-[28rem] shadow-xl rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <div class="absolute top-4 right-4">
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="mt-2">
            <div class="flex items-center mb-6">
                <div class="p-3 rounded-full bg-blue-50 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Edit Data Pegawai</h3>
            </div>
            <form id="editForm" class="space-y-5">
                <input type="hidden" id="editNip" name="nip">
                <div>
                    <label for="editJabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" id="editJabatan" name="jabatan" class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200" required>
                    <p class="text-red-500 text-sm mt-1.5 hidden" id="jabatanError">Jabatan harus diisi</p>
                </div>
                <div>
                    <label for="editGolongan" class="block text-sm font-medium text-gray-700 mb-1">Golongan <span class="text-red-500">*</span></label>
                    <input type="text" id="editGolongan" name="golongan" class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200" required>
                    <p class="text-red-500 text-sm mt-1.5 hidden" id="golonganError">Golongan harus diisi</p>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const icon = toast.querySelector('svg');

    // Reset classes
    toast.querySelector('.flex').className = 'flex items-center p-4 mb-4 rounded-lg shadow-lg min-w-[300px]';
    icon.innerHTML = '';

    if (type === 'success') {
        toast.querySelector('.flex').classList.add('text-green-800', 'bg-green-50');
        icon.innerHTML = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>';
    } else {
        toast.querySelector('.flex').classList.add('text-red-800', 'bg-red-50');
        icon.innerHTML = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>';
    }

    toastMessage.textContent = message;
    toast.classList.remove('translate-x-full');
    toast.classList.add('translate-x-0');

    setTimeout(() => {
        hideToast();
    }, 3000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    toast.classList.remove('translate-x-0');
    toast.classList.add('translate-x-full');
}

function validateForm() {
    const jabatan = document.getElementById('editJabatan');
    const golongan = document.getElementById('editGolongan');
    const jabatanError = document.getElementById('jabatanError');
    const golonganError = document.getElementById('golonganError');
    let isValid = true;

    if (!jabatan.value.trim()) {
        jabatanError.classList.remove('hidden');
        jabatan.classList.add('border-red-500');
        isValid = false;
    } else {
        jabatanError.classList.add('hidden');
        jabatan.classList.remove('border-red-500');
    }

    if (!golongan.value.trim()) {
        golonganError.classList.remove('hidden');
        golongan.classList.add('border-red-500');
        isValid = false;
    } else {
        golonganError.classList.add('hidden');
        golongan.classList.remove('border-red-500');
    }

    return isValid;
}

function openEditModal(nip, jabatan, golongan) {
    document.getElementById('editNip').value = nip;
    document.getElementById('editJabatan').value = jabatan;
    document.getElementById('editGolongan').value = golongan;
    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 50);
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('modalContent');
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('jabatanError').classList.add('hidden');
        document.getElementById('golonganError').classList.add('hidden');
        document.getElementById('editJabatan').classList.remove('border-red-500');
        document.getElementById('editGolongan').classList.remove('border-red-500');
    }, 200);
}

function confirmSave(callback) {
    if (confirm('Apakah Anda yakin ingin menyimpan perubahan ini?')) {
        callback();
    }
}

// Inisialisasi WebSocket untuk realtime updates
const ws = new WebSocket('ws://' + window.location.hostname + ':8080');
ws.onmessage = function(event) {
    const data = JSON.parse(event.data);
    if (data.type === 'pegawai_update') {
        $('#pegawaiTable').DataTable().ajax.reload(null, false);
    }
};

$(document).ready(function() {
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }

        const formData = {
            nip: $('#editNip').val(),
            jabatan: $('#editJabatan').val(),
            golongan: $('#editGolongan').val()
        };

        confirmSave(() => {
            $.ajax({
                url: '../api/pegawai-update.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status) {
                        closeEditModal();
                        $('#pegawaiTable').DataTable().ajax.reload(null, false);
                        showToast('Data berhasil diperbarui', 'success');
                        // Broadcast update melalui WebSocket
                        ws.send(JSON.stringify({
                            type: 'pegawai_update',
                            data: formData
                        }));
                    } else {
                        showToast(response.message || 'Gagal memperbarui data', 'error');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan saat memperbarui data', 'error');
                }
            });
        });
    });



    $('#pegawaiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '../api/pegawai-list.php',
            type: 'POST'
        },
        columns: [
            { 
                data: 'nip',
                render: function(data) {
                    return '***' + data.substring(10);
                }
            },
            { data: 'nama' },
            { data: 'induk_unit' },
            { data: 'unit_kerja' },
            { 
                data: 'tmt_pensiun',
                render: function(data) {
                    return data ? new Date(data).toISOString().split('T')[0] : '-';
                }
            },
            { data: 'golongan' },
            {
                data: null,
                orderable: false,
                render: function(data) {
                    return `<button onclick="openEditModal('${data.nip}', '${data.jabatan || ''}', '${data.golongan || ''}')" class="text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>`;
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