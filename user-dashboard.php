<?php
$overviewCards = [
    ['label' => 'Total Orders', 'value' => '142'],
    ['label' => 'Open Cart Items', 'value' => '5'],
    ['label' => 'Wishlist Items', 'value' => '23'],
    ['label' => 'Referral Earnings', 'value' => '$1,240.00'],
];

$activities = [
    'Order #LX-9281 delivered',
    'Referral payout approved: $120',
    'Saved new payment method',
    'Added Nova Earbuds Pro to wishlist',
];

$referralLink = 'https://luxora.example/ref/AVA-8291';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard | Luxora</title>
    <link rel="stylesheet" href="assets/styles.css" />
</head>
<body class="dashboard-body">
<div class="dashboard-layout">
    <aside class="sidebar">
        <h2>Luxora</h2>
        <a class="active" href="#">Dashboard Overview</a>
        <a href="#">Orders</a>
        <a href="#">Cart</a>
        <a href="#">Wishlist</a>
        <a href="#">Profile Settings</a>
        <a href="#">Addresses</a>
        <a href="#">Payment Methods</a>
        <a href="#">Notifications</a>
        <a href="#">Referral Program</a>
    </aside>

    <main class="dashboard-main">
        <header class="dash-top">
            <h1>User Dashboard</h1>
            <div class="top-actions">
                <button class="btn ghost">Export Activity</button>
                <button class="btn primary">Start Shopping</button>
            </div>
        </header>

        <section class="stats-grid">
            <?php foreach ($overviewCards as $card): ?>
                <article class="card metric">
                    <p><?= htmlspecialchars($card['label']) ?></p>
                    <h3><?= htmlspecialchars($card['value']) ?></h3>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="section">
            <div class="section-head"><h2>Recent Activity</h2></div>
            <ul class="card activity-list">
                <?php foreach ($activities as $activity): ?>
                    <li><?= htmlspecialchars($activity) ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="section">
            <div class="section-head"><h2>Referral Program</h2></div>
            <div class="card referral-box">
                <label for="ref-link">Unique Referral Link</label>
                <div class="inline-actions">
                    <input id="ref-link" type="text" value="<?= htmlspecialchars($referralLink) ?>" readonly />
                    <button class="btn small" data-copy-target="#ref-link">Copy</button>
                </div>
                <div class="stats-inline">
                    <p><strong>Total Referrals:</strong> 37</p>
                    <p><strong>Pending Earnings:</strong> $210.00</p>
                    <p><strong>Withdrawn:</strong> $1,030.00</p>
                </div>
                <button class="btn primary">Request Withdrawal</button>
            </div>
        </section>
    </main>
</div>
<script src="assets/app.js"></script>
</body>
</html>
