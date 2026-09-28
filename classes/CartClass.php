<?php

require_once __DIR__ . '/../core/core.php';

class CartClass extends Database
{
    public function getCart($customer_id)
    {
        $sql = "SELECT * FROM cart WHERE customer_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return [];
        }

        $stmt->bind_param("i", $customer_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addToCart($customer_id, $product_id, $qty)
    {
        $sql = "INSERT INTO cart (customer_id, product_id, qty)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("iii", $customer_id, $product_id, $qty);

        return $stmt->execute();
    }

    public function removeFromCart($cart_id)
    {
        $sql = "DELETE FROM cart WHERE cart_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $cart_id);

        return $stmt->execute();
    }
}