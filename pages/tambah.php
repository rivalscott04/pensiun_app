<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';

$pensiunManager = new PensiunManager();
$jenisPensiun = $pensiunManager->getJenisPensiun();

include __DIR__ . '/../components/header.php';
?>

<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold text-gray-800">Tambah Data Pensiun</h1>
    <a href="<?= BASE_URL ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
    <form id="pensiunForm" onsubmit="savePensiun(event)">
        <input type="hidden" id="pegawai_id" name="pegawai_id">
        <!-- Added hidden field for NIP -->
        <input type="hidden" id="nip" name="nip">

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">NIP</label>
                <select id="nip_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 select2-input"></select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="nama" class="mt-1 w-full border-gray-300 bg-gray-50" readonly>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Induk Unit</label>
                    <input type="text" id="induk_unit" class="mt-1 w-full border-gray-300 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                    <input type="text" id="unit_kerja" class="mt-1 w-full border-gray-300 bg-gray-50" readonly>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">TMT Pensiun</label>
                <input type="text" id="tmt_pensiun" class="mt-1 w-full border-gray-300 bg-gray-50" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Pensiun</label>
                <select id="jenis_pensiun" name="jenis_pensiun" class="mt-1 w-full border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    <option value="">Pilih jenis pensiun</option>
                    <?php foreach ($jenisPensiun as $jenis): ?>
                        <option value="<?= $jenis['nama_jenis'] ?>"><?= $jenis['nama_jenis'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 w-full border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>

            <div id="fileUploadSection" class="hidden">
                <label class="block text-sm font-medium text-gray-700">File SK</label>
                <div class="mt-1 flex items-center space-x-2">
                    <label class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                        <span>Pilih File</span>
                        <input type="file" id="file_sk" name="file_sk" accept=".pdf" class="hidden" onchange="handleFileSelect(this)">
                    </label>
                    <button type="button" id="previewButton" class="hidden px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700" onclick="previewFile()">
                        Preview File
                    </button>
                    <span id="selectedFileName" class="text-sm text-gray-500"></span>
                </div>
                <p class="mt-1 text-sm text-gray-500">Format: PDF, Maksimal 5MB</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="<?= BASE_URL ?>" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
                Simpan
            </button>
        </div>
    </form>
</div>

<script>
// Initialize Select2
$(document).ready(function() {
    $('#nip_select').select2({
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
                return {
                    results: data.map(item => ({
                        id: item.nip, // Changed to use NIP as the ID
                        text: `${item.nip} - ${item.nama}`,
                        pegawai: item
                    }))
                };
            },
            cache: true
        }
    }).on('select2:select', function(e) {
        const pegawai = e.params.data.pegawai;
        $('#pegawai_id').val(pegawai.id);
        $('#nip').val(pegawai.nip); // Set the hidden NIP field
        $('#nama').val(pegawai.nama);
        $('#induk_unit').val(pegawai.induk_unit);
        $('#unit_kerja').val(pegawai.unit_kerja);
        $('#tmt_pensiun').val(new Date(pegawai.tmt_pensiun).toLocaleDateString('id-ID'));
    });

    // Show/hide file upload based on status
    $('#status').change(function() {
        $('#fileUploadSection').toggleClass('hidden', $(this).val() !== 'Selesai');
        if ($(this).val() === 'Selesai') {
            $('#file_sk').prop('required', true);
        } else {
            $('#file_sk').prop('required', false);
        }
    });
});

// Save pensiun data
function savePensiun(event) {
    event.preventDefault();

    if (!$('#nip').val()) {
        showToast('error', 'Silakan pilih pegawai terlebih dahulu');
        return;
    }

    const formData = new FormData($('#pensiunForm')[0]);
    
    // Log the form data for debugging
    console.log('Form data:', {
        nip: $('#nip').val(),
        jenis_pensiun: $('#jenis_pensiun').val(),
        status: $('#status').val()
    });
    
    $.ajax({
        url: '<?= BASE_URL ?>/api/pensiun-save.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status) {
                showSuccess('Berhasil', response.message);
                setTimeout(function() {
                    window.location.href = '<?= BASE_URL ?>';
                }, 1500);
            } else {
                showToast('error', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax error:', xhr.responseText);
            showToast('error', 'Terjadi kesalahan sistem');
        }
    });
}

function handleFileSelect(input) {
    const file = input.files[0];
    const fileNameSpan = document.getElementById('selectedFileName');
    const previewButton = document.getElementById('previewButton');
    
    if (file) {
        fileNameSpan.textContent = file.name;
        previewButton.classList.remove('hidden');
    } else {
        fileNameSpan.textContent = '';
        previewButton.classList.add('hidden');
    }
}

function previewFile() {
    const fileInput = document.getElementById('file_sk');
    const file = fileInput.files[0];
    
    if (file) {
        const fileURL = URL.createObjectURL(file);
        window.open(fileURL, '_blank');
    }
}
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>