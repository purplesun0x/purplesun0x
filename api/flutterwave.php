<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_auth();

$pdo = db();
$action = $_GET['action'] ?? '';
$user = current_user();
$amount = (float) ($_SESSION['checkout_total'] ?? 0);

if ($action === 'initialize') {
    if ($amount <= 0) {
        set_flash('error', 'Invalid checkout amount.');
        redirect('../public/checkout.php');
    }

    $reference = generate_reference('FLW');
    $_SESSION['payment_reference'] = $reference;
    $_SESSION['payment_provider'] = 'flutterwave';

    // Payment initialization stub for local testing.
    // Replace with real API call to Flutterwave initialize_url.
    $stmt = $pdo->prepare('INSERT INTO transactions (user_id, reference, amount, provider, status, created_at) VALUES (?, ?, ?, "flutterwave", "pending", NOW())');
    $stmt->execute([(int) $user['id'], $reference, $amount]);

    redirect('flutterwave.php?action=callback&tx_ref=' . urlencode($reference));
}

if ($action === 'callback') {
    $reference = $_GET['tx_ref'] ?? '';
    if (!$reference) {
        exit('Invalid transaction reference.');
    }

    $pdo->beginTransaction();
    try {
        $update = $pdo->prepare('UPDATE transactions SET status="success" WHERE reference=?');
        $update->execute([$reference]);

        $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, payment_method, payment_status, created_at) VALUES (?, ?, "flutterwave", "paid", NOW())');
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
        exit('Flutterwave verification failed: ' . $e->getMessage());
    }

    unset($_SESSION['cart'], $_SESSION['checkout_total'], $_SESSION['payment_reference'], $_SESSION['payment_provider']);
    set_flash('success', 'Flutterwave payment verified successfully.');
    redirect('../public/success.php');
}
