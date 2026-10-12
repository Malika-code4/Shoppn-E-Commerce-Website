<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

if (!is_logged_in()) {
    redirect('views/login.php');
}

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($id === false || $id <= 0) {
    $_SESSION['error'] = 'Invalid product selected.';
    redirect('views/home.php');
}

$controller = new ProductController();
$product = $controller->getProduct($id);

if (!$product) {
    $_SESSION['error'] = 'Product not found.';
    redirect('views/home.php');
}

$page_title = $product['product_title'] . ' - Shoppn';

require_once __DIR__ . '/layout/header.php';
?>

<p>
    <a href="<?php echo BASE_URL; ?>/views/home.php">
        &larr; Back to Products
    </a>
</p>

<h1><?php echo htmlspecialchars($product['product_title']); ?></h1>

<?php if (!empty($product['product_image'])): ?>
    <p>
        <img
            src="<?php echo BASE_URL; ?>/images/products/<?php echo rawurlencode(basename($product['product_image'])); ?>"
            alt="<?php echo htmlspecialchars($product['product_title']); ?>"
            style="width:100%; max-width:400px; max-height:400px; object-fit:contain;"
        >
    </p>
<?php endif; ?>

<h2>
    Price:
    <?php echo htmlspecialchars(number_format((float) $product['product_price'], 2)); ?>
</h2>

<p>
    <strong>Category:</strong>
    <?php echo htmlspecialchars($product['cat_name'] ?? ''); ?>
</p>

<p>
    <strong>Brand:</strong>
    <?php echo htmlspecialchars($product['brand_name'] ?? ''); ?>
</p>

<h3>Product Description</h3>

<p>
    <?php echo nl2br(htmlspecialchars($product['product_desc'] ?? '')); ?>
</p>

<h3>Add to Cart</h3>

<form action="<?php echo BASE_URL; ?>/actions/add_to_cart_action.php" method="POST">
    <input
        type="hidden"
        name="product_id"
        value="<?php echo (int) $product['product_id']; ?>"
    >

    <label for="qty">Quantity:</label>

    <input
        type="number"
        id="qty"
        name="qty"
        value="1"
        min="1"
        max="100"
        required
    >

    <button type="submit">Add to Cart</button>
</form>

<?php if (!empty($_SESSION['success'])): ?>
    <p style="color: green;">
        <?php
        echo htmlspecialchars($_SESSION['success']);
        unset($_SESSION['success']);
        ?>
    </p>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <p style="color: red;">
        <?php
        echo htmlspecialchars($_SESSION['error']);
        unset($_SESSION['error']);
        ?>
    </p>
<?php endif; ?>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
