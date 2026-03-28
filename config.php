<?php
session_start();

define('DB_PATH', __DIR__ . '/database.sqlite');

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        initializeDatabase($pdo);
    }

    return $pdo;
}

function initializeDatabase(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT "user",
        referral_code TEXT NOT NULL UNIQUE,
        referred_by INTEGER NULL,
        referral_balance REAL NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (referred_by) REFERENCES users (id)
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT NOT NULL,
        price REAL NOT NULL,
        download_url TEXT NOT NULL,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        amount REAL NOT NULL,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (product_id) REFERENCES products (id)
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS referral_earnings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        referrer_id INTEGER NOT NULL,
        referred_user_id INTEGER NOT NULL,
        order_id INTEGER NOT NULL,
        commission_amount REAL NOT NULL,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (referrer_id) REFERENCES users (id),
        FOREIGN KEY (referred_user_id) REFERENCES users (id),
        FOREIGN KEY (order_id) REFERENCES orders (id)
    )');

    $adminExists = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
    if ((int) $adminExists === 0) {
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, referral_code) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            'Site Admin',
            'admin@example.com',
            password_hash('admin123', PASSWORD_DEFAULT),
            'admin',
            'ADMIN' . random_int(1000, 9999),
        ]);
    }

    $productCount = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    if ($productCount === 0) {
        $stmt = $pdo->prepare('INSERT INTO products (name, description, price, download_url) VALUES (?, ?, ?, ?)');
        $seedProducts = [
            ['Starter Branding Pack', 'Logo templates, brand colors, and typography guide.', 19.00, 'https://example.com/downloads/branding-pack.zip'],
            ['Creator Notion Bundle', 'Planner templates for content creators and freelancers.', 29.00, 'https://example.com/downloads/notion-bundle.zip'],
            ['Email Marketing Swipe File', '50 high-converting email templates.', 39.00, 'https://example.com/downloads/email-swipe-file.zip'],
        ];

        foreach ($seedProducts as $product) {
            $stmt->execute($product);
        }
    }
}
