<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_auth();

$pdo = db();
$productId = (int) ($_GET['product_id'] ?? 0);
$userId = (int) current_user()['id'];

$sql = 'SELECT p.title, p.file_path
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        JOIN products p ON p.id = oi.product_id
        WHERE oi.product_id = ? AND o.user_id = ? AND o.payment_status = "paid"
        LIMIT 1';
$stmt = $pdo->prepare($sql);
$stmt->execute([$productId, $userId]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(403);
    exit('You are not allowed to download this file.');
}

$file = __DIR__ . '/../' . ltrim($product['file_path'], '/');
if (!is_file($file)) {
    http_response_code(404);
    exit('File not found.');
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Content-Length: ' . (string) filesize($file));
readfile($file);
exit;
