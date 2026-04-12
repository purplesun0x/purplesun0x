<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_auth();

$pdo = db();
$stmt = $pdo->prepare('SELECT DISTINCT p.id, p.title, p.description
    FROM order_items oi
    JOIN orders o ON o.id = oi.order_id
    JOIN products p ON p.id = oi.product_id
    WHERE o.user_id=? AND o.payment_status="paid"
    ORDER BY o.created_at DESC');
$stmt->execute([(int) current_user()['id']]);
$products = $stmt->fetchAll();

include __DIR__ . '/_header.php';
?>
<h1 class="h3">My Downloads</h1>
<?php if (!$products): ?>
    <p>You have no purchases yet.</p>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($products as $product): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1"><?= esc($product['title']) ?></h6>
                    <small><?= esc(substr($product['description'], 0, 100)) ?>...</small>
                </div>
                <a class="btn btn-sm btn-primary" href="download.php?product_id=<?= (int) $product['id'] ?>">Download</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/_footer.php'; ?>
