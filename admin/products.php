<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_admin();
$pdo = db();
if (isset($_GET['approve'])) {
    $stmt = $pdo->prepare('UPDATE products SET status="approved" WHERE id=?');
    $stmt->execute([(int) $_GET['approve']]);
    set_flash('success', 'Product approved.');
    redirect('products.php');
}
if (isset($_GET['reject'])) {
    $stmt = $pdo->prepare('UPDATE products SET status="pending" WHERE id=?');
    $stmt->execute([(int) $_GET['reject']]);
    set_flash('success', 'Product moved to pending.');
    redirect('products.php');
}
$stmt = $pdo->query('SELECT p.*,u.name AS vendor_name FROM products p JOIN users u ON u.id=p.user_id ORDER BY p.created_at DESC');
$products = $stmt->fetchAll();
include __DIR__ . '/../public/_header.php';
?>
<h1 class="h3">Products</h1>
<table class="table table-striped">
<thead><tr><th>Title</th><th>Vendor</th><th>Price</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php foreach ($products as $product): ?>
<tr>
<td><?= esc($product['title']) ?></td>
<td><?= esc($product['vendor_name']) ?></td>
<td>$<?= number_format((float) $product['price'],2) ?></td>
<td><?= esc($product['status']) ?></td>
<td>
    <a class="btn btn-sm btn-success" href="?approve=<?= (int) $product['id'] ?>">Approve</a>
    <a class="btn btn-sm btn-secondary" href="?reject=<?= (int) $product['id'] ?>">Pending</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php include __DIR__ . '/../public/_footer.php'; ?>
