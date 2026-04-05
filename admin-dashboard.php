<?php
$analytics = [
    ['label' => 'Revenue (30d)', 'value' => '$184,200'],
    ['label' => 'Orders (30d)', 'value' => '2,941'],
    ['label' => 'Active Customers', 'value' => '8,372'],
    ['label' => 'Referral Payout Queue', 'value' => '$6,870'],
];

$modules = [
    'Product Management',
    'Order Management',
    'Customer Management',
    'Categories',
    'Coupons',
    'Referral System',
    'Reports',
    'Settings',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Console | Luxora</title>
    <link rel="stylesheet" href="assets/styles.css" />
</head>
<body class="dashboard-body admin-theme">
<div class="dashboard-layout">
    <aside class="sidebar">
        <h2>Admin Console</h2>
        <a class="active" href="#">Dashboard Analytics</a>
        <?php foreach ($modules as $module): ?>
            <a href="#"><?= htmlspecialchars($module) ?></a>
        <?php endforeach; ?>
    </aside>

    <main class="dashboard-main">
        <header class="dash-top">
            <h1>Administrator Dashboard</h1>
            <div class="top-actions">
                <span class="pill">Role: Super Admin</span>
                <button class="btn ghost">Add Administrator</button>
            </div>
        </header>

        <section class="stats-grid">
            <?php foreach ($analytics as $item): ?>
                <article class="card metric">
                    <p><?= htmlspecialchars($item['label']) ?></p>
                    <h3><?= htmlspecialchars($item['value']) ?></h3>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="section grid-2">
            <article class="card">
                <h2>Product & Inventory Controls</h2>
                <ul class="activity-list plain">
                    <li>Add / edit / delete products with media gallery</li>
                    <li>Update inventory, SKU thresholds, and low-stock alerts</li>
                    <li>Assign categories, variants, and smart collections</li>
                    <li>Activate bulk pricing and coupon logic</li>
                </ul>
            </article>

            <article class="card">
                <h2>Order & Customer Operations</h2>
                <ul class="activity-list plain">
                    <li>Update order statuses and trigger notifications</li>
                    <li>Review user activity trails for support and compliance</li>
                    <li>Process refunds and dispute workflows</li>
                    <li>Manage role-based permissions for admin team</li>
                </ul>
            </article>
        </section>

        <section class="section">
            <article class="card">
                <h2>Referral System Administration</h2>
                <div class="stats-inline">
                    <p><strong>Commission Rule:</strong> Tiered 8% / 12% / 15%</p>
                    <p><strong>Fraud Flags (7d):</strong> 14 suspicious events</p>
                    <p><strong>Payout Approval Queue:</strong> 56 requests</p>
                </div>
                <div class="inline-actions">
                    <button class="btn">Configure Commissions</button>
                    <button class="btn ghost">Review Fraud Signals</button>
                    <button class="btn primary">Approve Payout Batch</button>
                </div>
            </article>
        </section>
    </main>
</div>
<script src="assets/app.js"></script>
</body>
</html>
