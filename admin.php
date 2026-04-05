<?php
$admins = [
    ['name' => 'Admin Owner', 'role' => 'Super Admin'],
    ['name' => 'Merch Team', 'role' => 'Product Manager'],
    ['name' => 'Ops Lead', 'role' => 'Order Manager'],
];

$orders = [
    ['id' => '#8452', 'customer' => 'Maya D.', 'status' => 'Shipped', 'amount' => '$299'],
    ['id' => '#8451', 'customer' => 'Daniel R.', 'status' => 'Processing', 'amount' => '$399'],
    ['id' => '#8450', 'customer' => 'Sofia K.', 'status' => 'Delivered', 'amount' => '$149'],
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | NovaMarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="app-layout admin-theme">
<div class="dashboard-shell">
    <aside class="sidebar surface">
        <h2>Admin Panel</h2>
        <a class="active" href="#">Dashboard Analytics</a>
        <a href="#">Product Management</a>
        <a href="#">Order Management</a>
        <a href="#">Customer Management</a>
        <a href="#">Categories</a>
        <a href="#">Coupons</a>
        <a href="#referral-admin">Referral System</a>
        <a href="#">Reports</a>
        <a href="#">Settings</a>
        <a href="index.php">Back to Storefront</a>
    </aside>

    <div class="content-area">
        <header class="content-topbar surface">
            <h3>Multi-Admin Workspace</h3>
            <div class="topbar-actions">
                <?php foreach ($admins as $admin): ?>
                    <span class="pill"><?= htmlspecialchars($admin['name']) ?> · <?= htmlspecialchars($admin['role']) ?></span>
                <?php endforeach; ?>
            </div>
        </header>

        <main class="content-grid">
            <section class="metrics-grid">
                <article class="metric-card surface"><span>Revenue (30d)</span><strong>$284,100</strong></article>
                <article class="metric-card surface"><span>Orders (30d)</span><strong>1,249</strong></article>
                <article class="metric-card surface"><span>New Customers</span><strong>418</strong></article>
                <article class="metric-card surface"><span>Fraud Alerts</span><strong>6</strong></article>
            </section>

            <section class="surface panel">
                <div class="panel-head">
                    <h3>Order Management</h3>
                    <button class="btn btn-small btn-primary">Update Status</button>
                </div>
                <table>
                    <thead>
                    <tr><th>Order</th><th>Customer</th><th>Status</th><th>Amount</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['customer']) ?></td>
                            <td><span class="pill"><?= htmlspecialchars($order['status']) ?></span></td>
                            <td><?= htmlspecialchars($order['amount']) ?></td>
                            <td><button class="btn btn-small btn-ghost">Edit</button></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <section class="surface panel">
                <div class="panel-head">
                    <h3>Product & Inventory</h3>
                    <button class="btn btn-small btn-primary">Add Product</button>
                </div>
                <div class="form-grid">
                    <input type="text" placeholder="Product name">
                    <input type="number" placeholder="Stock">
                    <input type="number" placeholder="Price">
                    <select>
                        <option>Category</option>
                        <option>Wearables</option>
                        <option>Audio</option>
                    </select>
                    <button class="btn btn-secondary">Save Product</button>
                    <button class="btn btn-ghost">Delete Product</button>
                </div>
            </section>

            <section class="surface panel" id="referral-admin">
                <div class="panel-head">
                    <h3>Referral Control Center</h3>
                    <span class="pill">Fraud Monitoring Enabled</span>
                </div>
                <div class="form-grid">
                    <label>Commission Tier 1 (%) <input type="number" value="12"></label>
                    <label>Commission Tier 2 (%) <input type="number" value="6"></label>
                    <label>Minimum Withdrawal ($) <input type="number" value="100"></label>
                    <label>Payout Cycle
                        <select>
                            <option>Weekly</option>
                            <option>Bi-Weekly</option>
                            <option>Monthly</option>
                        </select>
                    </label>
                </div>
                <div class="metrics-grid two-col">
                    <article class="metric-card subtle"><span>Pending Payout Requests</span><strong>21</strong></article>
                    <article class="metric-card subtle"><span>Approved Today</span><strong>9</strong></article>
                    <article class="metric-card subtle"><span>Flagged Referrals</span><strong>4</strong></article>
                    <article class="metric-card subtle"><span>Total Referral Revenue</span><strong>$72,440</strong></article>
                </div>
                <div class="button-row">
                    <button class="btn btn-primary">Approve Payouts</button>
                    <button class="btn btn-secondary">Review Fraud Queue</button>
                </div>
            </section>
        </main>
    </div>
</div>
<script src="assets/app.js"></script>
</body>
</html>
