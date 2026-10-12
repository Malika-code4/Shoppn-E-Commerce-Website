<?php

require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$controller = new ProductController();

$brands = $controller->getAllBrands();

$edit_id = filter_var(
    $_GET['edit_id'] ?? '',
    FILTER_VALIDATE_INT
);

$edit_brand = null;

if ($edit_id !== false && $edit_id > 0) {
    $edit_brand = $controller->getBrandById($edit_id);
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';

unset($_SESSION['success']);
unset($_SESSION['error']);

require_once __DIR__ . '/../layout/header.php';

?>

<h2>Brand Management</h2>

<?php if ($success): ?>

    <p>
        <?php echo htmlspecialchars($success); ?>
    </p>

<?php endif; ?>


<?php if ($error): ?>

    <p>
        <?php echo htmlspecialchars($error); ?>
    </p>

<?php endif; ?>


<!-- ADD / EDIT BRAND FORM -->

<?php if ($edit_brand): ?>

    <h3>Edit Brand</h3>

    <form
        method="POST"
        action="../../actions/update_brand_action.php"
    >

        <input
            type="hidden"
            name="brand_id"
            value="<?php echo (int) $edit_brand['brand_id']; ?>"
        >

        <label for="brand_name">
            Brand Name
        </label>

        <input
            type="text"
            id="brand_name"
            name="brand_name"
            value="<?php echo htmlspecialchars($edit_brand['brand_name']); ?>"
            required
        >

        <button type="submit">
            Update Brand
        </button>

        <a href="brand.php">
            Cancel
        </a>

    </form>

<?php else: ?>

    <h3>Add Brand</h3>

    <form
        method="POST"
        action="../../actions/add_brand_action.php"
    >

        <label for="brand_name">
            Brand Name
        </label>

        <input
            type="text"
            id="brand_name"
            name="brand_name"
            required
        >

        <button type="submit">
            Add Brand
        </button>

    </form>

<?php endif; ?>


<!-- BRAND TABLE -->

<h3>All Brands</h3>

<table border="1">

    <thead>

        <tr>
            <th>ID</th>
            <th>Brand Name</th>
            <th>Action</th>
        </tr>

    </thead>

    <tbody>

        <?php if (empty($brands)): ?>

            <tr>
                <td colspan="3">
                    No brands found.
                </td>
            </tr>

        <?php else: ?>

            <?php foreach ($brands as $brand): ?>

                <tr>

                    <td>
                        <?php echo (int) $brand['brand_id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($brand['brand_name']); ?>
                    </td>

                    <td>

                        <a
                            href="brand.php?edit_id=<?php echo (int) $brand['brand_id']; ?>"
                        >
                            Edit
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    </tbody>

</table>

<?php

require_once __DIR__ . '/../layout/footer.php';

?>