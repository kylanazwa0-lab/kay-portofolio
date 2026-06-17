<?php
// ============================================================
// auth.php — Session / login handler
// ============================================================
require_once __DIR__ . '/config.php';

session_name(SESSION_NAME);
session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

function attemptLogin(string $user, string $pass): bool {
    if ($user === ADMIN_USERNAME && $pass === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $user;
        $_SESSION['login_time']      = time();
        return true;
    }
    return false;
}

function logout(): void {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
}
