<?php
session_start();

$db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function runMigrations(PDO $db): void
{
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'user',
        referral_code TEXT NOT NULL UNIQUE,
        referred_by INTEGER NULL,
        referral_earnings REAL NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        price REAL NOT NULL,
        file_url TEXT NOT NULL,
        active INTEGER NOT NULL DEFAULT 1,
        created_at TEXT NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        amount REAL NOT NULL,
        payment_provider TEXT NOT NULL DEFAULT 'manual',
        payment_reference TEXT NOT NULL DEFAULT '',
        created_at TEXT NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id),
        FOREIGN KEY(product_id) REFERENCES products(id)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS referral_commissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        referrer_id INTEGER NOT NULL,
        buyer_id INTEGER NOT NULL,
        order_id INTEGER NOT NULL,
        commission_amount REAL NOT NULL,
        created_at TEXT NOT NULL,
        FOREIGN KEY(referrer_id) REFERENCES users(id),
        FOREIGN KEY(buyer_id) REFERENCES users(id),
        FOREIGN KEY(order_id) REFERENCES orders(id)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS checkout_sessions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        session_token TEXT NOT NULL UNIQUE,
        user_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        gateway TEXT NOT NULL,
        gateway_reference TEXT,
        status TEXT NOT NULL DEFAULT 'pending',
        created_at TEXT NOT NULL,
        completed_at TEXT,
        FOREIGN KEY(user_id) REFERENCES users(id),
        FOREIGN KEY(product_id) REFERENCES products(id)
    )");

    ensureColumn($db, 'orders', 'payment_provider', "TEXT NOT NULL DEFAULT 'manual'");
    ensureColumn($db, 'orders', 'payment_reference', "TEXT NOT NULL DEFAULT ''");

    $adminStmt = $db->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
    $adminStmt->execute([':role' => 'admin']);
    if ((int)$adminStmt->fetchColumn() === 0) {
        $stmt = $db->prepare('INSERT INTO users (name, email, password, role, referral_code, created_at) VALUES (:name, :email, :password, :role, :referral_code, :created_at)');
        $stmt->execute([
            ':name' => 'Administrator',
            ':email' => 'admin@example.com',
            ':password' => password_hash('Admin@123', PASSWORD_DEFAULT),
            ':role' => 'admin',
            ':referral_code' => createReferralCode(),
            ':created_at' => date('c'),
        ]);
    }

    $productStmt = $db->query('SELECT COUNT(*) FROM products');
    if ((int)$productStmt->fetchColumn() === 0) {
        $insertProduct = $db->prepare('INSERT INTO products (title, description, price, file_url, created_at) VALUES (:title, :description, :price, :file_url, :created_at)');
        $seedProducts = [
            ['Starter Canva Templates', 'Editable templates for social media and business branding.', 29.00, '/downloads/canva-templates.zip'],
            ['Premium UI Kit', 'Figma + XD interface kit with 150+ components.', 49.00, '/downloads/premium-ui-kit.zip'],
            ['Marketing Swipe File', '100 high-converting email and ad copy examples.', 19.00, '/downloads/marketing-swipe-file.zip'],
        ];

        foreach ($seedProducts as $product) {
            $insertProduct->execute([
                ':title' => $product[0],
                ':description' => $product[1],
                ':price' => $product[2],
                ':file_url' => $product[3],
                ':created_at' => date('c'),
            ]);
        }
    }
}

function ensureColumn(PDO $db, string $table, string $column, string $definition): void
{
    $stmt = $db->query('PRAGMA table_info(' . $table . ')');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $row) {
        if (($row['name'] ?? '') === $column) {
            return;
        }
    }

    $db->exec('ALTER TABLE ' . $table . ' ADD COLUMN ' . $column . ' ' . $definition);
}

function createReferralCode(): string
{
    return strtoupper(bin2hex(random_bytes(4)));
}

function createSessionToken(): string
{
    return bin2hex(random_bytes(24));
}

function currentUser(PDO $db): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}

function isAdmin(array $user): bool
{
    return $user['role'] === 'admin';
}

function redirectTo(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function appUrl(string $path = ''): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';

    return $scheme . '://' . $host . $path;
}

function requireLogin(PDO $db): array
{
    $user = currentUser($db);
    if (!$user) {
        $_SESSION['error'] = 'Please login first.';
        redirectTo('/login.php');
    }

    return $user;
}

function requireAdmin(PDO $db): array
{
    $user = requireLogin($db);
    if (!isAdmin($user)) {
        $_SESSION['error'] = 'Admin access required.';
        redirectTo('/dashboard.php');
    }

    return $user;
}

function flash(string $key): ?string
{
    if (!isset($_SESSION[$key])) {
        return null;
    }

    $value = $_SESSION[$key];
    unset($_SESSION[$key]);

    return $value;
}

function completeOrderAndCommissions(PDO $db, array $user, array $product, string $gateway, string $reference): void
{
    $orderInsert = $db->prepare('INSERT INTO orders (user_id, product_id, amount, payment_provider, payment_reference, created_at) VALUES (:user_id, :product_id, :amount, :payment_provider, :payment_reference, :created_at)');
    $orderInsert->execute([
        ':user_id' => $user['id'],
        ':product_id' => $product['id'],
        ':amount' => $product['price'],
        ':payment_provider' => $gateway,
        ':payment_reference' => $reference,
        ':created_at' => date('c'),
    ]);

    $orderId = (int)$db->lastInsertId();

    if (!empty($user['referred_by'])) {
        $commission = round((float)$product['price'] * 0.10, 2);

        $db->prepare('UPDATE users SET referral_earnings = referral_earnings + :amount WHERE id = :id')
            ->execute([':amount' => $commission, ':id' => $user['referred_by']]);

        $db->prepare('INSERT INTO referral_commissions (referrer_id, buyer_id, order_id, commission_amount, created_at) VALUES (:referrer_id, :buyer_id, :order_id, :commission_amount, :created_at)')
            ->execute([
                ':referrer_id' => $user['referred_by'],
                ':buyer_id' => $user['id'],
                ':order_id' => $orderId,
                ':commission_amount' => $commission,
                ':created_at' => date('c'),
            ]);
    }
}

runMigrations($db);
