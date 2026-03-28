<?php
require_once __DIR__ . '/functions.php';
requireLogin();

$user = currentUser();
$productId = (int) ($_GET['product_id'] ?? 0);

$stmt = db()->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    setFlash('error', 'Product not found.');
    header('Location: index.php');
    exit;
}

$pdo = db();
$pdo->beginTransaction();

try {
    $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, product_id, amount) VALUES (?, ?, ?)');
    $orderStmt->execute([$user['id'], $productId, $product['price']]);
    $orderId = (int) $pdo->lastInsertId();

    if (!empty($user['referred_by'])) {
        $commission = round(((float)$product['price']) * 0.10, 2);

        $earningStmt = $pdo->prepare('INSERT INTO referral_earnings (referrer_id, referred_user_id, order_id, commission_amount) VALUES (?, ?, ?, ?)');
        $earningStmt->execute([(int)$user['referred_by'], (int)$user['id'], $orderId, $commission]);

        $walletStmt = $pdo->prepare('UPDATE users SET referral_balance = referral_balance + ? WHERE id = ?');
        $walletStmt->execute([$commission, (int)$user['referred_by']]);
    }

    $pdo->commit();
    setFlash('success', 'Purchase successful! Product unlocked in your dashboard.');
} catch (Throwable $e) {
    $pdo->rollBack();
    setFlash('error', 'Purchase failed. Please try again.');
}

header('Location: dashboard.php');
exit;
