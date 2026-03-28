<?php
require_once __DIR__ . '/header.php';
redirectIfLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        setFlash('error', 'All fields are required.');
        header('Location: register.php');
        exit;
    }

    $stmt = db()->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        setFlash('error', 'Email already registered.');
        header('Location: register.php');
        exit;
    }

    $referrerId = null;
    $cookieReferral = $_COOKIE['referral_code'] ?? null;
    if ($cookieReferral) {
        $stmt = db()->prepare('SELECT id FROM users WHERE referral_code = ?');
        $stmt->execute([$cookieReferral]);
        $ref = $stmt->fetch();
        if ($ref) {
            $referrerId = (int) $ref['id'];
        }
    }

    do {
        $code = generateReferralCode();
        $exists = db()->prepare('SELECT id FROM users WHERE referral_code = ?');
        $exists->execute([$code]);
    } while ($exists->fetch());

    $stmt = db()->prepare('INSERT INTO users (name, email, password, referral_code, referred_by) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $name,
        $email,
        password_hash($password, PASSWORD_DEFAULT),
        $code,
        $referrerId,
    ]);

    setFlash('success', 'Registration successful. Please login.');
    header('Location: login.php');
    exit;
}

$error = getFlash('error');
$success = getFlash('success');
?>

<h1>Create Your Account</h1>
<?php if ($error): ?><div class="flash flash-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<?php if ($success): ?><div class="flash flash-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

<div class="card">
    <form method="post">
        <label>Name</label>
        <input name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button class="btn" type="submit">Register</button>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
