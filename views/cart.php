
<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';

if (!is_logged_in()) {
    $_SESSION['error'] = 'Please log in to view your cart.';
    redirect('views/login.php');
}

$cartController = new CartController();
$productController = new ProductController();

$customer_id = (int) $_SESSION['customer_id'];

$cartItems = $cartController->getCart($customer_id);

$cartProducts = [];
$total = 0;

foreach ($cartItems as $item) {
    $product = $productController->getProduct(
        (int) $item['p_id']
    );

    if (!$product) {
        continue;
    }

    $qty = (int) $item['qty'];
    $price = (float) $product['product_price'];
    $subtotal = $price * $qty;

    
    $cartProducts[] = [
        'cart_id' => (int) $item['cart_id'],
        'product' => $product,
        'qty' => $qty,
        'subtotal' => $subtotal
    ];


    $total += $subtotal;
}

$page_title = 'My Shopping Cart - Shoppn';

require_once __DIR__ . '/layout/header.php';
?>

<h1>My Shopping Cart</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <p style="color:green;">
        <?php
        echo htmlspecialchars($_SESSION['success']);
        unset($_SESSION['success']);
        ?>
    </p>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red;">
        <?php
        echo htmlspecialchars($_SESSION['error']);
        unset($_SESSION['error']);
        ?>
    </p>
<?php endif; ?>

<?php if (empty($cartProducts)): ?>

    <p>Your shopping cart is empty.</p>

    <p>
        <a href="<?php echo BASE_URL; ?>/views/home.php">
            Continue Shopping
        </a>
    </p>

<?php else: ?>

    <table border="1" cellpadding="10" cellspacing="0"
           style="width:100%; border-collapse:collapse;">

        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($cartProducts as $cartItem): ?>
                <?php $product = $cartItem['product']; ?>

                <tr>
                    <td>
                        <?php if (!empty($product['product_image'])): ?>
                            <img
                                src="<?php echo BASE_URL; ?>/images/products/<?php echo rawurlencode(basename($product['product_image'])); ?>"
                                alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                                style="width:70px; height:70px; object-fit:contain;"
                            >
                        <?php endif; ?>

                        <br>

                        <?php
                        echo htmlspecialchars($product['product_title']);
                        ?>
                    </td>

                    <td>
                        <?php
                        echo number_format(
                            (float) $product['product_price'],
                            2
                        );
                        ?>
                    </td>

                    <td>
                        <?php echo (int) $cartItem['qty']; ?>
                    </td>

                    <td>
                        <?php
                        echo number_format(
                            (float) $cartItem['subtotal'],
                            2
                        );
                        ?>
                    </td>

                    <td>
                        <form
                            action="<?php echo BASE_URL; ?>/actions/remove_from_cart_action.php"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to remove this item?');"
                        >
                            <input
                                type="hidden"
                                name="cart_id"
                                value="<?php echo (int) $cartItem['cart_id']; ?>"
                            >

                            <button type="submit">
                                Remove
                            </button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right;">
                    Total:
                </th>

                <th>
                    <?php echo number_format($total, 2); ?>
                </th>

                <th></th>
            </tr>
        </tfoot>

    </table>

    <p>
        <a href="<?php echo BASE_URL; ?>/views/home.php">
            Continue Shopping
        </a>
    </p>

<?php endif; ?>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
