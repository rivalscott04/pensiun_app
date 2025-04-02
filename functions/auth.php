<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                return true;
            }
        }
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return $this->isLoggedIn();
    }

    public function logout() {
        session_destroy();
        session_start();
    }

    public function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function validateCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }
        return true;
    }

    public function checkAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login.php');
            exit;
        }
        return true;
    }

    public function requireLogin() {
        return $this->checkAuth();
    }

    public function requireAdmin() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login.php');
            exit;
        }
    }

    public static function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}