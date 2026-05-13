<?php

$dbPath = __DIR__ . '/../storage/selections.sqlite';

try {

    $pdo = new PDO('sqlite:' . $dbPath);

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die('Database connection failed: ' . $e->getMessage());
}