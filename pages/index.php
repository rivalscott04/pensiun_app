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
$summary = $pensiunManager->getSummary();
include_once __DIR__. '/../components/header.php';
?>


<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-l-primary border-t border-r border-b border-gray-200 hover:shadow-xl transition-all duration-300 flex items-center">
        <div class="mr-4 text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <h3 class="text-gray-500 text-sm font-medium">Total Data</h3>
            <p class="text-2xl font-semibold text-primary" data-summary="total"><?= $summary['total'] ?></p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-l-supporting-200 border-t border-r border-b border-gray-200 hover:shadow-xl transition-all duration-300 flex items-center">
        <div class="mr-4 text-supporting-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <h3 class="text-gray-500 text-sm font-medium">Selesai</h3>
            <p class="text-2xl font-semibold text-supporting-200" data-summary="selesai"><?= $summary['selesai'] ?></p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-l-supporting-100 border-t border-r border-b border-gray-200 hover:shadow-xl transition-all duration-300 flex items-center">
        <div class="mr-4 text-supporting-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <h3 class="text-gray-500 text-sm font-medium">Diproses</h3>
            <p class="text-2xl font-semibold text-supporting-100" data-summary="diproses"><?= $summary['diproses'] ?></p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-l-secondary border-t border-r border-b border-gray-200 hover:shadow-xl transition-all duration-300 flex items-center">
        <div class="mr-4 text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <h3 class="text-gray-500 text-sm font-medium">Menunggu</h3>
            <p class="text-2xl font-semibold text-secondary" data-summary="menunggu"><?= $summary['menunggu'] ?></p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-l-supporting-300 border-t border-r border-b border-gray-200 hover:shadow-xl transition-all duration-300 flex items-center">
        <div class="mr-4 text-supporting-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <h3 class="text-gray-500 text-sm font-medium">Ditolak</h3>
            <p class="text-2xl font-semibold text-supporting-300" data-summary="ditolak"><?= $summary['ditolak'] ?></p>
        </div>
    </div>
</div>

<!-- DataTable Section -->
<div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Data Pensiun</h2>
        <div class="flex space-x-2">
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Data
            </button>
        </div>
    </div>

    <table id="pensiunTable" class="w-full">
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>TMT Pensiun</th>
                <th>Jenis Pensiun</th>
                <th>Tempat Tugas</th>
                <th>Status</th>
                <th>File SK</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal Form -->
