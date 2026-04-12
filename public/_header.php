<?php
require_once __DIR__ . '/../includes/functions.php';
$user = current_user();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc(config('app.name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= url('index.php') ?>">Script Marketplace</a>
        <div class="navbar-nav ms-auto gap-2">
            <a class="nav-link" href="<?= url('index.php') ?>">Home</a>
            <a class="nav-link" href="<?= url('checkout.php') ?>">Cart</a>
            <?php if ($user): ?><a class="nav-link" href="<?= url('my-downloads.php') ?>">My Downloads</a><?php endif; ?>
            <?php if ($user): ?>
                <?php if ($user['role'] === 'user'): ?>
                    <a class="nav-link" href="../vendor/become-vendor.php">Become Vendor</a>
                <?php endif; ?>
                <?php if (in_array($user['role'], ['vendor', 'admin'], true)): ?>
                    <a class="nav-link" href="../vendor/dashboard.php">Vendor</a>
                <?php endif; ?>
                <?php if ($user['role'] === 'admin'): ?>
                    <a class="nav-link" href="../admin/dashboard.php">Admin</a>
                <?php endif; ?>
                <a class="nav-link" href="../auth/logout.php">Logout</a>
            <?php else: ?>
                <a class="nav-link" href="../auth/login.php">Login</a>
                <a class="nav-link" href="../auth/register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container">
    <?php if ($message = flash('success')): ?>
        <div class="alert alert-success"><?= esc($message) ?></div>
    <?php endif; ?>
    <?php if ($error = flash('error')): ?>
        <div class="alert alert-danger"><?= esc($error) ?></div>
    <?php endif; ?>
