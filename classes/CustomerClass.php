<?php

require_once __DIR__ . '/../core/core.php';

class CustomerClass extends Database
{
    public function emailExists($email)
    {
        $sql = "SELECT customer_email
                FROM customer
                WHERE customer_email = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function addCustomer(
        $name,
        $email,
        $pass,
        $country,
        $city,
        $contact
    ) {
        $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

        $sql = "INSERT INTO customer
                (
                    customer_name,
                    customer_email,
                    customer_pass,
                    customer_country,
                    customer_city,
                    customer_contact
                )
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param(
            "ssssss",
            $name,
            $email,
            $hashedPassword,
            $country,
            $city,
            $contact
        );

        return $stmt->execute();
    }

    public function getCustomerByEmail($email)
    {
        $sql = "SELECT *
                FROM customer
                WHERE customer_email = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function login($email, $pass)
    {
        $customer = $this->getCustomerByEmail($email);

        if (!$customer) {
            return false;
        }

        if (!password_verify($pass, $customer['customer_pass'])) {
            return false;
        }

        return $customer;
    }
}