<?php
require __DIR__ . '/init.php';
require __DIR__ . '/layout.php';

$user = requireLogin($db);
if (isAdmin($user)) {
    redirectTo('/admin.php');
}

$ordersStmt = $db->prepare('SELECT o.*, p.title, p.file_url FROM orders o JOIN products p ON p.id = o.product_id WHERE o.user_id = :user_id ORDER BY o.id DESC');
$ordersStmt->execute([':user_id' => $user['id']]);
$orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

$commissionsStmt = $db->prepare('SELECT rc.*, u.name as buyer_name FROM referral_commissions rc JOIN users u ON u.id = rc.buyer_id WHERE rc.referrer_id = :id ORDER BY rc.id DESC');
$commissionsStmt->execute([':id' => $user['id']]);
$commissions = $commissionsStmt->fetchAll(PDO::FETCH_ASSOC);

renderHeader('User Dashboard', $user);
?>
<h1>User Dashboard</h1>
<?php if ($success = flash('success')): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<?php if ($error = flash('error')): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

<div class="grid">
    <div class="card">
        <h3>Your Referral Link</h3>
        <p>Share this referral code in signup page:</p>
        <code><?php echo htmlspecialchars($user['referral_code']); ?></code>
        <p class="mt-2">Total referral earnings: <strong>$<?php echo number_format((float)$user['referral_earnings'], 2); ?></strong></p>
    </div>
    <div class="card">
        <h3>Quick Actions</h3>
        <a class="btn" href="/store.php">Go to Store</a>
    </div>
</div>

<h2 class="mt-2">Purchased Products</h2>
<table>
    <thead>
    <tr><th>Product</th><th>Amount</th><th>Gateway</th><th>Date</th><th>Download</th></tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo htmlspecialchars($order['title']); ?></td>
            <td>$<?php echo number_format((float)$order['amount'], 2); ?></td>
            <td><?php echo strtoupper(htmlspecialchars($order['payment_provider'] ?? 'manual')); ?></td>
            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($order['created_at']))); ?></td>
            <td><a class="btn btn-secondary" href="<?php echo htmlspecialchars($order['file_url']); ?>">Download</a></td>
        </tr>
    <?php endforeach; ?>
    <?php if (count($orders) === 0): ?>
        <tr><td colspan="5">No purchases yet.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<h2 class="mt-2">Referral Commissions</h2>
<table>
    <thead>
    <tr><th>Buyer</th><th>Commission</th><th>Date</th></tr>
    </thead>
    <tbody>
    <?php foreach ($commissions as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['buyer_name']); ?></td>
            <td>$<?php echo number_format((float)$item['commission_amount'], 2); ?></td>
            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($item['created_at']))); ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if (count($commissions) === 0): ?>
        <tr><td colspan="3">No commissions yet.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
<?php renderFooter(); ?>
