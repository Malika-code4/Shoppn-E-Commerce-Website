<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/product.php');
}

$id = filter_var($_POST['product_id'] ?? '', FILTER_VALIDATE_INT);
$cat = filter_var($_POST['product_cat'] ?? '', FILTER_VALIDATE_INT);
$brand = filter_var($_POST['product_brand'] ?? '', FILTER_VALIDATE_INT);

$title = trim($_POST['product_title'] ?? '');
$price = $_POST['product_price'] ?? '';
$desc = trim($_POST['product_desc'] ?? '');
$keywords = trim($_POST['product_keywords'] ?? '');
$existingImage = basename(trim($_POST['existing_image'] ?? ''));

if (
    $id === false || $id <= 0 ||
    $cat === false || $cat <= 0 ||
    $brand === false || $brand <= 0 ||
    $title === '' ||
    $desc === '' ||
    $keywords === '' ||
    !is_numeric($price) ||
    (float) $price < 0 ||
    $existingImage === ''
) {
    $_SESSION['error'] = 'Please complete all fields correctly.';
    redirect('../views/admin/product.php');
}

$controller = new ProductController();
$product = $controller->getProduct($id);

if (!$product) {
    $_SESSION['error'] = 'Product not found.';
    redirect('../views/admin/product.php');
}

$filename = $existingImage;
$newImageUploaded = false;
$destination = '';

if (
    isset($_FILES['product_image']) &&
    $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE
) {
    $file = $_FILES['product_image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'The image upload failed.';
        redirect('../views/admin/product.php?edit_id=' . $id);
    }

    $maxSize = 5 * 1024 * 1024;

    if ($file['size'] <= 0 || $file['size'] > $maxSize) {
        $_SESSION['error'] = 'Image must be smaller than 5 MB.';
        redirect('../views/admin/product.php?edit_id=' . $id);
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    if (!isset($allowed[$mime])) {
        $_SESSION['error'] = 'Only JPEG, PNG, GIF, and WebP images are allowed.';
        redirect('../views/admin/product.php?edit_id=' . $id);
    }

    $uploadDir = __DIR__ . '/../images/products/';

    if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
        $_SESSION['error'] = 'The product image folder is unavailable or not writable.';
        redirect('../views/admin/product.php?edit_id=' . $id);
    }

    $filename = uniqid('product_', true) . '.' . $allowed[$mime];
    $destination = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        $_SESSION['error'] = 'Could not save the new product image.';
        redirect('../views/admin/product.php?edit_id=' . $id);
    }

    $newImageUploaded = true;
}

$result = $controller->updateProduct(
    $id,
    $cat,
    $brand,
    $title,
    (float) $price,
    $desc,
    $filename,
    $keywords
);

if ($result) {
    // Delete the previous image only after the database update succeeds.
    if ($newImageUploaded && !empty($product['product_image'])) {
        $oldImage = basename($product['product_image']);

        if ($oldImage !== $filename) {
            $oldPath = __DIR__ . '/../images/products/' . $oldImage;

            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }
    }

    $_SESSION['success'] = 'Product updated successfully.';
} else {
    if ($newImageUploaded && is_file($destination)) {
        unlink($destination);
    }

    $_SESSION['error'] = 'Could not update the product.';
}

redirect('../views/admin/product.php');