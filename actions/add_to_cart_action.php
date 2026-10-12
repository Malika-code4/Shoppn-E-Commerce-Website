
<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if (!is_logged_in()) {
    $_SESSION['error'] = 'Please log in to add products to your cart.';
    redirect('views/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('views/home.php');
}

$customer_id = (int) $_SESSION['customer_id'];

$product_id = filter_var(
    $_POST['product_id'] ?? '',
    FILTER_VALIDATE_INT
);

$qty = filter_var(
    $_POST['qty'] ?? 1,
    FILTER_VALIDATE_INT
);

if (
    $product_id === false ||
    $product_id <= 0 ||
    $qty === false ||
    $qty < 1 ||
    $qty > 100
) {
    $_SESSION['error'] = 'Invalid product or quantity.';
    redirect('views/home.php');
}

$cartController = new CartController();

if ($cartController->addToCart($customer_id, $product_id, $qty)) {
    $_SESSION['success'] = 'Product added to your cart successfully.';
} else {
    $_SESSION['error'] = 'Could not add the product to your cart.';
}

redirect('views/product_details.php?id=' . $product_id);