<div id="formModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full backdrop-blur-sm transition-all duration-300">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-lg bg-white transform transition-all duration-300 scale-95 opacity-0">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800" id="modalTitle">Tambah Data Pensiun</h3>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="pensiunForm" onsubmit="savePensiun(event)">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIP</label>
                    <select id="nip" name="nip" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"></select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="nama" name="nama" class="mt-1 w-full rounded-md border-gray-300 bg-gray-50" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Induk Unit</label>
                    <input type="text" id="induk_unit" name="induk_unit" class="mt-1 w-full rounded-md border-gray-300 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                    <input type="text" id="unit_kerja" name="unit_kerja" class="mt-1 w-full rounded-md border-gray-300 bg-gray-50" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">TMT Pensiun</label>
                    <input type="text" id="tmt_pensiun" name="tmt_pensiun" class="mt-1 w-full rounded-md border-gray-300 bg-gray-50" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Pensiun</label>
                    <select id="jenis_pensiun" name="jenis_pensiun" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required></select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                        <option value="Menunggu">Menunggu</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>

                <div id="fileUploadSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">File SK</label>
                    <div class="mt-1 flex items-center justify-center w-full">
                        <label for="file_sk" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                <p class="text-xs text-gray-500">PDF (Maks. 5MB)</p>
                            </div>
                            <input type="file" id="file_sk" name="file_sk" accept=".pdf" class="hidden">
                        </label>
                    </div>
                    <div id="filePreview" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p id="fileName" class="text-sm font-medium text-gray-900"></p>
                                    <p id="fileSize" class="text-xs text-gray-500"></p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button type="button" onclick="previewFile()" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7z"></path>
                                    </svg>
                                </button>
                                <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button id="submitBtn" type="submit" class="px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let dataTable;
    let pollingInterval;

    // Document ready handler
    $(document).ready(function() {
        // Initialize components
        initializeDataTable();
        initializeSelect2();

        // Ensure modal is hidden on page load
        $('#formModal').addClass('hidden');
        $('#fileUploadSection').addClass('hidden');
        $('#filePreview').addClass('hidden');
    });

    // Initialize DataTable
    function initializeDataTable() {
        if ($.fn.DataTable.isDataTable('#pensiunTable')) {
            $('#pensiunTable').DataTable().destroy();
        }

        dataTable = $('#pensiunTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= BASE_URL ?>/api/pensiun-list.php',
                type: 'POST',
                data: function(d) {
                    return {
                        draw: d.draw,
                        start: d.start,
                        length: d.length,
                        search: d.search.value,
                        order: d.order.map(function(order) {
                            return {
                                column: d.columns[order.column].data,
                                dir: order.dir
                            };
                        })
                    };
                }
            },
            columns: [{
                    data: 'nip'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'tmt_pensiun',
                    render: function(data) {
                        return data ? new Date(data).toISOString().split('T')[0] : '-';
                    }
                },
                {
                    data: 'jenis_pensiun_nama'
                },
                {
                    data: 'tempat_tugas'
                },
                {
                    data: 'status',
                    render: function(data) {
                        const classes = {
                            'Menunggu': 'bg-yellow-100 text-yellow-800',
                            'Diproses': 'bg-blue-100 text-blue-800',
                            'Selesai': 'bg-green-100 text-green-800',
                            'Ditolak': 'bg-red-100 text-red-800'
                        };
                        return `<span class="px-2 py-1 rounded-full text-xs font-medium ${classes[data] || 'bg-gray-100 text-gray-800'}">${data}</span>`;
                    }
                },
                {
                    data: 'file_sk',
                    render: function(data) {
                        if (!data) return '-';
                        return `<a href="<?= BASE_URL ?>/uploads/${data}" target="_blank" class="text-primary hover:text-primary/90">Lihat File</a>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                        <div class="flex space-x-2">
                            <button onclick="editPensiun(${row.id})" class="text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button onclick="deletePensiun(${row.id})" class="text-red-600 hover:text-red-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            order: [
                [2, 'asc']
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });

        // Start polling
        startPolling();
    }

    // Initialize Select2
    function initializeSelect2() {
        $('#nip').select2({
            placeholder: 'Masukkan NIP atau nama pegawai',
            minimumInputLength: 3,
            ajax: {
                url: '<?= BASE_URL ?>/api/pegawai-search.php',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function(data) {
                    const results = data.map(pegawai => {
                        return {
                            id: pegawai.id,
                            text: pegawai.text,
                            pegawai: pegawai.pegawai
                        };
                    });
                    return {
                        results
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            const pegawai = e.params.data.pegawai;
            $('#pegawai_id').val(pegawai.id);
            $('#nama').val(pegawai.nama);
            $('#induk_unit').val(pegawai.induk_unit);
            $('#unit_kerja').val(pegawai.unit_kerja);
            $('#tmt_pensiun').val(new Date(pegawai.tmt_pensiun).toISOString().split('T')[0]);
        });

        // Load jenis pensiun
        $.getJSON('<?= BASE_URL ?>/api/jenis-pensiun.php', function(data) {
            const select = $('#jenis_pensiun');
            select.empty();
            select.append('<option value="">Pilih jenis pensiun</option>');
            data.forEach(item => {
                select.append(`<option value="${item.id}">${item.nama_jenis}</option>`);
            });
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error loading jenis pensiun:', textStatus, errorThrown);
        });
    }

    // Show/hide file upload based on status
    $('#status').change(function() {
        $('#fileUploadSection').toggleClass('hidden', $(this).val() !== 'Selesai');
        if ($(this).val() === 'Selesai') {
            $('#file_sk').prop('required', true);
        } else {
            $('#file_sk').prop('required', false);
        }
    });

    // Modal functions
    function openModal() {
        const modal = $('#formModal');
        const modalContent = modal.find('.relative');
        modal.removeClass('hidden');
        setTimeout(() => {
            modalContent.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 50);
        $('#modalTitle').text('Tambah Data Pensiun');
        $('#pensiunForm')[0].reset();
        $('#pensiun_id').val('');
        $('#pegawai_id').val('');
        $('#nip').val(null).trigger('change');
        $('#fileUploadSection').addClass('hidden');
    }

    function closeModal() {
        const modal = $('#formModal');
        const modalContent = modal.find('.relative');
        modalContent.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            modal.addClass('hidden');
            $('#pensiunForm')[0].reset();
        }, 200);
    }

    // Validasi form sebelum submit
    function validateForm() {
        const nip = $('#nip').val();
        const jenisPensiun = $('#jenis_pensiun').val();
        const status = $('#status').val();
        const fileInput = $('#file_sk')[0];

        if (!nip) {
            showToast('Pilih pegawai terlebih dahulu', 'error');
            return false;
        }

        if (!jenisPensiun) {
            showToast('Pilih jenis pensiun', 'error');
            return false;
        }

        if (!status) {
            showToast('Pilih status', 'error');
            return false;
        }

        if (status === 'Selesai' && fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['application/pdf'];

            if (file.size > maxSize) {
                showToast('Ukuran file terlalu besar (maksimal 5MB)', 'error');
                return false;
            }

            if (!allowedTypes.includes(file.type)) {
                showToast('Format file tidak valid (hanya PDF yang diizinkan)', 'error');
                return false;
            }
        }

        return true;
    }

    // Preview file sebelum upload
    function removeFile() {
        $('#file_sk').val('');
        $('#filePreview').addClass('hidden');
        $('#fileName').text('');
        $('#fileSize').text('');
    }

    $('#file_sk').change(function() {
        const file = this.files[0];
        if (file) {
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
            $('#fileName').text(file.name);
            $('#fileSize').text(`${fileSize} MB`);
            $('#filePreview').removeClass('hidden');
        } else {
            $('#filePreview').addClass('hidden');
            $('#fileName').text('');
            $('#fileSize').text('');
        }
    });

    // Save pensiun data
    function savePensiun(event) {
        event.preventDefault();

        if (!validateForm()) return;

        Swal.fire({
            title: 'Simpan Data?',
            text: 'Apakah Anda yakin ingin menyimpan data ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            const formData = new FormData($('#pensiunForm')[0]);
            const submitBtn = $('#submitBtn');
            const originalText = submitBtn.html();

            submitBtn.html(`
            <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.4 0 0 5.4 0 12h4zm2 5.3A8 8 0 014 12H0c0 3.04 1.1 5.8 2.9 7.9l3.1-2.6z"/>
            </svg> Menyimpan...`).prop('disabled', true);

            $.ajax({
                url: '<?= BASE_URL ?>/api/pensiun-save.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        showToast(response.message, 'success');
                        closeModal();
                        reloadData();
                    } else {
                        showToast(response.message || 'Gagal menyimpan data.', 'error');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan sistem saat menyimpan.', 'error');
                },
                complete: function() {
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });
    }


    // Edit pensiun
    function editPensiun(id) {
        $.getJSON(`<?= BASE_URL ?>/api/pensiun-detail.php?id=${id}`, function(data) {
            $('#modalTitle').text('Edit Data Pensiun');
            $('#pensiunForm')[0].reset();

            $('#pensiun_id').val(data.id);
            $('#pegawai_id').val(data.pegawai_id);

            const option = new Option(`${data.nip} - ${data.nama}`, data.nip, true, true);
            $('#nip').empty().append(option).trigger('change');
            $('#nip').prop('disabled', true);

            $('#nama').val(data.nama).prop('readonly', true);
            $('#induk_unit').val(data.induk_unit).prop('readonly', true);
            $('#unit_kerja').val(data.unit_kerja).prop('readonly', true);
            $('#tmt_pensiun').val(new Date(data.tmt_pensiun).toISOString().split('T')[0]).prop('readonly', true);

            $('#jenis_pensiun').val(data.jenis_pensiun).prop('disabled', true);
            $('#status').val(data.status).trigger('change');

            // Tampilkan file upload jika statusnya 'Selesai'
            if (data.status === 'Selesai') {
                $('#fileUploadSection').removeClass('hidden');
            } else {
                $('#fileUploadSection').addClass('hidden');
            }

            $('#formModal').removeClass('hidden');
            setTimeout(() => {
                $('.relative.top-20').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
            }, 50);
        });
    }


    // Delete pensiun
    function deletePensiun(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Apakah Anda yakin ingin menghapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4b49ac',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`<?= BASE_URL ?>/api/pensiun-save.php`, {
                    delete: true,
                    id: id
                }, function(response) {
                    if (response.status) {
                        showToast(response.message, 'success');
                        reloadData();
                    } else {
                        showToast(response.message, 'error');
                    }
                });
            }
        });
    }

    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();

        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500'
        };

        const toast = document.createElement('div');
        toast.className = `text-white px-4 py-2 rounded shadow-md mb-2 ${colors[type] || 'bg-gray-800'}`;
        toast.innerText = message;

        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 4000);
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 flex flex-col items-end';
        document.body.appendChild(container);
        return container;
    }

    // Polling function
    function startPolling() {
        pollingInterval = setInterval(reloadData, 10000); // Poll every 10 seconds
    }

    function reloadData() {
        dataTable.ajax.reload(null, false);

        // Update summary
        $.getJSON('<?= BASE_URL ?>/api/pensiun-list.php?summary=true', function(response) {
            const summary = response.summary;
            Object.keys(summary).forEach(key => {
                $(`[data-summary="${key}"]`).text(summary[key]);
            });
        });
    }

    // Cleanup on page unload
    $(window).on('unload', function() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    });

    function previewFile() {
        const file = $('#file_sk')[0].files[0];
        if (!file) return;

        const fileUrl = URL.createObjectURL(file);
        const previewModal = $(`
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="filePreviewModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl h-3/4 shadow-lg rounded-lg bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">${file.name}</h3>
                    <button onclick="closeFilePreview()" class="text-gray-600 hover:text-gray-800">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <iframe src="${fileUrl}" class="w-full h-full rounded-lg"></iframe>
            </div>
        </div>
    `).appendTo('body');
    }

    function closeFilePreview() {
        $('#filePreviewModal').remove();
    }
</script>

<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border-color: #D1D5DB !important;
        border-radius: 0.375rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 0.75rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>

<?php include __DIR__ . '/../components/footer.php'; ?>