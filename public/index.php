<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
$pdo = db();

if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    set_flash('success', 'Product added to cart.');
    redirect('index.php');
}

$stmt = $pdo->query("SELECT p.*, u.name AS vendor_name FROM products p JOIN users u ON u.id=p.user_id WHERE p.status='approved' ORDER BY p.created_at DESC");
$products = $stmt->fetchAll();

include __DIR__ . '/_header.php';
?>
<h1 class="h3 mb-4">Marketplace Scripts</h1>
<div class="row g-4">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= esc($product['title']) ?></h5>
                    <p class="text-muted small">By <?= esc($product['vendor_name']) ?></p>
                    <p><?= esc(substr($product['description'], 0, 120)) ?>...</p>
                    <p class="fw-bold">$<?= number_format((float) $product['price'], 2) ?></p>
                    <a href="product.php?id=<?= (int) $product['id'] ?>" class="btn btn-outline-primary btn-sm">View</a>
                    <a href="?add=<?= (int) $product['id'] ?>" class="btn btn-primary btn-sm">Add to cart</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
