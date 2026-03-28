<?php
require __DIR__ . '/init.php';
require __DIR__ . '/layout.php';
require_once __DIR__ . '/payments/GatewayFactory.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = requireLogin($db);
    $productId = (int)($_POST['product_id'] ?? 0);
    $gatewayKey = trim($_POST['gateway'] ?? 'manual');

    $productStmt = $db->prepare('SELECT * FROM products WHERE id = :id AND active = 1 LIMIT 1');
    $productStmt->execute([':id' => $productId]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $_SESSION['error'] = 'Product not found.';
        redirectTo('/store.php');
    }

    $gateway = makeGateway($gatewayKey);
    $sessionToken = createSessionToken();

    $db->prepare('INSERT INTO checkout_sessions (session_token, user_id, product_id, gateway, status, created_at) VALUES (:session_token, :user_id, :product_id, :gateway, :status, :created_at)')
        ->execute([
            ':session_token' => $sessionToken,
            ':user_id' => $user['id'],
            ':product_id' => $product['id'],
            ':gateway' => $gateway->key(),
            ':status' => 'pending',
            ':created_at' => date('c'),
        ]);

    $callbackUrl = appUrl('/payment_callback.php?gateway=' . rawurlencode($gateway->key()) . '&session=' . rawurlencode($sessionToken));

    $initResponse = $gateway->initialize([
        'email' => $user['email'],
        'amount' => (float)$product['price'],
        'session_token' => $sessionToken,
        'callback_url' => $callbackUrl,
    ]);

    if (!$initResponse['ok']) {
        $_SESSION['error'] = 'Payment initialization failed: ' . ($initResponse['error'] ?? 'Unknown error');
        redirectTo('/store.php');
    }

    $reference = $initResponse['reference'] ?? '';
    $db->prepare('UPDATE checkout_sessions SET gateway_reference = :gateway_reference WHERE session_token = :session_token')
        ->execute([':gateway_reference' => $reference, ':session_token' => $sessionToken]);

    if ($gateway->key() === 'manual') {
        completeOrderAndCommissions($db, $user, $product, $gateway->key(), $reference);

        $db->prepare("UPDATE checkout_sessions SET status = 'completed', completed_at = :completed_at WHERE session_token = :session_token")
            ->execute([':completed_at' => date('c'), ':session_token' => $sessionToken]);

        $_SESSION['success'] = 'Purchase completed. Product added to your dashboard.';
        redirectTo('/dashboard.php');
    }

    if (!empty($initResponse['redirect_url'])) {
        redirectTo($initResponse['redirect_url']);
    }

    $_SESSION['error'] = 'No redirect URL returned from gateway.';
    redirectTo('/store.php');
}

$current = currentUser($db);
$products = $db->query('SELECT * FROM products WHERE active = 1 ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
$availableGateways = availableGatewayKeys();

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
                <label>Payment Gateway
                    <select name="gateway" required>
                        <?php foreach ($availableGateways as $key): ?>
                            <option value="<?php echo htmlspecialchars($key); ?>"><?php echo strtoupper(htmlspecialchars($key)); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button class="btn mt-2" type="submit">Buy Now</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<p class="mt-2"><small><strong>Tip:</strong> set <code>PAYSTACK_SECRET_KEY</code> in your environment to enable Paystack checkout.</small></p>
<?php renderFooter(); ?>
