<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_vendor();

$pdo = db();
$userId = (int) current_user()['id'];
$stmt = $pdo->prepare('SELECT COUNT(*) AS total_products FROM products WHERE user_id = ?');
$stmt->execute([$userId]);
$totalProducts = (int) $stmt->fetch()['total_products'];

$earnStmt = $pdo->prepare('SELECT COALESCE(SUM(oi.price),0) AS earnings FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE oi.vendor_id = ? AND o.payment_status="paid"');
$earnStmt->execute([$userId]);
$earnings = (float) $earnStmt->fetch()['earnings'];

include __DIR__ . '/../public/_header.php';
?>
<h1 class="h3">Vendor Dashboard</h1>
<div class="row g-3">
    <div class="col-md-4"><div class="card card-body"><h5>Total products</h5><p><?= $totalProducts ?></p></div></div>
    <div class="col-md-4"><div class="card card-body"><h5>Total earnings</h5><p>$<?= number_format($earnings, 2) ?></p></div></div>
</div>
<div class="mt-3 d-flex gap-2">
    <a href="upload-product.php" class="btn btn-primary">Upload Product</a>
    <a href="my-products.php" class="btn btn-outline-secondary">My Products</a>
    <a href="earnings.php" class="btn btn-outline-success">Earnings</a>
</div>
<?php include __DIR__ . '/../public/_footer.php'; ?>
