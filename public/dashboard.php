<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();
$user = currentUser();

$ordersStmt = $pdo->prepare('SELECT o.id, o.total_amount, o.status, o.payment_reference, o.created_at FROM orders o WHERE user_id = ? ORDER BY o.id DESC');
$ordersStmt->execute([$user['id']]);
$orders = $ordersStmt->fetchAll();

$referralsStmt = $pdo->prepare('SELECT name, email, created_at FROM users WHERE referred_by = ? ORDER BY id DESC');
$referralsStmt->execute([$user['id']]);
$referrals = $referralsStmt->fetchAll();

renderHeader('Dashboard');
?>
<h1>User Dashboard</h1>
<div class="card">
    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Your referral code:</strong> <?= htmlspecialchars($user['referral_code']) ?></p>
</div>

<h2>Your Orders</h2>
<table>
    <tr><th>#</th><th>Total</th><th>Status</th><th>Payment Ref</th><th>Date</th></tr>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= (int) $order['id'] ?></td>
            <td>$<?= number_format((float) $order['total_amount'], 2) ?></td>
            <td><?= htmlspecialchars($order['status']) ?></td>
            <td><?= htmlspecialchars($order['payment_reference']) ?></td>
            <td><?= htmlspecialchars($order['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>People you referred</h2>
<table>
    <tr><th>Name</th><th>Email</th><th>Joined</th></tr>
    <?php foreach ($referrals as $ref): ?>
        <tr>
            <td><?= htmlspecialchars($ref['name']) ?></td>
            <td><?= htmlspecialchars($ref['email']) ?></td>
            <td><?= htmlspecialchars($ref['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php renderFooter(); ?>
