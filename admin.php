<?php
require_once __DIR__ . '/header.php';
requireLogin();
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $downloadUrl = trim($_POST['download_url'] ?? '');

    if ($name !== '' && $description !== '' && $price > 0 && $downloadUrl !== '') {
        $stmt = db()->prepare('INSERT INTO products (name, description, price, download_url) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $description, $price, $downloadUrl]);
        setFlash('success', 'Product added successfully.');
    } else {
        setFlash('error', 'Please provide valid product data.');
    }

    header('Location: admin.php');
    exit;
}

$users = db()->query('SELECT id, name, email, role, referral_code, referral_balance, created_at FROM users ORDER BY id DESC')->fetchAll();
$orders = db()->query('SELECT o.id, o.amount, o.created_at, u.email AS buyer_email, p.name AS product_name
    FROM orders o
    JOIN users u ON u.id = o.user_id
    JOIN products p ON p.id = o.product_id
    ORDER BY o.id DESC LIMIT 20')->fetchAll();

$success = getFlash('success');
$error = getFlash('error');
?>

<h1>Admin Dashboard</h1>
<?php if ($success): ?><div class="flash flash-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<?php if ($error): ?><div class="flash flash-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

<div class="grid">
    <div class="card">
        <h3>Add Product</h3>
        <form method="post">
            <label>Name</label>
            <input name="name" required>
            <label>Description</label>
            <textarea name="description" required></textarea>
            <label>Price (USD)</label>
            <input type="number" name="price" step="0.01" min="0.01" required>
            <label>Download URL</label>
            <input name="download_url" required>
            <button class="btn" type="submit">Create Product</button>
        </form>
    </div>

    <div class="card">
        <h3>Recent Orders</h3>
        <table class="table">
            <thead><tr><th>ID</th><th>Buyer</th><th>Product</th><th>Amount</th></tr></thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo (int)$order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['buyer_email']); ?></td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td>$<?php echo number_format((float)$order['amount'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h3>Users</h3>
    <table class="table">
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Referral Code</th><th>Balance</th></tr></thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?php echo (int)$u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
                <td><?php echo htmlspecialchars($u['referral_code']); ?></td>
                <td>$<?php echo number_format((float)$u['referral_balance'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
