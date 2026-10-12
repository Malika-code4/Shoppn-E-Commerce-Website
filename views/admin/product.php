<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$controller = new ProductController();

$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();
$products = $controller->getFeaturedProducts(100);

$edit_id = filter_var($_GET['edit_id'] ?? '', FILTER_VALIDATE_INT);
$edit_product = null;

if ($edit_id !== false && $edit_id > 0) {
    $edit_product = $controller->getProduct($edit_id);

    if (!$edit_product) {
        $_SESSION['error'] = 'Product not found.';
        redirect('product.php');
    }
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';

unset($_SESSION['success'], $_SESSION['error']);

require_once __DIR__ . '/../layout/header.php';
?>

<h2>Product Management</h2>

<?php if ($success !== ''): ?>
    <p><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<?php if ($error !== ''): ?>
    <p><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if (empty($categories) || empty($brands)): ?>
    <p>
        You need to add at least one category and one brand before
        adding a product.
    </p>
    <p>
        <a href="category.php">Manage Categories</a> |
        <a href="brand.php">Manage Brands</a>
    </p>
<?php else: ?>

    <h3>
        <?php echo $edit_product ? 'Edit Product' : 'Add Product'; ?>
    </h3>

    <form
        method="POST"
        action="<?php echo $edit_product
            ? '../../actions/update_product_action.php'
            : '../../actions/add_product_action.php'; ?>"
        enctype="multipart/form-data"
    >

        <?php if ($edit_product): ?>
            <input
                type="hidden"
                name="product_id"
                value="<?php echo (int) $edit_product['product_id']; ?>"
            >

            <input
                type="hidden"
                name="existing_image"
                value="<?php echo htmlspecialchars($edit_product['product_image']); ?>"
            >
        <?php endif; ?>

        <p>
            <label for="product_title">Product Title</label><br>
            <input
                type="text"
                id="product_title"
                name="product_title"
                maxlength="200"
                value="<?php echo htmlspecialchars($edit_product['product_title'] ?? ''); ?>"
                required
            >
        </p>

        <p>
            <label for="product_price">Price</label><br>
            <input
                type="number"
                id="product_price"
                name="product_price"
                min="0"
                step="0.01"
                value="<?php echo htmlspecialchars((string) ($edit_product['product_price'] ?? '')); ?>"
                required
            >
        </p>

        <p>
            <label for="product_desc">Description</label><br>
            <textarea
                id="product_desc"
                name="product_desc"
                maxlength="500"
                required
            ><?php echo htmlspecialchars($edit_product['product_desc'] ?? ''); ?></textarea>
        </p>

        <p>
            <label for="product_keywords">Keywords</label><br>
            <input
                type="text"
                id="product_keywords"
                name="product_keywords"
                maxlength="100"
                value="<?php echo htmlspecialchars($edit_product['product_keywords'] ?? ''); ?>"
                required
            >
        </p>

        <p>
            <label for="product_cat">Category</label><br>
            <select id="product_cat" name="product_cat" required>
                <option value="">Select a category</option>

                <?php foreach ($categories as $category): ?>
                    <option
                        value="<?php echo (int) $category['cat_id']; ?>"
                        <?php echo (
                            isset($edit_product['product_cat']) &&
                            (int) $edit_product['product_cat'] === (int) $category['cat_id']
                        ) ? 'selected' : ''; ?>
                    >
                        <?php echo htmlspecialchars($category['cat_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="product_brand">Brand</label><br>
            <select id="product_brand" name="product_brand" required>
                <option value="">Select a brand</option>

                <?php foreach ($brands as $brand): ?>
                    <option
                        value="<?php echo (int) $brand['brand_id']; ?>"
                        <?php echo (
                            isset($edit_product['product_brand']) &&
                            (int) $edit_product['product_brand'] === (int) $brand['brand_id']
                        ) ? 'selected' : ''; ?>
                    >
                        <?php echo htmlspecialchars($brand['brand_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="product_image">
                Product Image (JPEG, PNG, GIF, or WebP; maximum 5 MB)
            </label><br>

            <input
                type="file"
                id="product_image"
                name="product_image"
                accept="image/jpeg,image/png,image/gif,image/webp"
                <?php echo $edit_product ? '' : 'required'; ?>
            >
        </p>

        <?php if ($edit_product && !empty($edit_product['product_image'])): ?>
            <p>Current image:</p>
            <img
                src="../../images/products/<?php echo rawurlencode(basename($edit_product['product_image'])); ?>"
                alt="Current product image"
                style="max-width:200px;max-height:200px;"
            >
        <?php endif; ?>

        <p id="image_error" role="alert"></p>

        <button type="submit">
            <?php echo $edit_product ? 'Update Product' : 'Add Product'; ?>
        </button>

        <?php if ($edit_product): ?>
            <a href="product.php">Cancel</a>
        <?php endif; ?>

    </form>

<?php endif; ?>

<hr>

<h3>Existing Products</h3>

<?php if (empty($products)): ?>
    <p>No products have been added yet.</p>
<?php else: ?>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Price</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo (int) $product['product_id']; ?></td>

                    <td>
                        <?php if (!empty($product['product_image'])): ?>
                            <img
                                src="../../images/products/<?php echo rawurlencode(basename($product['product_image'])); ?>"
                                alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                                style="width:60px;height:60px;object-fit:contain;"
                            >
                        <?php endif; ?>
                    </td>

                    <td><?php echo htmlspecialchars($product['product_title']); ?></td>

                    <td><?php echo htmlspecialchars((string) $product['product_price']); ?></td>

                    <td><?php echo htmlspecialchars($product['cat_name'] ?? ''); ?></td>

                    <td><?php echo htmlspecialchars($product['brand_name'] ?? ''); ?></td>

                    <td>
                        <a href="product.php?edit_id=<?php echo (int) $product['product_id']; ?>">
                            Edit
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

<script>
document.getElementById('product_image')?.addEventListener('change', function () {
    const file = this.files[0];
    const error = document.getElementById('image_error');
    const allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp'
    ];

    error.textContent = '';

    if (!file) {
        return;
    }

    if (!allowedTypes.includes(file.type)) {
        error.textContent = 'Choose a JPEG, PNG, GIF, or WebP image.';
        this.value = '';
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        error.textContent = 'The image must be no larger than 5 MB.';
        this.value = '';
    }
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>