
<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if (!is_logged_in()) {
    $_SESSION['error'] = 'Please log in to manage your cart.';
    redirect('views/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('views/cart.php');
}

$cart_id = filter_var(
    $_POST['cart_id'] ?? '',
    FILTER_VALIDATE_INT
);

if ($cart_id === false || $cart_id === null || $cart_id <= 0) {
    $_SESSION['error'] = 'Invalid cart item.';
    redirect('views/cart.php');
}

$customer_id = (int) $_SESSION['customer_id'];

$cartController = new CartController();
$cartItems = $cartController->getCart($customer_id);

$ownsItem = false;

foreach ($cartItems as $item) {
    if ((int) $item['cart_id'] === $cart_id) {
        $ownsItem = true;
        break;
    }
}

if (!$ownsItem) {
    $_SESSION['error'] = 'Cart item not found.';
    redirect('views/cart.php');
}

if ($cartController->removeFromCart($cart_id)) {
    $_SESSION['success'] = 'Item removed from your cart.';
} else {
    $_SESSION['error'] = 'Could not remove the item.';
}

redirect('views/cart.php');
