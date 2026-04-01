<?php
require __DIR__ . '/init.php';
require __DIR__ . '/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $referralCode = strtoupper(trim($_POST['referral_code'] ?? ''));

    if ($name === '' || $email === '' || $password === '') {
        $_SESSION['error'] = 'Name, email and password are required.';
        redirectTo('/register.php');
    }

    $referredById = null;
    if ($referralCode !== '') {
        $refStmt = $db->prepare('SELECT id FROM users WHERE referral_code = :code LIMIT 1');
        $refStmt->execute([':code' => $referralCode]);
        $refUser = $refStmt->fetch(PDO::FETCH_ASSOC);
        if (!$refUser) {
            $_SESSION['error'] = 'Invalid referral code.';
            redirectTo('/register.php');
        }
        $referredById = (int)$refUser['id'];
    }

    try {
        $insert = $db->prepare('INSERT INTO users (name, email, password, role, referral_code, referred_by, created_at) VALUES (:name, :email, :password, :role, :referral_code, :referred_by, :created_at)');
        $insert->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':role' => 'user',
            ':referral_code' => createReferralCode(),
            ':referred_by' => $referredById,
            ':created_at' => date('c'),
        ]);
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Email is already in use.';
        redirectTo('/register.php');
    }

    $_SESSION['success'] = 'Registration complete. Please login.';
    redirectTo('/login.php');
}

$user = currentUser($db);
renderHeader('Register', $user);
?>
<h1>Create Account</h1>
<?php if ($error = flash('error')): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post">
    <label>Name <input name="name" required></label><br><br>
    <label>Email <input name="email" type="email" required></label><br><br>
    <label>Password <input name="password" type="password" minlength="6" required></label><br><br>
    <label>Referral Code (optional) <input name="referral_code" placeholder="e.g. 1A2B3C4D"></label><br><br>
    <button class="btn" type="submit">Create Account</button>
</form>
<?php renderFooter(); ?>
