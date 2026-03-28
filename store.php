<?php
require __DIR__ . '/init.php';
require __DIR__ . '/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = requireLogin($db);
    $productId = (int)($_POST['product_id'] ?? 0);

    $productStmt = $db->prepare('SELECT * FROM products WHERE id = :id AND active = 1 LIMIT 1');
    $productStmt->execute([':id' => $productId]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $_SESSION['error'] = 'Product not found.';
        redirectTo('/store.php');
    }

    $orderInsert = $db->prepare('INSERT INTO orders (user_id, product_id, amount, created_at) VALUES (:user_id, :product_id, :amount, :created_at)');
    $orderInsert->execute([
        ':user_id' => $user['id'],
        ':product_id' => $product['id'],
        ':amount' => $product['price'],
        ':created_at' => date('c'),
    ]);

    $orderId = (int)$db->lastInsertId();

    if (!empty($user['referred_by'])) {
        $commission = round((float)$product['price'] * 0.10, 2);

        $db->prepare('UPDATE users SET referral_earnings = referral_earnings + :amount WHERE id = :id')
            ->execute([':amount' => $commission, ':id' => $user['referred_by']]);

        $db->prepare('INSERT INTO referral_commissions (referrer_id, buyer_id, order_id, commission_amount, created_at) VALUES (:referrer_id, :buyer_id, :order_id, :commission_amount, :created_at)')
            ->execute([
                ':referrer_id' => $user['referred_by'],
                ':buyer_id' => $user['id'],
                ':order_id' => $orderId,
                ':commission_amount' => $commission,
                ':created_at' => date('c'),
            ]);
    }

    $_SESSION['success'] = 'Purchase completed. Product added to your dashboard.';
    redirectTo('/dashboard.php');
}

$current = currentUser($db);
$products = $db->query('SELECT * FROM products WHERE active = 1 ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

renderHeader('Store', $current);
?>
<h1>Store</h1>
<?php if ($success = flash('success')): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<?php if ($error = flash('error')): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<div class="grid">
    <?php foreach ($products as $product): ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($product['title']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p><strong>$<?php echo number_format((float)$product['price'], 2); ?></strong></p>
            <form method="post">
                <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                <button class="btn" type="submit">Buy Now</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<?php renderFooter(); ?>
