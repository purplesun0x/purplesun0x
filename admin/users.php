<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_admin();
$pdo = db();
if (isset($_GET['vendor'])) {
    $stmt = $pdo->prepare('UPDATE users SET role="vendor" WHERE id=?');
    $stmt->execute([(int) $_GET['vendor']]);
    set_flash('success', 'User upgraded to vendor.');
    redirect('users.php');
}
$users = $pdo->query('SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC')->fetchAll();
include __DIR__ . '/../public/_header.php';
?>
<h1 class="h3">Users</h1>
<table class="table table-striped">
<thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr></thead>
<tbody>
<?php foreach ($users as $user): ?>
<tr>
<td><?= esc($user['name']) ?></td><td><?= esc($user['email']) ?></td><td><?= esc($user['role']) ?></td>
<td><?php if ($user['role'] === 'user'): ?><a class="btn btn-sm btn-outline-primary" href="?vendor=<?= (int) $user['id'] ?>">Make Vendor</a><?php endif; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php include __DIR__ . '/../public/_footer.php'; ?>
