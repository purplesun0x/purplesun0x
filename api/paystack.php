<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_auth();

$pdo = db();
$config = config();
$action = $_GET['action'] ?? '';
$user = current_user();
$amount = (float) ($_SESSION['checkout_total'] ?? 0);

if ($action === 'initialize') {
    if ($amount <= 0) {
        set_flash('error', 'Invalid checkout amount.');
        redirect('../public/checkout.php');
    }

    $reference = generate_reference('PST');
    $_SESSION['payment_reference'] = $reference;
    $_SESSION['payment_provider'] = 'paystack';

    // Payment initialization stub for local testing.
    // Replace with actual cURL request to Paystack initialize_url in production.
    $stmt = $pdo->prepare('INSERT INTO transactions (user_id, reference, amount, provider, status, created_at) VALUES (?, ?, ?, "paystack", "pending", NOW())');
    $stmt->execute([(int) $user['id'], $reference, $amount]);

    redirect('paystack.php?action=callback&reference=' . urlencode($reference));
}

if ($action === 'callback') {
    $reference = $_GET['reference'] ?? '';
    if (!$reference) {
        exit('Invalid reference.');
    }

    $pdo->beginTransaction();
    try {
        $update = $pdo->prepare('UPDATE transactions SET status="success" WHERE reference=?');
        $update->execute([$reference]);

        $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, payment_method, payment_status, created_at) VALUES (?, ?, "paystack", "paid", NOW())');
        $orderStmt->execute([(int) $user['id'], $amount]);
        $orderId = (int) $pdo->lastInsertId();

        foreach (cart_items() as $productId => $qty) {
            $productStmt = $pdo->prepare('SELECT id,user_id,price FROM products WHERE id=? AND status="approved"');
            $productStmt->execute([(int) $productId]);
            $product = $productStmt->fetch();
            if (!$product) {
                continue;
            }

            $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, vendor_id, price) VALUES (?, ?, ?, ?)');
            for ($i = 0; $i < (int) $qty; $i++) {
                $itemStmt->execute([$orderId, (int) $product['id'], (int) $product['user_id'], (float) $product['price']]);
            }
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        exit('Payment verification failed: ' . $e->getMessage());
    }

    unset($_SESSION['cart'], $_SESSION['checkout_total'], $_SESSION['payment_reference'], $_SESSION['payment_provider']);
    set_flash('success', 'Paystack payment verified successfully.');
    redirect('../public/success.php');
}
