<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_auth();
$pdo = db();

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][(int) $_GET['remove']]);
    redirect('checkout.php');
}

$ids = array_keys(cart_items());
$products = [];
if ($ids) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders) AND status='approved'");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
}
$total = cart_total($pdo);

if (is_post() && $total > 0) {
    $method = $_POST['payment_method'] ?? 'paystack';
    $_SESSION['checkout_total'] = $total;
    if ($method === 'flutterwave') {
        redirect('../api/flutterwave.php?action=initialize');
    }
    redirect('../api/paystack.php?action=initialize');
}

include __DIR__ . '/_header.php';
?>
<h1 class="h3">Checkout</h1>
<?php if (!$products): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table class="table table-striped">
        <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($products as $product): $qty = (int) $_SESSION['cart'][$product['id']]; ?>
            <tr>
                <td><?= esc($product['title']) ?></td>
                <td><?= $qty ?></td>
                <td>$<?= number_format($qty * (float) $product['price'], 2) ?></td>
                <td><a href="?remove=<?= (int) $product['id'] ?>" class="btn btn-sm btn-outline-danger">Remove</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <h4>Total: $<?= number_format($total, 2) ?></h4>
    <form method="post" class="mt-3">
        <label class="form-label">Payment Method</label>
        <select name="payment_method" class="form-select mb-3">
            <option value="paystack">Paystack</option>
            <option value="flutterwave">Flutterwave</option>
        </select>
        <button class="btn btn-success">Proceed to Payment</button>
    </form>
<?php endif; ?>
<?php include __DIR__ . '/_footer.php'; ?>
