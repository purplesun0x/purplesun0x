<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear'])) {
        $_SESSION['cart'] = [];
        flash('success', 'Cart cleared.');
        redirect('/public/cart.php');
    }

    if (isset($_POST['checkout'])) {
        $cart = $_SESSION['cart'] ?? [];
        if (!$cart) {
            flash('error', 'Cart is empty.');
            redirect('/public/cart.php');
        }

        $productIds = array_keys($cart);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
        $stmt->execute($productIds);
        $prices = [];
        foreach ($stmt->fetchAll() as $row) {
            $prices[(int) $row['id']] = (float) $row['price'];
        }

        $total = 0.0;
        foreach ($cart as $productId => $quantity) {
            if (isset($prices[(int) $productId])) {
                $total += $prices[(int) $productId] * (int) $quantity;
            }
        }

        $pdo->beginTransaction();
        $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, status, payment_reference) VALUES (?, ?, ?, ?)');
        $paymentRef = 'PAY-' . date('YmdHis') . '-' . random_int(1000, 9999);
        $orderStmt->execute([currentUser()['id'], $total, 'paid', $paymentRef]);
        $orderId = (int) $pdo->lastInsertId();

        $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)');
        foreach ($cart as $productId => $quantity) {
            if (isset($prices[(int) $productId])) {
                $itemStmt->execute([$orderId, $productId, $quantity, $prices[(int) $productId]]);
            }
        }
        $pdo->commit();

        $_SESSION['cart'] = [];
        flash('success', 'Payment successful. Order placed with reference ' . $paymentRef);
        redirect('/public/dashboard.php');
    }
}

$cart = $_SESSION['cart'] ?? [];
$products = [];
$total = 0.0;
if ($cart) {
    $productIds = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($productIds);
    $products = $stmt->fetchAll();
}

renderHeader('Cart');
?>
<h1>Your Cart</h1>
<?php if (!$cart): ?>
    <p>No items in cart.</p>
<?php else: ?>
    <table>
        <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
        <?php foreach ($products as $product):
            $qty = (int) $cart[$product['id']];
            $subtotal = $qty * (float) $product['price'];
            $total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= $qty ?></td>
                <td>$<?= number_format((float) $product['price'], 2) ?></td>
                <td>$<?= number_format($subtotal, 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h3>Total: $<?= number_format($total, 2) ?></h3>
    <form method="post" class="row">
        <button name="checkout" value="1" type="submit">Pay & Place Order</button>
        <button name="clear" value="1" type="submit">Clear Cart</button>
    </form>
<?php endif; ?>
<?php renderFooter(); ?>
