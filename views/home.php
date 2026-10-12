
<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// Require users to log in before viewing the shop.
if (!is_logged_in()) {
    redirect('views/login.php');
}

$page_title = 'Home - Shoppn';

$controller = new ProductController();

$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();

$search = trim($_GET['search'] ?? '');
$category_id = filter_var(
    $_GET['category'] ?? '',
    FILTER_VALIDATE_INT
);
$brand_id = filter_var(
    $_GET['brand'] ?? '',
    FILTER_VALIDATE_INT
);

$category_id = ($category_id !== false && $category_id > 0)
    ? $category_id : 0;

$brand_id = ($brand_id !== false && $brand_id > 0)
    ? $brand_id : 0;

// Apply search, category, and brand filters.
if ($search !== '') {
    $products = $controller->searchProducts($search);
} elseif ($category_id > 0) {
    $products = $controller->getProductsByCategory($category_id);
} elseif ($brand_id > 0) {
    $products = $controller->getProductsByBrand($brand_id);
} else {
    $products = $controller->getFeaturedProducts(12);
}

// Apply additional filters when a search is combined
// with a category or brand selection.
if ($search !== '' && ($category_id > 0 || $brand_id > 0)) {
    $products = array_filter($products, function ($product) use (
        $category_id,
        $brand_id
    ) {
        return ($category_id === 0 ||
                (int) $product['product_cat'] === $category_id)
            && ($brand_id === 0 ||
                (int) $product['product_brand'] === $brand_id);
    });
}

require_once __DIR__ . '/layout/header.php';
?>

<h1>Welcome to Shoppn</h1>
<p>Browse our available products below.</p>

<?php if (is_admin()): ?>
    <p>
        <a href="<?php echo BASE_URL; ?>/views/admin/brand.php">
            Manage Brands
        </a> |
        <a href="<?php echo BASE_URL; ?>/views/admin/category.php">
            Manage Categories
        </a> |
        <a href="<?php echo BASE_URL; ?>/views/admin/product.php">
            Manage Products
        </a>
    </p>
<?php endif; ?>

<hr>

<h2>Find Products</h2>

<form method="GET" action="">
    <p>
        <label for="search">Search products</label><br>
        <input
            type="search"
            id="search"
            name="search"
            placeholder="Enter a product name or keyword"
            value="<?php echo htmlspecialchars($search); ?>"
        >
    </p>

    <p>
        <label for="category">Category</label><br>
        <select id="category" name="category">
            <option value="">All categories</option>

            <?php foreach ($categories as $category): ?>
                <option
                    value="<?php echo (int) $category['cat_id']; ?>"
                    <?php echo $category_id === (int) $category['cat_id']
                        ? 'selected' : ''; ?>
                >
                    <?php echo htmlspecialchars($category['cat_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label for="brand">Brand</label><br>
        <select id="brand" name="brand">
            <option value="">All brands</option>

            <?php foreach ($brands as $brand): ?>
                <option
                    value="<?php echo (int) $brand['brand_id']; ?>"
                    <?php echo $brand_id === (int) $brand['brand_id']
                        ? 'selected' : ''; ?>
                >
                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <button type="submit">Search / Filter</button>
    <a href="<?php echo BASE_URL; ?>/views/home.php">Clear filters</a>
</form>

<hr>

<h2>Available Products</h2>

<?php if (empty($products)): ?>
    <p>No products found. Try another search or filter.</p>
<?php else: ?>

    <div style="display:flex;flex-wrap:wrap;gap:20px;">

        <?php foreach ($products as $product): ?>
            <div style="
                width:220px;
                padding:12px;
                border:1px solid #ddd;
                border-radius:8px;
            ">

                <?php if (!empty($product['product_image'])): ?>
                    <img
                        src="<?php echo BASE_URL; ?>/images/products/<?php
                            echo rawurlencode(
                                basename($product['product_image'])
                            );
                        ?>"
                        alt="<?php echo htmlspecialchars(
                            $product['product_title']
                        ); ?>"
                        style="
                            width:100%;
                            height:180px;
                            object-fit:contain;
                        "
                    >
                <?php endif; ?>

                <h3>
                    <?php echo htmlspecialchars(
                        $product['product_title']
                    ); ?>
                </h3>

                <p>
                    Price:
                    <?php echo htmlspecialchars(
                        number_format(
                            (float) $product['product_price'],
                            2
                        )
                    ); ?>
                </p>

                <p>
                    Category:
                    <?php echo htmlspecialchars(
                        $product['cat_name'] ?? ''
                    ); ?>
                </p>

                <p>
                    Brand:
                    <?php echo htmlspecialchars(
                        $product['brand_name'] ?? ''
                    ); ?>
                </p>

                <p>
                    <?php
                    $description = $product['product_desc'] ?? '';
                    echo htmlspecialchars(
                        strlen($description) > 100
                            ? substr($description, 0, 100) . '...'
                            : $description
                    );
                    ?>
                </p>

                <a href="<?php echo BASE_URL; ?>/views/product_details.php?id=<?php
                    echo (int) $product['product_id'];
                ?>">
                    View Details
                </a>

            </div>
        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
