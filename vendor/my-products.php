<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_vendor();
$pdo = db();
$stmt = $pdo->prepare('SELECT * FROM products WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([(int) current_user()['id']]);
$products = $stmt->fetchAll();
include __DIR__ . '/../public/_header.php';
?>
<h1 class="h3">My Products</h1>
<table class="table table-striped">
    <thead><tr><th>Title</th><th>Price</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= esc($product['title']) ?></td>
            <td>$<?= number_format((float) $product['price'], 2) ?></td>
            <td><span class="badge bg-<?= $product['status'] === 'approved' ? 'success' : 'warning' ?>"><?= esc($product['status']) ?></span></td>
            <td><?= esc($product['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/../public/_footer.php'; ?>
