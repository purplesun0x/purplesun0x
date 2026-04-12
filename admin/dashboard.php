<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_admin();
$pdo = db();
$stats = [
    'users' => (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'products' => (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
    'pending' => (int) $pdo->query("SELECT COUNT(*) FROM products WHERE status='pending'")->fetchColumn(),
    'transactions' => (int) $pdo->query('SELECT COUNT(*) FROM transactions')->fetchColumn(),
];
include __DIR__ . '/../public/_header.php';
?>
<h1 class="h3">Admin Dashboard</h1>
<div class="row g-3">
<?php foreach ($stats as $label => $value): ?>
    <div class="col-md-3"><div class="card card-body"><h6><?= ucfirst($label) ?></h6><p class="fs-4"><?= $value ?></p></div></div>
<?php endforeach; ?>
</div>
<div class="mt-3 d-flex gap-2">
    <a href="users.php" class="btn btn-outline-primary">Manage Users</a>
    <a href="products.php" class="btn btn-outline-warning">Review Products</a>
    <a href="transactions.php" class="btn btn-outline-success">Transactions</a>
</div>
<?php include __DIR__ . '/../public/_footer.php'; ?>
