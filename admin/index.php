<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);

    if (!$name || $price <= 0) {
        flash('error', 'Provide a valid product name and price.');
        redirect('/admin/index.php');
    }

    $stmt = $pdo->prepare('INSERT INTO products (name, description, price) VALUES (?, ?, ?)');
    $stmt->execute([$name, $description, $price]);
    flash('success', 'Product created.');
    redirect('/admin/index.php');
}

$usersCount = (int) $pdo->query('SELECT COUNT(*) AS total FROM users')->fetch()['total'];
$ordersCount = (int) $pdo->query('SELECT COUNT(*) AS total FROM orders')->fetch()['total'];
$revenue = (float) $pdo->query('SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders')->fetch()['total'];
$products = $pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();

renderHeader('Admin Panel');
?>
<h1>Admin Panel</h1>
<div class="row">
    <div class="card"><strong>Total Users:</strong> <?= $usersCount ?></div>
    <div class="card"><strong>Total Orders:</strong> <?= $ordersCount ?></div>
</div>
<div class="card"><strong>Total Revenue:</strong> $<?= number_format($revenue, 2) ?></div>

<h2>Add Product</h2>
<form method="post" class="card">
    <label>Product name</label>
    <input name="name" required>
    <label>Description</label>
    <input name="description">
    <label>Price (USD)</label>
    <input type="number" step="0.01" min="0.01" name="price" required>
    <button type="submit">Save product</button>
</form>

<h2>Products</h2>
<table>
    <tr><th>ID</th><th>Name</th><th>Price</th></tr>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= (int) $product['id'] ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td>$<?= number_format((float) $product['price'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php renderFooter(); ?>
