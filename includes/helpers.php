<?php

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function flash(string $key, ?string $message = null): ?string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $value = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $value;
}

function currentUser(): ?array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return $_SESSION['user'] ?? null;
}

function requireAuth(bool $admin = false): void
{
    $user = currentUser();

    if (!$user) {
        redirect('/public/login.php');
    }

    if ($admin && (int) $user['is_admin'] !== 1) {
        redirect('/public/dashboard.php');
    }
}

function referralCode(): string
{
    return strtoupper(bin2hex(random_bytes(4)));
}
