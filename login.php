<?php
require __DIR__ . '/init.php';
require __DIR__ . '/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'Invalid email or password.';
        redirectTo('/login.php');
    }

    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['success'] = 'Welcome back, ' . $user['name'] . '!';
    redirectTo('/dashboard.php');
}

$current = currentUser($db);
renderHeader('Login', $current);
?>
<h1>Login</h1>
<?php if ($success = flash('success')): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<?php if ($error = flash('error')): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post">
    <label>Email <input name="email" type="email" required></label><br><br>
    <label>Password <input name="password" type="password" required></label><br><br>
    <button class="btn" type="submit">Login</button>
</form>
<?php renderFooter(); ?>
