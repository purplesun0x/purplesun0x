<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = $user;
        flash('success', 'Welcome back, ' . $user['name'] . '!');
        redirect('/public/dashboard.php');
    }

    flash('error', 'Invalid credentials.');
    redirect('/public/login.php');
}

renderHeader('Login');
?>
<h1>Login</h1>
<form method="post" class="card">
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
<?php renderFooter(); ?>
