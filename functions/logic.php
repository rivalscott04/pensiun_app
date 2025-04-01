<?php

require_once __DIR__ . '/../config/db.php';

class PensiunManager {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
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

    // Get all pensiun data with pagination and search
    public function getAllPensiun($search = '') {
        try {
            $query = "SELECT p.*, pg.nip, pg.nama, pg.induk_unit, pg.unit_kerja, 
                      CONCAT(pg.induk_unit, ' - ', pg.unit_kerja) as tempat_tugas 
                      FROM pensiun p 
                      JOIN pegawai pg ON p.pegawai_id = pg.id 
                      WHERE pg.nama LIKE :search 
                      OR pg.nip LIKE :search 
                      OR p.jenis_pensiun LIKE :search 
                      OR CONCAT(pg.induk_unit, ' - ', pg.unit_kerja) LIKE :search 
                      ORDER BY p.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error getting pensiun data: " . $e->getMessage());
        }
    }

    // Get pegawai by NIP for Select2
    public function searchPegawai($term) {
        try {
            $query = "SELECT id, nip, nama, induk_unit, unit_kerja, tmt_pensiun 
                      FROM pegawai 
                      WHERE nip LIKE :term 
                      OR nama LIKE :term 
                      LIMIT 10";

            $stmt = $this->conn->prepare($query);
            $searchTerm = "%{$term}%";
            $stmt->bindParam(':term', $searchTerm);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error searching pegawai: " . $e->getMessage());
        }
    }

    // Save or update pensiun data
    public function savePensiun($data) {
        try {
            $this->conn->beginTransaction();

            if (isset($data['id'])) {
                // Update
                $query = "UPDATE pensiun SET 
                          jenis_pensiun = :jenis_pensiun,
                          status = :status,
                          file_sk = :file_sk,
                          updated_at = NOW()
                          WHERE id = :id";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $data['id']);
            } else {
                // Insert
                $query = "INSERT INTO pensiun 
                          (pegawai_id, jenis_pensiun, status, file_sk, created_at, updated_at)
                          VALUES 
                          (:pegawai_id, :jenis_pensiun, :status, :file_sk, NOW(), NOW())";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':pegawai_id', $data['pegawai_id']);
            }

            $stmt->bindParam(':jenis_pensiun', $data['jenis_pensiun']);
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
            $query = "SELECT * FROM jenis_pensiun ORDER BY nama_jenis";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll();
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