<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_vendor();

$pdo = db();
$userId = (int) current_user()['id'];
$stmt = $pdo->prepare('SELECT p.title, oi.price, o.created_at
    FROM order_items oi
    JOIN orders o ON o.id=oi.order_id
    JOIN products p ON p.id=oi.product_id
    WHERE oi.vendor_id=? AND o.payment_status="paid"
    ORDER BY o.created_at DESC');
$stmt->execute([$userId]);
$rows = $stmt->fetchAll();
$total = array_sum(array_map(fn($r) => (float) $r['price'], $rows));

include __DIR__ . '/../public/_header.php';
?>
<h1 class="h3">Earnings</h1>
<p class="fw-bold">Total: $<?= number_format($total, 2) ?></p>
<table class="table table-striped">
    <thead><tr><th>Product</th><th>Amount</th><th>Date</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $row): ?>
        <tr><td><?= esc($row['title']) ?></td><td>$<?= number_format((float)$row['price'], 2) ?></td><td><?= esc($row['created_at']) ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/../public/_footer.php'; ?>
