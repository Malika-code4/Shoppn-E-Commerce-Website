<?php

require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$controller = new ProductController();

$categories = $controller->getAllCategories();

$edit_id = filter_var(
    $_GET['edit_id'] ?? '',
    FILTER_VALIDATE_INT
);

$edit_category = null;

if ($edit_id !== false && $edit_id > 0) {
    $edit_category = $controller->getCategoryById($edit_id);
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';

unset($_SESSION['success']);
unset($_SESSION['error']);

require_once __DIR__ . '/../layout/header.php';

?>

<h2>Category Management</h2>

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


<!-- ADD / EDIT CATEGORY -->

<?php if ($edit_category): ?>

    <h3>Edit Category</h3>

    <form
        method="POST"
        action="../../actions/update_category_action.php"
    >

        <input
            type="hidden"
            name="cat_id"
            value="<?php echo (int) $edit_category['cat_id']; ?>"
        >

        <label for="cat_name">
            Category Name
        </label>

        <input
            type="text"
            id="cat_name"
            name="cat_name"
            value="<?php echo htmlspecialchars($edit_category['cat_name']); ?>"
            required
        >

        <button type="submit">
            Update Category
        </button>

        <a href="category.php">
            Cancel
        </a>

    </form>

<?php else: ?>

    <h3>Add Category</h3>

    <form
        method="POST"
        action="../../actions/add_category_action.php"
    >

        <label for="cat_name">
            Category Name
        </label>

        <input
            type="text"
            id="cat_name"
            name="cat_name"
            required
        >

        <button type="submit">
            Add Category
        </button>

    </form>

<?php endif; ?>


<!-- CATEGORY TABLE -->

<h3>All Categories</h3>

<table border="1">

    <thead>

        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Action</th>
        </tr>

    </thead>

    <tbody>

        <?php if (empty($categories)): ?>

            <tr>
                <td colspan="3">
                    No categories found.
                </td>
            </tr>

        <?php else: ?>

            <?php foreach ($categories as $category): ?>

                <tr>

                    <td>
                        <?php echo (int) $category['cat_id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($category['cat_name']); ?>
                    </td>

                    <td>

                        <a
                            href="category.php?edit_id=<?php echo (int) $category['cat_id']; ?>"
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