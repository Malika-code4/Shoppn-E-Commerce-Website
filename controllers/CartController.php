<?php

require_once __DIR__ . '/../classes/CartClass.php';

class CartController
{
    private $cart;

    public function __construct()
    {
        $this->cart = new CartClass();
    }

    public function getCart($customer_id)
    {
        if (!is_numeric($customer_id)) {
            return [];
        }

        return $this->cart->getCart((int) $customer_id);
    }

    public function addToCart($customer_id, $product_id, $qty)
    {
        if (
            !is_numeric($customer_id) ||
            !is_numeric($product_id) ||
            !is_numeric($qty)
        ) {
            return false;
        }

        $qty = (int) $qty;

        if ($qty < 1) {
            return false;
        }

        return $this->cart->addToCart(
            (int) $customer_id,
            (int) $product_id,
            $qty
        );
    }

    public function removeFromCart($cart_id)
    {
        if (!is_numeric($cart_id)) {
            return false;
        }

        return $this->cart->removeFromCart((int) $cart_id);
    }
}