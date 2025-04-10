    <?php

    require_once __DIR__ . '/../config/db.php';

    class PensiunManager
    {
        private $db;
        private $conn;

        public function __construct()
        {
            $this->db = new Database();
            $this->conn = $this->db->getConnection();
        }

        // Getter untuk ambil koneksi
        public function getConn()
        {
            return $this->conn;
        }

        // Get summary data
        public function getSummary() {
            $summary = [
                'total' => 0,
                'selesai' => 0,
                'diproses' => 0,
                'menunggu' => 0,
                'ditolak' => 0
            ];

            try {
                $query = "SELECT status, COUNT(*) as count FROM pensiun GROUP BY status";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                while ($row = $stmt->fetch()) {
                    $status = $row['status'];
                    $count = $row['count'];
                    $summary['total'] += $count;

                    switch ($status) {
                        case STATUS_SELESAI:
                            $summary['selesai'] = $count;
                            break;
                        case STATUS_DIPROSES:
                            $summary['diproses'] = $count;
                            break;
                        case STATUS_MENUNGGU:
                            $summary['menunggu'] = $count;
                            break;
                        case STATUS_DITOLAK:
                            $summary['ditolak'] = $count;
                            break;
                    }
                }

                return $summary;
            } catch (PDOException $e) {
                throw new Exception("Error getting summary: " . $e->getMessage());
            }
        }

        // Get all pensiun data with pagination, search and ordering
        public function getAllPensiun($start = 0, $length = 10, $search = '', $order = []) {
            try {
                // Check if pensiun table has any data
                $checkQuery = "SELECT COUNT(*) as count FROM pensiun";
                $stmt = $this->conn->prepare($checkQuery);
                $stmt->execute();
                $count = $stmt->fetch()['count'];
                
                if ($count === 0) {
                    return [
                        'data' => [],
                        'total' => 0,
                        'filtered' => 0
                    ];
                }

                // Base query
                $baseQuery = "FROM pensiun p 
                            LEFT JOIN jenis_pensiun jp ON p.jenis_pensiun_id = jp.id";

                // Where clause
                $whereClause = '';
                if ($search) {
                    $whereClause = " WHERE p.nama LIKE :search 
                                OR p.nip LIKE :search 
                                OR jp.nama_jenis LIKE :search 
                                OR p.tempat_tugas LIKE :search";
                }

                // Order clause
                $orderClause = " ORDER BY p.created_at DESC";
                if (!empty($order)) {
                    $validColumns = ['nama', 'nip', 'tmt_pensiun', 'jenis_pensiun_nama', 'tempat_tugas', 'status'];
                    $orderBy = [];
                    foreach ($order as $ord) {
                        if (isset($ord['column']) && in_array($ord['column'], $validColumns)) {
                            $direction = (isset($ord['dir']) && strtoupper($ord['dir']) === 'DESC') ? 'DESC' : 'ASC';
                            $column = $ord['column'] === 'jenis_pensiun_nama' ? 'jp.nama_jenis' : "p.{$ord['column']}";
                            $orderBy[] = "{$column} {$direction}";
                        }
                    }
                    if (!empty($orderBy)) {
                        $orderClause = " ORDER BY " . implode(', ', $orderBy);
                    }
                }

                // Get total records
                $totalQuery = "SELECT COUNT(*) as total " . $baseQuery;
                $stmt = $this->conn->prepare($totalQuery);
                $stmt->execute();
                $total = $stmt->fetch()['total'];

                // Get filtered records
                $filteredQuery = "SELECT COUNT(*) as filtered " . $baseQuery . $whereClause;
                $stmt = $this->conn->prepare($filteredQuery);
                if ($search) {
                    $searchParam = "%{$search}%";
                    $stmt->bindParam(':search', $searchParam);
                }
                $stmt->execute();
                $filtered = $stmt->fetch()['filtered'];

                // Get paginated data
                $query = "SELECT p.id, p.nip, p.nama, p.tmt_pensiun, p.tempat_tugas, 
                        p.jenis_pensiun_id, p.status, p.file_sk, p.created_at,
                        jp.nama_jenis as jenis_pensiun_nama " . 
                        $baseQuery . $whereClause . $orderClause . 
                        " LIMIT :start, :length";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':start', $start, PDO::PARAM_INT);
                $stmt->bindParam(':length', $length, PDO::PARAM_INT);
                if ($search) {
                    $stmt->bindParam(':search', $searchParam);
                }
                $stmt->execute();

                return [
                    'data' => $stmt->fetchAll(),
                    'total' => $total,
                    'filtered' => $filtered
                ];
            } catch (PDOException $e) {
                return [
                    'data' => [],
                    'total' => 0,
                    'filtered' => 0
                ];
            }
        }

        // Get pegawai by NIP for Select2
        public function searchPegawai($term) {
            try {
                $query = "SELECT 
                    nip,
                    nama,
                    induk_unit,
                    unit_kerja,
                    tmt_pensiun
                    FROM pegawai 
                    WHERE nip LIKE :term 
                    OR nama LIKE :term 
                    ORDER BY nama ASC 
                    LIMIT 10";

                $stmt = $this->conn->prepare($query);
                $searchTerm = "%{$term}%";
                $stmt->bindParam(':term', $searchTerm);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new Exception("Error searching pegawai: " . $e->getMessage());
            }
        }



        // Get summary of pegawai by kabupaten/kota
        public function getPegawaiByKabupaten()
        {
            try {
                // Insert data for missing locations if they don't exist
                $missingLocations = [
                    'Kantor Kementerian Agama Kota Bima',
                    'Kantor Kementerian Agama Kota Mataram',
                    'Kantor Kementerian Agama Kabupaten Sumbawa'
                ];

                foreach ($missingLocations as $location) {
                    $checkQuery = "SELECT COUNT(*) as count FROM pegawai WHERE induk_unit = :location";
                    $stmt = $this->conn->prepare($checkQuery);
                    $stmt->bindParam(':location', $location);
                    $stmt->execute();

                    if ($stmt->fetch()['count'] == 0) {
                        $timestamp = time();
                        $locationCode = substr(md5($location), 0, 4);
                        $randomStr = bin2hex(random_bytes(4));
                        $uniqueNip = 'PLACEHOLDER_' . $timestamp . '_' . $locationCode . '_' . $randomStr;
                        $insertQuery = "INSERT INTO pegawai (nip, nama, induk_unit, unit_kerja) VALUES (:nip, 'placeholder', :location, 'placeholder')";
                        $stmt = $this->conn->prepare($insertQuery);
                        $stmt->bindParam(':nip', $uniqueNip);
                        $stmt->bindParam(':location', $location);
                        $stmt->execute();
                        usleep(1000); // Add a small delay to ensure unique timestamps
                    }
                }

                $query = "SELECT 
            induk_unit,
            CASE 
                WHEN induk_unit LIKE '%Kota Mataram%' THEN 'Kota Mataram'
                WHEN induk_unit LIKE '%Kota Bima%' THEN 'Kota Bima'
                WHEN induk_unit LIKE '%Kabupaten Bima%' THEN 'Kabupaten Bima'
                WHEN induk_unit LIKE '%Kabupaten Sumbawa Barat%' THEN 'Kabupaten Sumbawa Barat'
                WHEN induk_unit LIKE '%Kabupaten Sumbawa%' THEN 'Kabupaten Sumbawa'
                WHEN induk_unit LIKE '%Kabupaten Lombok Utara%' THEN 'Kabupaten Lombok Utara'
                WHEN induk_unit LIKE '%Kabupaten Lombok Timur%' THEN 'Kabupaten Lombok Timur'
                WHEN induk_unit LIKE '%Kabupaten Lombok Tengah%' THEN 'Kabupaten Lombok Tengah'
                WHEN induk_unit LIKE '%Kabupaten Lombok Barat%' THEN 'Kabupaten Lombok Barat'
                WHEN induk_unit LIKE '%Kabupaten Dompu%' THEN 'Kabupaten Dompu'
                WHEN induk_unit LIKE 'Kanwil%' THEN 'Kanwil Kementerian Agama NTB'
                ELSE 'Lainnya'
            END as nama_singkat,
            COUNT(*) as total 
        FROM pegawai 
        WHERE induk_unit IS NOT NULL AND induk_unit != ''
        GROUP BY nama_singkat
        ORDER BY FIELD(nama_singkat,
            'Kanwil Kementerian Agama NTB',
            'Kota Mataram',
            'Kabupaten Lombok Barat',
            'Kabupaten Lombok Utara',
            'Kabupaten Lombok Tengah',
            'Kabupaten Lombok Timur',
            'Kabupaten Sumbawa Barat',
            'Kabupaten Sumbawa',
            'Kabupaten Dompu',
            'Kabupaten Bima',
            'Kota Bima'
        )";

                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                throw new Exception("Error getting pegawai summary by kabupaten: " . $e->getMessage());
            }
        }


        // Get all pegawai data with pagination, search and ordering
        public function getAllPegawai($start = 0, $length = 10, $search = '', $order = []) {
            try {
                // Check if pegawai table has any data
                $checkQuery = "SELECT COUNT(*) as count FROM pegawai";
                $stmt = $this->conn->prepare($checkQuery);
                $stmt->execute();
                $count = $stmt->fetch()['count'];
                
                if ($count === 0) {
                    return [
                        'data' => [],
                        'total' => 0,
                        'filtered' => 0
                    ];
                }

                // Base query
                $baseQuery = "FROM pegawai";

                // Where clause
                $whereClause = '';
                if ($search) {
                    $whereClause = " WHERE nip LIKE :search 
                                OR nama LIKE :search 
                                OR induk_unit LIKE :search 
                                OR unit_kerja LIKE :search";
                }

                // Order clause
                $orderClause = " ORDER BY nama ASC";
                if (!empty($order)) {
                    $validColumns = ['nip', 'nama', 'induk_unit', 'unit_kerja', 'tmt_pensiun'];
                    $orderBy = [];
                    foreach ($order as $ord) {
                        if (isset($ord['column']) && in_array($ord['column'], $validColumns)) {
                            $direction = (isset($ord['dir']) && strtoupper($ord['dir']) === 'DESC') ? 'DESC' : 'ASC';
                            $orderBy[] = "{$ord['column']} {$direction}";
                        }
                    }
                    if (!empty($orderBy)) {
                        $orderClause = " ORDER BY " . implode(', ', $orderBy);
                    }
                }

                // Get total records
                $totalQuery = "SELECT COUNT(*) as total " . $baseQuery;
                $stmt = $this->conn->prepare($totalQuery);
                $stmt->execute();
                $total = $stmt->fetch()['total'];

                // Get filtered records
                $filteredQuery = "SELECT COUNT(*) as filtered " . $baseQuery . $whereClause;
                $stmt = $this->conn->prepare($filteredQuery);
                if ($search) {
                    $searchParam = "%{$search}%";
                    $stmt->bindParam(':search', $searchParam);
                }
                $stmt->execute();
                $filtered = $stmt->fetch()['filtered'];

                // Get paginated data
                $query = "SELECT * " . $baseQuery . $whereClause . $orderClause . 
                        " LIMIT :start, :length";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':start', $start, PDO::PARAM_INT);
                $stmt->bindParam(':length', $length, PDO::PARAM_INT);
                if ($search) {
                    $stmt->bindParam(':search', $searchParam);
                }
                $stmt->execute();

                return [
                    'data' => $stmt->fetchAll(),
                    'total' => $total,
                    'filtered' => $filtered
                ];
            } catch (PDOException $e) {
                return [
                    'data' => [],
                    'total' => 0,
                    'filtered' => 0
                ];
            }
        }

        // Save or update pensiun data
        public function savePensiun($data) {
            try {
                // Validasi data
                if (!isset($data['jenis_pensiun_id']) || empty($data['jenis_pensiun_id'])) {
                    throw new Exception('Jenis pensiun harus diisi');
                }
                if (!isset($data['status']) || empty($data['status'])) {
                    throw new Exception('Status harus diisi');
                }

                // Validasi jenis_pensiun_id
                $checkJenisQuery = "SELECT id FROM jenis_pensiun WHERE id = :jenis_pensiun_id";
                $checkJenisStmt = $this->conn->prepare($checkJenisQuery);
                $checkJenisStmt->bindParam(':jenis_pensiun_id', $data['jenis_pensiun_id']);
                $checkJenisStmt->execute();
                if (!$checkJenisStmt->fetch()) {
                    throw new Exception('Jenis pensiun tidak valid');
                }

                $this->conn->beginTransaction();

                if (isset($data['id'])) {
                    // Validasi ID
                    $checkQuery = "SELECT id FROM pensiun WHERE id = :id";
                    $checkStmt = $this->conn->prepare($checkQuery);
                    $checkStmt->bindParam(':id', $data['id']);
                    $checkStmt->execute();
                    if (!$checkStmt->fetch()) {
                        throw new Exception('Data pensiun tidak ditemukan');
                    }

                    // Update
                    $query = "UPDATE pensiun SET 
                            jenis_pensiun_id = :jenis_pensiun_id,
                            status = :status,
                            file_sk = :file_sk,
                            updated_at = NOW()
                            WHERE id = :id";

                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':id', $data['id']);
                } else {
                    // Validasi NIP
                    if (!isset($data['nip']) || empty($data['nip'])) {
                        throw new Exception('Data pegawai harus dipilih');
                    }

                    // Cek apakah pegawai sudah ada di tabel pensiun
                    $checkQuery = "SELECT id FROM pensiun WHERE nip = :nip";
                    $checkStmt = $this->conn->prepare($checkQuery);
                    $checkStmt->bindParam(':nip', $data['nip']);
                    $checkStmt->execute();
                    if ($checkStmt->fetch()) {
                        throw new Exception('Data pensiun untuk pegawai ini sudah ada');
                    }

                    // Get pegawai data
                    $pegawaiQuery = "SELECT *, CONCAT(induk_unit, ' - ', unit_kerja) as tempat_tugas 
                                    FROM pegawai 
                                    WHERE nip = :nip";
                    $pegawaiStmt = $this->conn->prepare($pegawaiQuery);
                    $pegawaiStmt->bindParam(':nip', $data['nip']);
                    $pegawaiStmt->execute();
                    $pegawai = $pegawaiStmt->fetch();

                    if (!$pegawai) {
                        throw new Exception('Data pegawai tidak ditemukan');
                    }

                    // Insert with additional fields
                    $query = "INSERT INTO pensiun 
                            (nip, nama, tmt_pensiun, tempat_tugas, jenis_pensiun_id, status, file_sk, created_at, updated_at)
                            VALUES 
                            (:nip, :nama, :tmt_pensiun, :tempat_tugas, :jenis_pensiun_id, :status, :file_sk, NOW(), NOW())";

                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':nip', $data['nip'], PDO::PARAM_STR);
                    $stmt->bindParam(':nama', $pegawai['nama'], PDO::PARAM_STR);
                    $stmt->bindParam(':tmt_pensiun', $pegawai['tmt_pensiun'], PDO::PARAM_STR);
                    $stmt->bindParam(':tempat_tugas', $pegawai['tempat_tugas'], PDO::PARAM_STR);
                }

                $stmt->bindParam(':jenis_pensiun_id', $data['jenis_pensiun_id']);
                $stmt->bindParam(':status', $data['status']);
                $stmt->bindParam(':file_sk', $data['file_sk']);
                
                $stmt->execute();
                $this->conn->commit();

                return $data['id'] ?? $this->conn->lastInsertId();
            } catch (PDOException $e) {
                $this->conn->rollBack();
                throw new Exception("Error saving pensiun data: " . $e->getMessage());
            }
        }


        // Get jenis pensiun list
        public function getJenisPensiun() {
            try {
                $query = "SELECT id, nama_jenis FROM jenis_pensiun ORDER BY nama_jenis ASC";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new Exception("Error getting jenis pensiun: " . $e->getMessage());
            }
        }

        // Delete pensiun data
        public function deletePensiun($id) {
            try {
                // Get file_sk first
                $query = "SELECT file_sk FROM pensiun WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $result = $stmt->fetch();

                // Delete from database
                $query = "DELETE FROM pensiun WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                // Delete file if exists
                if ($result && $result['file_sk']) {
                    $filePath = UPLOAD_DIR . '/' . $result['file_sk'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                return true;
            } catch (PDOException $e) {
                throw new Exception("Error deleting pensiun data: " . $e->getMessage());
            }
        }
    }