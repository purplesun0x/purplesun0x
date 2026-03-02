<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAuth();
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

    $stmt = $pdo->prepare('SELECT id FROM products WHERE id = ?');
    $stmt->execute([$productId]);
    if ($stmt->fetch()) {
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $quantity;
        flash('success', 'Product added to cart.');
    }
    redirect('/public/index.php');
}

$products = $pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
renderHeader('Product Store');
?>
<h1>Products</h1>
<?php foreach ($products as $product): ?>
    <div class="card">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <strong>$<?= number_format((float) $product['price'], 2) ?></strong>
        <?php if (currentUser()): ?>
            <form method="post">
                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                <label>Quantity</label>
                <input type="number" name="quantity" min="1" value="1">
                <button type="submit">Add to Cart</button>
            </form>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
<?php renderFooter(); ?>
