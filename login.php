<?php
require_once __DIR__ . '/header.php';
redirectIfLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        setFlash('error', 'Invalid email or password.');
        header('Location: login.php');
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    header('Location: dashboard.php');
    exit;
}

$error = getFlash('error');
$success = getFlash('success');
?>

<h1>Login</h1>
<?php if ($error): ?><div class="flash flash-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<?php if ($success): ?><div class="flash flash-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

<div class="card">
    <form method="post">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button class="btn" type="submit">Login</button>
    </form>
    <p>Default admin: <strong>admin@example.com</strong> / <strong>admin123</strong></p>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
