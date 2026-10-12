
<?php

require_once __DIR__ . '/../../core/core.php';

$header_name = $_SESSION['customer_name'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($page_title ?? 'Shoppn'); ?></title>
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">

</head>
<body>

<header>
    <h2><a href="<?php echo BASE_URL; ?>/index.php">Shoppn</a></h2>

    <nav>
        <a href="<?php echo BASE_URL; ?>/index.php">Home</a>

        <?php if (is_admin()): ?>
            | <a href="<?php echo BASE_URL; ?>/views/admin/brand.php">Brands</a>
            | <a href="<?php echo BASE_URL; ?>/views/admin/category.php">Categories</a>
            | <a href="<?php echo BASE_URL; ?>/views/admin/product.php">Products</a>
        <?php endif; ?>

        <?php if (is_logged_in()): ?>
            | Welcome, <?php echo htmlspecialchars($header_name); ?>
            | <a href="<?php echo BASE_URL; ?>/views/cart.php">My Cart</a>
            | <a href="<?php echo BASE_URL; ?>/actions/logout_action.php">Logout</a>
        <?php else: ?>
            | <a href="<?php echo BASE_URL; ?>/views/login.php">Login</a>
            | <a href="<?php echo BASE_URL; ?>/views/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<hr>
