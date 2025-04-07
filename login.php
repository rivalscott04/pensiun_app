<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/functions/auth.php';
require_once __DIR__ . '/components/ui/card.php';
require_once __DIR__ . '/components/ui/input.php';
require_once __DIR__ . '/components/ui/button.php';

$database = new Database();
$conn = $database->getConnection();
$auth = new Auth($conn);

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    header('Location: ' . BASE_URL . 'pages/index.php');
    exit;
}

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? Auth::sanitizeInput($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($auth->login($username, $password)) {
        header('Location: ' . BASE_URL . 'pages/index.php');
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
    <script src="/assets/js/tailwind.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4b49ac',
                        border: '#e2e8f0',
                        input: '#e2e8f0',
                        ring: '#4b49ac',
                        background: '#ffffff',
                        foreground: '#0f172a',
                        card: {
                            DEFAULT: '#ffffff',
                            foreground: '#0f172a'
                        },
                        muted: {
                            DEFAULT: '#f1f5f9',
                            foreground: '#64748b'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="flex w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Left Side: Illustration -->
        <div class="hidden md:flex w-1/2 bg-primary items-center justify-center p-6">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/application-login-4438907-3726717.png"
                alt="Ilustrasi Login"
                class="w-full h-auto object-contain" />
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2">
            <?php
            echo Card([
                'className' => 'border-0 shadow-none',
                'children' => 
                    CardHeader([
                        'children' => 
                            CardTitle([
                                'children' => 'Masuk ke Sistem Pensiun'
                            ]) .
                            CardDescription([
                                'children' => 'Masukkan kredensial Anda untuk mengakses sistem'
                            ])
                    ]) .
                    CardContent([
                        'children' => 
                            (isset($error) ? 
                            "<div class='mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm'>$error</div>" : '') .
                            "<form action='' method='POST' class='space-y-4'>" .
                                "<div class='space-y-2'>" .
                                    "<label for='username' class='text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70'>Username</label>" .
                                    Input([
                                        'type' => 'text',
                                        'id' => 'username',
                                        'name' => 'username',
                                        'placeholder' => 'Masukkan username',
                                        'required' => true
                                    ]) .
                                "</div>" .
                                "<div class='space-y-2'>" .
                                    "<label for='password' class='text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70'>Kata Sandi</label>" .
                                    Input([
                                        'type' => 'password',
                                        'id' => 'password',
                                        'name' => 'password',
                                        'placeholder' => 'Masukkan kata sandi',
                                        'required' => true
                                    ]) .
                                "</div>" .
                                Button([
                                    'type' => 'submit',
                                    'className' => 'w-full',
                                    'children' => 'Masuk'
                                ]) .
                            "</form>"
                    ])
            ]);
            ?>
        </div>
    </div>
</body>

</html>