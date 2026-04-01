<?php
require __DIR__ . '/init.php';
require_once __DIR__ . '/payments/GatewayFactory.php';

$user = requireLogin($db);
$gatewayKey = trim($_GET['gateway'] ?? '');
$sessionToken = trim($_GET['session'] ?? '');
$reference = trim($_GET['reference'] ?? '');

if ($gatewayKey === '' || $sessionToken === '') {
    $_SESSION['error'] = 'Invalid payment callback payload.';
    redirectTo('/store.php');
}

$sessionStmt = $db->prepare('SELECT * FROM checkout_sessions WHERE session_token = :session_token LIMIT 1');
$sessionStmt->execute([':session_token' => $sessionToken]);
$checkoutSession = $sessionStmt->fetch(PDO::FETCH_ASSOC);

if (!$checkoutSession || (int)$checkoutSession['user_id'] !== (int)$user['id']) {
    $_SESSION['error'] = 'Checkout session not found.';
    redirectTo('/store.php');
}

if ($checkoutSession['status'] === 'completed') {
    $_SESSION['success'] = 'Payment already confirmed.';
    redirectTo('/dashboard.php');
}

$productStmt = $db->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
$productStmt->execute([':id' => $checkoutSession['product_id']]);
$product = $productStmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $_SESSION['error'] = 'Product linked to this checkout no longer exists.';
    redirectTo('/store.php');
}

if ($reference === '') {
    $reference = (string)($checkoutSession['gateway_reference'] ?? '');
}

$gateway = makeGateway($gatewayKey);
$verify = $gateway->verify($reference);

if (!$verify['ok']) {
    $_SESSION['error'] = 'Payment verification failed: ' . ($verify['error'] ?? 'Unknown error');
    redirectTo('/store.php');
}

$db->beginTransaction();
try {
    completeOrderAndCommissions($db, $user, $product, $gatewayKey, $verify['reference'] ?? $reference);

    $db->prepare("UPDATE checkout_sessions SET status = 'completed', gateway_reference = :reference, completed_at = :completed_at WHERE session_token = :session_token")
        ->execute([
            ':reference' => $verify['reference'] ?? $reference,
            ':completed_at' => date('c'),
            ':session_token' => $sessionToken,
        ]);

    $db->commit();
} catch (Throwable $exception) {
    $db->rollBack();
    $_SESSION['error'] = 'Could not finalize order: ' . $exception->getMessage();
    redirectTo('/store.php');
}

$_SESSION['success'] = 'Payment successful. Product added to your dashboard.';
redirectTo('/dashboard.php');
