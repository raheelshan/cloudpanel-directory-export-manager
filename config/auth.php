<?php

session_start();

define('APP_USERNAME', 'admin');

define(
    'APP_PASSWORD_HASH',
    '$2y$12................................................................' // Replace with the actual hash of your password
);

function isLoggedIn(): bool
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}