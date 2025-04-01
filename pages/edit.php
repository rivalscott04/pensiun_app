<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/logic.php';

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ' . BASE_URL);
    exit;
}

$id = $_GET['id'];
$pensiunManager = new PensiunManager();
$jenisPensiun = $pensiunManager->getJenisPensiun();

// Ambil data pensiun
$query = "SELECT p.*, pg.nip, pg.nama, pg.induk_unit, pg.unit_kerja, pg.tmt_pensiun
          FROM pensiun p 
          JOIN pegawai pg ON p.pegawai_id = pg.id 
          WHERE p.id = :id";
$stmt = $pensiunManager->conn->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$pensiun = $stmt->fetch();

// Jika data tidak ditemukan
if (!$pensiun) {
    header('Location: ' . BASE_URL);
    exit;
}

include __DIR__ . '/../components/header.php';
?>

<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold text-gray-800">Edit Data Pensiun</h1>
    <a href="<?= BASE_URL ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form id="pensiunForm" onsubmit="savePensiun(event)">
        <input type="hidden" id="pensiun_id" name="id" value="<?= $pensiun['id'] ?>">
        <input type="hidden" id="pegawai_id" name="pegawai_id" value="<?= $pensiun['pegawai_id'] ?>">

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">NIP</label>
                <input type="text" value="<?= $pensiun['nip'] ?>" class="mt-1 w-full rounded-md border-gray-300 bg-gray-50" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="nama" value="<?= $pensiun['nama'] ?>" class="mt-1 w-full rounded-md border-gray-300 bg-gray-50" readonly>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Induk Unit</label>
                    <input type="text" id="induk_unit" value="<?= $pensiun['induk_unit'] ?>" class="mt-1 w-full rounded-md border-gray-300 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                    <input type="text" id="unit_kerja" value="<?= $pensiun['unit_kerja'] ?>" class="mt-1 w-full rounded-md border-gray-300 bg-gray-50" readonly>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">TMT Pensiun</label>
                <input type="text" id="tmt_pensiun" value="<?= date('d-m-Y', strtotime($pensiun['tmt_pensiun'])) ?>" class="mt-1 w-full rounded-md border-gray-300 bg-gray-50" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Pensiun</label>
                <select id="jenis_pensiun" name="jenis_pensiun" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    <option value="">Pilih jenis pensiun</option>
                    <?php foreach ($jenisPensiun as $jenis): ?>
                        <option value="<?= $jenis['nama_jenis'] ?>" <?= $pensiun['jenis_pensiun'] === $jenis['nama_jenis'] ? 'selected' : '' ?>>
                            <?= $jenis['nama_jenis'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    <option value="Menunggu" <?= $pensiun['status'] === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="Diproses" <?= $pensiun['status'] === 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="Selesai" <?= $pensiun['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="Ditolak" <?= $pensiun['status'] === 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>

            <div id="fileUploadSection" class="<?= $pensiun['status'] !== 'Selesai' ? 'hidden' : '' ?>">
                <label class="block text-sm font-medium text-gray-700">File SK</label>
                <?php if (!empty($pensiun['file_sk'])): ?>
                    <div class="flex items-center space-x-2 mb-2">
                        <a href="<?= BASE_URL ?>/uploads/<?= $pensiun['file_sk'] ?>" target="_blank" class="text-primary hover:text-primary/90 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                            File SK saat ini
                        </a>
                    </div>
                <?php endif; ?>
                <input type="file" id="file_sk" name="file_sk" accept=".pdf" class="mt-1 w-full">
                <p class="mt-1 text-sm text-gray-500">Format: PDF, Maksimal 5MB<?= !empty($pensiun['file_sk']) ? ' (Kosongkan jika tidak ingin mengubah file)' : '' ?></p>
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
// Show/hide file upload based on status
$(document).ready(function() {
    $('#status').change(function() {
        $('#fileUploadSection').toggleClass('hidden', $(this).val() !== 'Selesai');
        if ($(this).val() === 'Selesai' && '<?= empty($pensiun['file_sk']) ?>' === '1') {
            $('#file_sk').prop('required', true);
        } else {
            $('#file_sk').prop('required', false);
        }
    });
});

// Save pensiun data
function savePensiun(event) {
    event.preventDefault();

    const formData = new FormData($('#pensiunForm')[0]);
    
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
        error: function() {
            showToast('error', 'Terjadi kesalahan sistem');
        }
    });
}
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>