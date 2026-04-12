<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_vendor();
$pdo = db();

if (is_post()) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);

    [$valid, $uploadError] = validate_upload($_FILES['file'] ?? []);

    if (!$title || !$description || $price <= 0 || !$valid) {
        set_flash('error', $uploadError ?: 'Please fill all fields correctly.');
        redirect('upload-product.php');
    }

    $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    $filename = uniqid('product_', true) . '.' . $extension;
    $destination = __DIR__ . '/../uploads/products/' . $filename;
    move_uploaded_file($_FILES['file']['tmp_name'], $destination);

    $stmt = $pdo->prepare('INSERT INTO products (user_id, title, description, price, file_path, thumbnail, status, created_at) VALUES (?, ?, ?, ?, ?, NULL, "pending", NOW())');
    $stmt->execute([
        (int) current_user()['id'],
        $title,
        $description,
        $price,
        'uploads/products/' . $filename,
    ]);

    set_flash('success', 'Product uploaded and awaiting admin approval.');
    redirect('my-products.php');
}

include __DIR__ . '/../public/_header.php';
?>
<h1 class="h3">Upload Product</h1>
<form method="post" enctype="multipart/form-data" class="card card-body">
    <label class="form-label">Title</label>
    <input name="title" class="form-control mb-3" required>
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control mb-3" rows="5" required></textarea>
    <label class="form-label">Price ($)</label>
    <input type="number" min="1" step="0.01" name="price" class="form-control mb-3" required>
    <label class="form-label">File (zip, rar, pdf)</label>
    <input type="file" name="file" class="form-control mb-3" required>
    <button class="btn btn-primary">Upload</button>
</form>
<?php include __DIR__ . '/../public/_footer.php'; ?>
