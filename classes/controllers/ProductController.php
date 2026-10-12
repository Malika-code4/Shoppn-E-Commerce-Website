<?php

require_once __DIR__ . '/../../classes/ProductClass.php';

class ProductController
{
    private $product;

    public function __construct()
    {
        $this->product = new ProductClass();
    }

    public function addBrand($name)
    {
        return $this->product->addBrand($name);
    }

    public function getAllBrands()
    {
        return $this->product->getAllBrands();
    }

    public function getBrand($id)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            return false;
        }

        return $this->product->getBrandById((int) $id);
    }

    // Alias used by views/admin/brand.php
    public function getBrandById($id)
    {
        return $this->getBrand($id);
    }

    public function updateBrand($id, $name)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            return false;
        }

        return $this->product->updateBrand(
            (int) $id,
            $name
        );
    }


    public function addCategory($name)
    {
        return $this->product->addCategory($name);
    }

    public function getAllCategories()
    {
        return $this->product->getAllCategories();
    }

    public function getCategory($id)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            return false;
        }

        return $this->product->getCategoryById((int) $id);
    }

    // Alias used by views/admin/category.php
    public function getCategoryById($id)
    {
        return $this->getCategory($id);
    }

    public function updateCategory($id, $name)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            return false;
        }

        return $this->product->updateCategory(
            (int) $id,
            $name
        );
    }


    public function addProduct(
        $cat,
        $brand,
        $title,
        $price,
        $desc,
        $image_filename,
        $keywords
    ) {
        if (
            !is_numeric($cat) ||
            !is_numeric($brand) ||
            !is_numeric($price)
        ) {
            return false;
        }

        return $this->product->addProduct(
            (int) $cat,
            (int) $brand,
            $title,
            (float) $price,
            $desc,
            $image_filename,
            $keywords
        );
    }

    public function updateProduct(
        $id,
        $cat,
        $brand,
        $title,
        $price,
        $desc,
        $image_filename,
        $keywords
    ) {
        if (
            !is_numeric($id) ||
            !is_numeric($cat) ||
            !is_numeric($brand) ||
            !is_numeric($price)
        ) {
            return false;
        }

        return $this->product->updateProduct(
            (int) $id,
            (int) $cat,
            (int) $brand,
            $title,
            (float) $price,
            $desc,
            $image_filename,
            $keywords
        );
    }

    public function getProduct($id)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            return false;
        }

        return $this->product->getProductById((int) $id);
    }

    public function getFeaturedProducts($limit = 6)
    {
        return $this->product->getFeaturedProducts($limit);
    }

    public function getProductsByCategory($cat_id)
    {
        if (!is_numeric($cat_id) || (int) $cat_id <= 0) {
            return [];
        }

        return $this->product->getProductsByCategory(
            (int) $cat_id
        );
    }

    public function getProductsByBrand($brand_id)
    {
        if (!is_numeric($brand_id) || (int) $brand_id <= 0) {
            return [];
        }

        return $this->product->getProductsByBrand(
            (int) $brand_id
        );
    }

    public function searchProducts($query)
    {
        $query = trim($query);

        if ($query === '') {
            return [];
        }

        return $this->product->searchProducts($query);
    }
}