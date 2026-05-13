<?php

require_once __DIR__ . '/config/auth.php';

if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (
        $username === APP_USERNAME &&
        password_verify($password, APP_PASSWORD_HASH)
    ) {

        $_SESSION['logged_in'] = true;

        header('Location: /index.php');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | Backup Excludes Manager</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-[#111827] min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        <!-- Logo / Heading -->
        <div class="text-center mb-8">

            <h1 class="text-3xl font-bold text-white">
                Backup Excludes Manager
            </h1>

            <p class="text-gray-400 mt-2 text-sm">
                CloudPanel Backup Control Dashboard
            </p>

        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                Sign In
            </h2>

            <?php if ($error): ?>

                <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3 text-sm">

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>

            <form method="POST" class="space-y-5">

                <!-- Username -->
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        required
                        autocomplete="username"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="Enter username"
                    >

                </div>

                <!-- Password -->
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="Enter password"
                    >

                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-medium py-3 rounded-lg shadow"
                >
                    Login
                </button>

            </form>

        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-xs text-gray-500">

            <?= date('Y') ?> © Backup Excludes Manager

        </div>

        <!-- Developer Attribution -->
        <div class="text-center mt-4 text-sm text-gray-600">
            <a href="https://raheelshan.com" target="_blank" class="text-gray-600 hover:text-blue-600">Developed by Raheel Shan</a>
        </div>

    </div>

</body>
</html>