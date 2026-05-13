<?php

require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/database.php';

requireLogin();

$stmt = $pdo->query("
    SELECT path
    FROM excluded_paths
    WHERE selected = 1
");

$paths = $stmt->fetchAll(PDO::FETCH_COLUMN);

$content = implode(PHP_EOL, $paths);

$file = __DIR__ . '/storage/excludes.txt';

file_put_contents($file, $content);

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="excludes.txt"');

echo $content;
exit;