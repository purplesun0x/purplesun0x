<?php
require_once __DIR__ . '/../includes/auth.php';

if (is_post()) {
    [$ok, $message] = register_user(
        trim($_POST['name'] ?? ''),
        trim($_POST['email'] ?? ''),
        $_POST['password'] ?? ''
    );

    if ($ok) {
        set_flash('success', $message);
        redirect('login.php');
    }
    set_flash('error', $message);
}
include __DIR__ . '/../public/_header.php';
?>
<h1 class="h3">Register</h1>
<form method="post" class="card card-body">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control mb-3" required>
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control mb-3" required>
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control mb-3" required>
    <button class="btn btn-primary">Register</button>
</form>
<?php include __DIR__ . '/../public/_footer.php'; ?>
