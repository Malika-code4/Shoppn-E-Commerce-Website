<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('views/login.php');
}

// Accept either common field naming; passwords are never trimmed
$email    = trim($_POST['email'] ?? $_POST['customer_email'] ?? '');
$password = $_POST['password'] ?? $_POST['customer_pass'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['error'] = 'Email and password are required.';
    redirect('views/login.php');
}

$controller = new CustomerController();
$result = $controller->login($email, $password);

if (!$result['success']) {
    $_SESSION['error'] = $result['error'];
    redirect('views/login.php');
}

$customer = $result['customer'];

session_regenerate_id(true);

$_SESSION['customer_id']    = (int) $customer['customer_id'];
$_SESSION['customer_name']  = $customer['customer_name'];
$_SESSION['customer_email'] = $customer['customer_email'];
$_SESSION['user_role']      = (int) $customer['user_role'];

// Everyone lands on the home page; admins see the Brands/Categories buttons there
redirect('index.php');