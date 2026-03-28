<?php

function renderHeader(string $title, ?array $user = null): void
{
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($title); ?></title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; color: #0f172a; }
            nav { background: #111827; padding: 12px 20px; color: #fff; display: flex; justify-content: space-between; align-items: center; }
            nav a { color: #fff; text-decoration: none; margin-right: 12px; }
            nav .right a { margin-right: 0; margin-left: 12px; }
            .container { max-width: 1000px; margin: 24px auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,.05); }
            .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 16px; }
            .card { border: 1px solid #e5e7eb; padding: 16px; border-radius: 10px; }
            .badge { display: inline-block; background: #e0e7ff; color: #3730a3; border-radius: 999px; padding: 4px 10px; font-size: 12px; }
            .btn { background: #2563eb; color: #fff; border: none; padding: 9px 14px; border-radius: 8px; cursor: pointer; text-decoration: none; display: inline-block; }
            .btn-secondary { background: #0f766e; }
            .btn-danger { background: #b91c1c; }
            .mt-1 { margin-top: 8px; }
            .mt-2 { margin-top: 16px; }
            input, textarea { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; margin-top: 6px; }
            label { font-weight: bold; font-size: 14px; }
            table { width: 100%; border-collapse: collapse; margin-top: 12px; }
            th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
            .alert { padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; }
            .alert-success { background: #dcfce7; color: #14532d; }
            .alert-error { background: #fee2e2; color: #7f1d1d; }
            code { background: #eef2ff; padding: 2px 6px; border-radius: 4px; }
        </style>
    </head>
    <body>
    <nav>
        <div>
            <a href="/index.php"><strong>DigitalStore</strong></a>
            <a href="/store.php">Store</a>
        </div>
        <div class="right">
            <?php if ($user): ?>
                <a href="/dashboard.php">Dashboard</a>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="/admin.php">Admin</a>
                <?php endif; ?>
                <a href="/logout.php">Logout</a>
            <?php else: ?>
                <a href="/login.php">Login</a>
                <a href="/register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">
    <?php
}

function renderFooter(): void
{
    ?>
    </div>
    </body>
    </html>
    <?php
}
