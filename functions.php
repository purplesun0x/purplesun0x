<?php
require_once __DIR__ . '/config.php';

function generateReferralCode(): string
{
    return strtoupper(bin2hex(random_bytes(4)));
}

function currentUser(): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);

    $user = $stmt->fetch();

    return $user ?: null;
}

function isAdmin(): bool
{
    $user = currentUser();
    return $user !== null && $user['role'] === 'admin';
}

function requireLogin(): void
{
    if (!currentUser()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        header('Location: dashboard.php');
        exit;
    }
}

function redirectIfLoggedIn(): void
{
    if (currentUser()) {
        header('Location: dashboard.php');
        exit;
    }
}

function setFlash(string $key, string $value): void
{
    $_SESSION['flash'][$key] = $value;
}

function getFlash(string $key): ?string
{
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

function captureReferral(): void
{
    if (!empty($_GET['ref'])) {
        setcookie('referral_code', $_GET['ref'], time() + (60 * 60 * 24 * 30), '/');
    }
}
