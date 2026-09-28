<?php

require_once __DIR__ . '/../classes/CustomerClass.php';

class CustomerController
{
    private $customer;

    public function __construct()
    {
        $this->customer = new CustomerClass();
    }

    public function register($data)
    {
        $name = $data['customer_name'] ?? '';
        $email = $data['customer_email'] ?? '';
        $password = $data['customer_pass'] ?? '';
        $country = $data['customer_country'] ?? '';
        $city = $data['customer_city'] ?? '';
        $contact = $data['customer_contact'] ?? '';

        if (
            empty($name) ||
            empty($email) ||
            empty($password) ||
            empty($country) ||
            empty($city) ||
            empty($contact)
        ) {
            return [
                'success' => false,
                'error' => 'All required fields must be completed.'
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'error' => 'Please enter a valid email address.'
            ];
        }

        if (strlen($name) > 100) {
            return [
                'success' => false,
                'error' => 'Name is too long.'
            ];
        }

        if (strlen($email) > 50) {
            return [
                'success' => false,
                'error' => 'Email is too long.'
            ];
        }

        if (strlen($country) > 30) {
            return [
                'success' => false,
                'error' => 'Country name is too long.'
            ];
        }

        if (strlen($city) > 30) {
            return [
                'success' => false,
                'error' => 'City name is too long.'
            ];
        }

        if (strlen($contact) > 15) {
            return [
                'success' => false,
                'error' => 'Contact number is too long.'
            ];
        }

        if ($this->customer->emailExists($email)) {
            return [
                'success' => false,
                'error' => 'Email already registered.'
            ];
        }

        $success = $this->customer->addCustomer(
            $name,
            $email,
            $password,
            $country,
            $city,
            $contact
        );

        if (!$success) {
            return [
                'success' => false,
                'error' => 'Registration failed. Please try again.'
            ];
        }

        $customer = $this->customer->getCustomerByEmail($email);

        return [
            'success' => true,
            'customer' => $customer
        ];
    }

    public function login($email, $password)
    {
        $customer = $this->customer->login($email, $password);

        if (!$customer) {
            return [
                'success' => false,
                'error' => 'Invalid email or password.'
            ];
        }

        return [
            'success' => true,
            'customer' => $customer
        ];
    }
}