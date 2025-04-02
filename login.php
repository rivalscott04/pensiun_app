<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/functions/auth.php';

$database = new Database();
$conn = $database->getConnection();
$auth = new Auth($conn);

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    header('Location: ' . BASE_URL);
    exit;
}

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? Auth::sanitizeInput($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ($auth->login($username, $password)) {
        header('Location: ' . BASE_URL);
        exit;
    } else {
        $error = 'Username atau password salah';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pensiun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4b49ac'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="flex w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Left Side: Illustration -->
        <div class="w-1/2 bg-primary flex items-center justify-center">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/application-login-4438907-3726717.png" 
                 alt="Ilustrasi Login" 
                 class="w-full h-auto object-cover" />
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-1/2 p-8">
            <h2 class="text-3xl font-semibold text-gray-700 mb-8">Masuk ke Sistem Pensiun</h2>
            <?php if (isset($error)): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-600">Username</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           placeholder="Masukkan username" 
                           class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-300 ease-in-out" 
                           required>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-600">Kata Sandi</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Masukkan kata sandi" 
                           class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-300 ease-in-out" 
                           required>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full py-2 bg-primary text-white font-semibold rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition duration-300 ease-in-out">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>