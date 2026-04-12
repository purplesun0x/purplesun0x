<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
$pdo = db();
$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, u.name AS vendor_name FROM products p JOIN users u ON u.id=p.user_id WHERE p.id=? AND p.status='approved'");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    exit('Product not found');
}

if (isset($_GET['add'])) {
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    set_flash('success', 'Added to cart.');
    redirect('product.php?id=' . $id);
}

include __DIR__ . '/_header.php';
?>
<div class="card">
    <div class="card-body">
        <h1 class="h3"><?= esc($product['title']) ?></h1>
        <p class="text-muted">Vendor: <?= esc($product['vendor_name']) ?></p>
        <p><?= nl2br(esc($product['description'])) ?></p>
        <p class="fw-bold fs-5">$<?= number_format((float) $product['price'], 2) ?></p>
        <a class="btn btn-primary" href="product.php?id=<?= $id ?>&add=1">Add to cart</a>
    </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
