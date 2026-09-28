<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/login.php');
}

$email = filter_var(
    trim($_POST['customer_email'] ?? ''),
    FILTER_SANITIZE_EMAIL
);

$password = $_POST['customer_pass'] ?? '';

$controller = new CustomerController();

$result = $controller->login($email, $password);

if ($result['success']) {
    $customer = $result['customer'];

    $_SESSION['customer_id'] = $customer['customer_id'];
    $_SESSION['customer_name'] = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role'] = (int) $customer['user_role'];

    redirect('../index.php');
}

$_SESSION['error'] = $result['error'];

redirect('../views/login.php');