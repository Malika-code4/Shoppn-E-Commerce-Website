<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('views/admin/brand.php');
}

$brand_name = strip_tags(trim($_POST['brand_name'] ?? ''));

if ($brand_name === '') {
    $_SESSION['error'] = 'Brand name is required.';
    redirect('views/admin/brand.php');
}

$controller = new ProductController();

if ($controller->addBrand($brand_name)) {
    $_SESSION['success'] = 'Brand added.';
} else {
    $_SESSION['error'] = 'Failed to add brand.';
}

redirect('views/admin/brand.php');
