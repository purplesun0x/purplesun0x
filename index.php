<?php
require_once __DIR__ . '/header.php';

$products = db()->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
?>

<h1>Digital Product Store</h1>
<p>Sell and deliver your downloadable products with a built-in referral program.</p>

<div class="grid">
    <?php foreach ($products as $product): ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <strong>$<?php echo number_format((float)$product['price'], 2); ?></strong>
            <div style="margin-top:10px;">
                <?php if (currentUser()): ?>
                    <a class="btn" href="purchase.php?product_id=<?php echo (int)$product['id']; ?>">Buy Now</a>
                <?php else: ?>
                    <a class="btn" href="login.php">Login to Buy</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
