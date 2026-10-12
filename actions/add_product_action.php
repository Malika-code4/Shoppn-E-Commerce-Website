<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/product.php');
}

$title = trim($_POST['product_title'] ?? '');
$price = $_POST['product_price'] ?? '';
$desc = trim($_POST['product_desc'] ?? '');
$keywords = trim($_POST['product_keywords'] ?? '');
$cat = filter_var($_POST['product_cat'] ?? '', FILTER_VALIDATE_INT);
$brand = filter_var($_POST['product_brand'] ?? '', FILTER_VALIDATE_INT);

if (
    $title === '' ||
    $desc === '' ||
    $keywords === '' ||
    !is_numeric($price) ||
    (float) $price < 0 ||
    $cat === false || $cat <= 0 ||
    $brand === false || $brand <= 0
) {
    $_SESSION['error'] = 'Please complete all fields correctly.';
    redirect('../views/admin/product.php');
}

if (
    !isset($_FILES['product_image']) ||
    $_FILES['product_image']['error'] !== UPLOAD_ERR_OK
) {
    $_SESSION['error'] = 'Please select a product image.';
    redirect('../views/admin/product.php');
}

$file = $_FILES['product_image'];

$maxSize = 5 * 1024 * 1024;

if ($file['size'] <= 0 || $file['size'] > $maxSize) {
    $_SESSION['error'] = 'Image must be smaller than 5 MB.';
    redirect('../views/admin/product.php');
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
    redirect('../views/admin/product.php');
}

$uploadDir = __DIR__ . '/../images/products/';

if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
    $_SESSION['error'] = 'The product image folder is unavailable or not writable.';
    redirect('../views/admin/product.php');
}

$filename = uniqid('product_', true) . '.' . $allowed[$mime];
$destination = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    $_SESSION['error'] = 'Could not save the product image.';
    redirect('../views/admin/product.php');
}

$controller = new ProductController();

$result = $controller->addProduct(
    (int) $cat,
    (int) $brand,
    $title,
    (float) $price,
    $desc,
    $filename,
    $keywords
);

if ($result) {
    $_SESSION['success'] = 'Product added successfully.';
} else {
    if (file_exists($destination)) {
        unlink($destination);
    }

    $_SESSION['error'] = 'Could not add the product. Check the selected category and brand.';
}

redirect('../views/admin/product.php');