<?php
require_once __DIR__ . '/helpers.php';

function renderHeader(string $title): void
{
    $user = currentUser();
    echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>' . htmlspecialchars($title) . '</title>';
    echo '<style>body{font-family:Arial;max-width:1000px;margin:20px auto;padding:0 16px}nav a{margin-right:10px}.card{border:1px solid #ddd;padding:12px;margin-bottom:12px;border-radius:6px}table{width:100%;border-collapse:collapse}td,th{border:1px solid #ddd;padding:8px}input,button,select{padding:8px;margin:4px 0;width:100%}.row{display:grid;grid-template-columns:1fr 1fr;gap:12px}.btn{display:inline-block;background:#4f46e5;color:#fff;padding:8px 10px;text-decoration:none;border-radius:4px}</style>';
    echo '</head><body>';
    echo '<nav><a href="/public/index.php">Products</a>';

    if ($user) {
        echo '<a href="/public/dashboard.php">Dashboard</a>';
        echo '<a href="/public/cart.php">Cart</a>';
        if ((int) $user['is_admin'] === 1) {
            echo '<a href="/admin/index.php">Admin Panel</a>';
        }
        echo '<a href="/public/logout.php">Logout</a>';
    } else {
        echo '<a href="/public/login.php">Login</a><a href="/public/register.php">Register</a>';
    }

    echo '</nav><hr>';

    $success = flash('success');
    $error = flash('error');

    if ($success) {
        echo '<p style="color:green">' . htmlspecialchars($success) . '</p>';
    }

    if ($error) {
        echo '<p style="color:red">' . htmlspecialchars($error) . '</p>';
    }
}

function renderFooter(): void
{
    echo '</body></html>';
}
