<?php
require_once __DIR__ . '/header.php';
requireLogin();

$user = currentUser();
$ordersStmt = db()->prepare('SELECT o.*, p.name AS product_name, p.download_url
    FROM orders o
    JOIN products p ON p.id = o.product_id
    WHERE o.user_id = ?
    ORDER BY o.id DESC');
$ordersStmt->execute([$user['id']]);
$orders = $ordersStmt->fetchAll();

$referralStats = db()->prepare('SELECT COUNT(*) AS total_sales, COALESCE(SUM(commission_amount), 0) AS total_commission
    FROM referral_earnings
    WHERE referrer_id = ?');
$referralStats->execute([$user['id']]);
$stats = $referralStats->fetch();

$referralLink = sprintf(
    '%s://%s%s?ref=%s',
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http',
    $_SERVER['HTTP_HOST'] ?? 'localhost:8000',
    rtrim(dirname($_SERVER['PHP_SELF'] ?? '/index.php'), '/\\') . '/index.php',
    urlencode($user['referral_code'])
);
?>

<h1>User Dashboard</h1>

<div class="grid">
    <div class="card">
        <h3>Referral Program</h3>
        <p>Your referral code: <strong><?php echo htmlspecialchars($user['referral_code']); ?></strong></p>
        <p>Share link:</p>
        <input value="<?php echo htmlspecialchars($referralLink); ?>" readonly>
        <p>Total referred sales: <strong><?php echo (int)$stats['total_sales']; ?></strong></p>
        <p>Total commissions: <strong>$<?php echo number_format((float)$stats['total_commission'], 2); ?></strong></p>
        <p>Wallet balance: <strong>$<?php echo number_format((float)$user['referral_balance'], 2); ?></strong></p>
    </div>
    <div class="card">
        <h3>Quick Actions</h3>
        <a class="btn" href="index.php">Browse Store</a>
        <?php if (isAdmin()): ?>
            <a class="btn btn-secondary" href="admin.php">Admin Dashboard</a>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <h3>Your Purchases</h3>
    <?php if (!$orders): ?>
        <p>You have not bought any products yet.</p>
    <?php else: ?>
        <table class="table">
            <thead>
            <tr>
                <th>Product</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Download</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td>$<?php echo number_format((float)$order['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($order['download_url']); ?>" target="_blank" rel="noopener noreferrer">Download</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
