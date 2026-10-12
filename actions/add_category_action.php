<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('views/admin/category.php');
}

$category_name = strip_tags(
    trim($_POST['cat_name'] ?? '')
);

if ($category_name === '') {
    $_SESSION['error'] = 'Category name is required.';
    redirect('views/admin/category.php');
}

$controller = new ProductController();

if ($controller->addCategory($category_name)) {

    $_SESSION['success'] = 'Category added.';

} else {

    $_SESSION['error'] = 'Failed to add category.';
}

redirect('views/admin/category.php');