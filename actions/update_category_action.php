<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('views/admin/category.php');
}

$category_id = filter_var(
    $_POST['cat_id'] ?? '',
    FILTER_VALIDATE_INT
);

$category_name = strip_tags(
    trim($_POST['cat_name'] ?? '')
);

if ($category_id === false || $category_id <= 0) {
    $_SESSION['error'] = 'Invalid category ID.';
    redirect('views/admin/category.php');
}

if ($category_name === '') {
    $_SESSION['error'] = 'Category name is required.';
    redirect(
        'views/admin/category.php?edit_id=' . $category_id
    );
}

$controller = new ProductController();

if ($controller->updateCategory($category_id, $category_name)) {

    $_SESSION['success'] = 'Category updated.';

} else {

    $_SESSION['error'] = 'Failed to update category.';
}

redirect('views/admin/category.php');