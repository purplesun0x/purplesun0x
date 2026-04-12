<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_admin();
$pdo = db();
$rows = $pdo->query('SELECT t.*,u.email FROM transactions t JOIN users u ON u.id=t.user_id ORDER BY t.created_at DESC')->fetchAll();
include __DIR__ . '/../public/_header.php';
?>
<h1 class="h3">Transactions</h1>
<table class="table table-striped">
<thead><tr><th>Reference</th><th>User</th><th>Amount</th><th>Provider</th><th>Status</th><th>Date</th></tr></thead>
<tbody>
<?php foreach ($rows as $row): ?>
<tr>
<td><?= esc($row['reference']) ?></td>
<td><?= esc($row['email']) ?></td>
<td>$<?= number_format((float) $row['amount'],2) ?></td>
<td><?= esc($row['provider']) ?></td>
<td><?= esc($row['status']) ?></td>
<td><?= esc($row['created_at']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php include __DIR__ . '/../public/_footer.php'; ?>
