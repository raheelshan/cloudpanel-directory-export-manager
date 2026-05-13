<?php

require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/database.php';

requireLogin();

$paths = $_POST['paths'] ?? [];

$pdo->exec("DELETE FROM excluded_paths");

$stmt = $pdo->prepare("
    INSERT INTO excluded_paths (path, selected)
    VALUES (:path, 1)
");

foreach ($paths as $path) {

    $stmt->execute([
        ':path' => $path
    ]);
}

echo json_encode([
    'success' => true
]);