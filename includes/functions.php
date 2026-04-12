<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    $config = require __DIR__ . '/../config/config.php';
    session_name($config['app']['session_name']);
    session_start();
}

function config(string $key = null)
{
    static $settings = null;
    if ($settings === null) {
        $settings = require __DIR__ . '/../config/config.php';
    }

    if ($key === null) {
        return $settings;
    }

    $segments = explode('.', $key);
    $value = $settings;
    foreach ($segments as $segment) {
        if (!isset($value[$segment])) {
            return null;
        }
        $value = $value[$segment];
    }

    return $value;
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function url(string $path = ''): string
{
    $baseUrl = rtrim((string) config('app.base_url'), '/');
    return $baseUrl . '/' . ltrim($path, '/');
}

function esc(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function set_flash(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash(string $key): ?string
{
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function cart_items(): array
{
    return $_SESSION['cart'] ?? [];
}

function cart_total(PDO $pdo): float
{
    $ids = array_keys(cart_items());
    if (!$ids) {
        return 0;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders) AND status='approved'");
    $stmt->execute($ids);
    $total = 0;
    while ($row = $stmt->fetch()) {
        $quantity = (int) ($_SESSION['cart'][$row['id']] ?? 0);
        $total += $quantity * (float) $row['price'];
    }

    return $total;
}

function validate_upload(array $file): array
{
    $allowedExtensions = config('security.allowed_extensions') ?? [];
    $maxSize = (int) config('security.max_upload_size');

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return [false, 'File upload failed.'];
    }

    $extension = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        return [false, 'Unsupported file type. Allowed: zip, rar, pdf'];
    }

    if ((int) $file['size'] > $maxSize) {
        return [false, 'File exceeds maximum upload size.'];
    }

    return [true, ''];
}

function generate_reference(string $prefix = 'TXN'): string
{
    return strtoupper($prefix) . '_' . bin2hex(random_bytes(6)) . '_' . time();
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}
