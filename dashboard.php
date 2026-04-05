<?php
$userStats = [
    'totalOrders' => 28,
    'wishlistItems' => 14,
    'referralEarnings' => '$1,842.50',
    'pendingDeliveries' => 2,
];

$recentActivity = [
    'Order #8452 shipped - ETA tomorrow',
    'Wishlist item "Aurora Smartwatch" dropped by 15%',
    'Referral bonus of $95 approved',
];

$referralLink = 'https://novamarket.com/r/AJX92K7';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | NovaMarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="app-layout">
<div class="dashboard-shell">
    <aside class="sidebar surface">
        <h2>NovaMarket</h2>
        <a class="active" href="#">Dashboard</a>
        <a href="#">Orders</a>
        <a href="#">Cart</a>
        <a href="#">Wishlist</a>
        <a href="#">Profile Settings</a>
        <a href="#">Addresses</a>
        <a href="#">Payment Methods</a>
        <a href="#">Notifications</a>
        <a href="#referral">Referral Program</a>
        <a href="admin.php">Admin View</a>
    </aside>

    <div class="content-area">
        <header class="content-topbar surface">
            <input type="search" placeholder="Search orders, products, activity...">
            <div class="topbar-actions">
                <button class="btn btn-ghost">Dark Mode</button>
                <button class="avatar">AD</button>
            </div>
        </header>

        <main class="content-grid">
            <section class="metrics-grid">
                <article class="metric-card surface"><span>Total Orders</span><strong><?= $userStats['totalOrders'] ?></strong></article>
                <article class="metric-card surface"><span>Wishlist Items</span><strong><?= $userStats['wishlistItems'] ?></strong></article>
                <article class="metric-card surface"><span>Referral Earnings</span><strong><?= $userStats['referralEarnings'] ?></strong></article>
                <article class="metric-card surface"><span>Pending Deliveries</span><strong><?= $userStats['pendingDeliveries'] ?></strong></article>
            </section>

            <section class="surface panel">
                <div class="panel-head">
                    <h3>Recent Activity</h3>
                    <button class="btn btn-small btn-ghost">View All</button>
                </div>
                <ul class="clean-list">
                    <?php foreach ($recentActivity as $activity): ?>
                        <li><?= htmlspecialchars($activity) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="surface panel" id="referral">
                <div class="panel-head">
                    <h3>Referral Program</h3>
                    <span class="pill">Live Tracking</span>
                </div>
                <div class="referral-box">
                    <label for="ref-link">Unique Referral Link</label>
                    <div class="ref-input-row">
                        <input id="ref-link" readonly value="<?= $referralLink ?>">
                        <button class="btn btn-secondary" data-copy="#ref-link">Copy</button>
                    </div>
                </div>
                <div class="metrics-grid two-col">
                    <article class="metric-card subtle"><span>Total Referrals</span><strong>64</strong></article>
                    <article class="metric-card subtle"><span>Approved Earnings</span><strong>$1,842.50</strong></article>
                    <article class="metric-card subtle"><span>Pending Payout</span><strong>$380.00</strong></article>
                    <article class="metric-card subtle"><span>Conversion Rate</span><strong>23.4%</strong></article>
                </div>
                <button class="btn btn-primary">Request Withdrawal</button>
            </section>
        </main>
    </div>
</div>
<script src="assets/app.js"></script>
</body>
</html>
