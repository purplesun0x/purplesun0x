<?php
require __DIR__ . '/init.php';
require __DIR__ . '/layout.php';

$admin = requireAdmin($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $fileUrl = trim($_POST['file_url'] ?? '');

    if ($title === '' || $description === '' || $price <= 0 || $fileUrl === '') {
        $_SESSION['error'] = 'All product fields are required and price must be above 0.';
        redirectTo('/admin.php');
    }

    $db->prepare('INSERT INTO products (title, description, price, file_url, created_at) VALUES (:title, :description, :price, :file_url, :created_at)')
        ->execute([
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':file_url' => $fileUrl,
            ':created_at' => date('c'),
        ]);

    $_SESSION['success'] = 'Product added successfully.';
    redirectTo('/admin.php');
}

$users = $db->query('SELECT id, name, email, role, referral_code, referral_earnings, created_at FROM users ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
$products = $db->query('SELECT * FROM products ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
$totalSales = (float)$db->query('SELECT COALESCE(SUM(amount),0) FROM orders')->fetchColumn();
$totalOrders = (int)$db->query('SELECT COUNT(*) FROM orders')->fetchColumn();

renderHeader('Admin Dashboard', $admin);
?>
<h1>Admin Dashboard</h1>
<?php if ($success = flash('success')): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<?php if ($error = flash('error')): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

<div class="grid">
    <div class="card"><h3>Total Sales</h3><p><strong>$<?php echo number_format($totalSales, 2); ?></strong></p></div>
    <div class="card"><h3>Total Orders</h3><p><strong><?php echo $totalOrders; ?></strong></p></div>
    <div class="card"><h3>Total Users</h3><p><strong><?php echo count($users); ?></strong></p></div>
</div>

<h2 class="mt-2">Add New Product</h2>
<form method="post">
    <label>Title <input name="title" required></label><br><br>
    <label>Description <textarea name="description" required></textarea></label><br><br>
    <label>Price <input name="price" type="number" min="1" step="0.01" required></label><br><br>
    <label>File URL <input name="file_url" placeholder="/downloads/file.zip" required></label><br><br>
    <button class="btn" type="submit">Add Product</button>
</form>

<h2 class="mt-2">Products</h2>
<table>
    <thead><tr><th>ID</th><th>Title</th><th>Price</th><th>File</th></tr></thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo (int)$product['id']; ?></td>
            <td><?php echo htmlspecialchars($product['title']); ?></td>
            <td>$<?php echo number_format((float)$product['price'], 2); ?></td>
            <td><code><?php echo htmlspecialchars($product['file_url']); ?></code></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h2 class="mt-2">Users</h2>
<table>
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Referral Code</th><th>Earnings</th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo htmlspecialchars($u['name']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td><?php echo htmlspecialchars($u['role']); ?></td>
            <td><code><?php echo htmlspecialchars($u['referral_code']); ?></code></td>
            <td>$<?php echo number_format((float)$u['referral_earnings'], 2); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php renderFooter(); ?>
