<?php
require_once __DIR__ . '/functions.php';
captureReferral();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Store</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
    <nav>
        <a href="index.php" class="brand">Digital Store</a>
        <div class="links">
            <a href="index.php">Store</a>
            <?php if ($user): ?>
                <a href="dashboard.php">Dashboard</a>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="admin.php">Admin</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>
