<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $referrerCode = trim($_POST['referrer_code'] ?? '');

    if (!$name || !$email || strlen($password) < 6) {
        flash('error', 'Provide valid registration details (password min 6 chars).');
        redirect('/public/register.php');
    }

    $referrerId = null;
    if ($referrerCode !== '') {
        $refStmt = $pdo->prepare('SELECT id FROM users WHERE referral_code = ?');
        $refStmt->execute([$referrerCode]);
        $referrer = $refStmt->fetch();
        if ($referrer) {
            $referrerId = (int) $referrer['id'];
        }
    }

    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, referral_code, referred_by) VALUES (?, ?, ?, ?, ?)');
    try {
        $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            referralCode(),
            $referrerId,
        ]);

        flash('success', 'Registration successful. Please login.');
        redirect('/public/login.php');
    } catch (PDOException $exception) {
        flash('error', 'Email already registered.');
        redirect('/public/register.php');
    }
}

renderHeader('Register');
?>
<h1>Create account</h1>
<form method="post" class="card">
    <label>Full Name</label>
    <input name="name" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" minlength="6" required>
    <label>Referral Code (optional)</label>
    <input name="referrer_code">
    <button type="submit">Register</button>
</form>
<?php renderFooter(); ?>
