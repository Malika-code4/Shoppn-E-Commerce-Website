<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('views/register.php');
}

$data = [
    'customer_name'    => strip_tags(trim($_POST['customer_name'] ?? '')),
    'customer_email'   => trim($_POST['customer_email'] ?? ''),
    'customer_pass'    => $_POST['customer_pass'] ?? '',
    'customer_country' => strip_tags(trim($_POST['customer_country'] ?? '')),
    'customer_city'    => strip_tags(trim($_POST['customer_city'] ?? '')),
    'customer_contact' => strip_tags(trim($_POST['customer_contact'] ?? '')),
];

$controller = new CustomerController();
$result = $controller->register($data);

if (!$result['success']) {
    $_SESSION['error'] = $result['error'];
    redirect('views/register.php');
}

// New accounts are always regular customers (user_role defaults to 2 in the DB)
$_SESSION['success'] = 'Account created successfully. Please log in.';
redirect('views/login.php');