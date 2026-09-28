<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/register.php');
}

$name = strip_tags(trim($_POST['customer_name'] ?? ''));
$email = filter_var(
    trim($_POST['customer_email'] ?? ''),
    FILTER_SANITIZE_EMAIL
);
$password = $_POST['customer_pass'] ?? '';
$country = strip_tags(trim($_POST['customer_country'] ?? ''));
$city = strip_tags(trim($_POST['customer_city'] ?? ''));
$contact = strip_tags(trim($_POST['customer_contact'] ?? ''));

$data = [
    'customer_name' => $name,
    'customer_email' => $email,
    'customer_pass' => $password,
    'customer_country' => $country,
    'customer_city' => $city,
    'customer_contact' => $contact
];

$controller = new CustomerController();

$result = $controller->register($data);

if ($result['success']) {
    $customer = $result['customer'];

    $_SESSION['customer_id'] = $customer['customer_id'];
    $_SESSION['customer_name'] = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role'] = (int) $customer['user_role'];

    redirect('../views/account/my_account.php');
}

$_SESSION['error'] = $result['error'];

redirect('../views/register.php');