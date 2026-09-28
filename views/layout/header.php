<?php
require_once __DIR__ . '/../../core/core.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppn</title>

    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>

<header>
    <div>
        <h1>Shoppn</h1>
    </div>

    <nav>
        <a href="../../index.php">Home</a>

        <?php if (is_logged_in()): ?>

            <span>
                Welcome,
                <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
            </span>

            <a href="../account/my_account.php">My Account</a>
            <a href="../../logout.php">Logout</a>

            <?php if (is_admin()): ?>
                <a href="../admin/brand.php">Brands</a>
                <a href="../admin/category.php">Categories</a>
                <a href="../admin/product.php">Products</a>
            <?php endif; ?>

        <?php else: ?>

            <a href="../register.php">Register</a>
            <a href="../login.php">Login</a>

        <?php endif; ?>

        <a href="../cart.php">Cart</a>
    </nav>

    <form action="../search_results.php" method="GET">
        <input
            type="text"
            name="query"
            placeholder="Search products..."
        >
        <button type="submit">Search</button>
    </form>
</header>

<main>