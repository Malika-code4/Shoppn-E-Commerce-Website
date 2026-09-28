<?php

require_once __DIR__ . '/../core/core.php';

class ProductClass extends Database
{
    public function addBrand($name)
    {
        $sql = "INSERT INTO brands (brand_name)
                VALUES (?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("s", $name);

        return $stmt->execute();
    }

    public function getAllBrands()
    {
        $sql = "SELECT *
                FROM brands
                ORDER BY brand_name ASC";

        $result = $this->conn->query($sql);

        if (!$result) {
            error_log($this->conn->error);
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBrandById($id)
    {
        $sql = "SELECT *
                FROM brands
                WHERE brand_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function updateBrand($id, $name)
    {
        $sql = "UPDATE brands
                SET brand_name = ?
                WHERE brand_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("si", $name, $id);

        return $stmt->execute();
    }


    public function addCategory($name)
    {
        $sql = "INSERT INTO categories (cat_name)
                VALUES (?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("s", $name);

        return $stmt->execute();
    }

    public function getAllCategories()
    {
        $sql = "SELECT *
                FROM categories
                ORDER BY cat_name ASC";

        $result = $this->conn->query($sql);

        if (!$result) {
            error_log($this->conn->error);
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById($id)
    {
        $sql = "SELECT *
                FROM categories
                WHERE cat_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function updateCategory($id, $name)
    {
        $sql = "UPDATE categories
                SET cat_name = ?
                WHERE cat_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("si", $name, $id);

        return $stmt->execute();
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
        $sql = "INSERT INTO products
                (
                    product_cat,
                    product_brand,
                    product_title,
                    product_price,
                    product_desc,
                    product_image,
                    product_keywords
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param(
            "iisdsss",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords
        );

        return $stmt->execute();
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
        $sql = "UPDATE products
                SET
                    product_cat = ?,
                    product_brand = ?,
                    product_title = ?,
                    product_price = ?,
                    product_desc = ?,
                    product_image = ?,
                    product_keywords = ?
                WHERE product_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param(
            "iisdsssi",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords,
            $id
        );

        return $stmt->execute();
    }

    public function getProductById($id)
    {
        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_name
                FROM products p
                INNER JOIN categories c
                    ON p.product_cat = c.cat_id
                INNER JOIN brands b
                    ON p.product_brand = b.brand_id
                WHERE p.product_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getFeaturedProducts($limit = 6)
    {
        $limit = (int) $limit;

        if ($limit < 1) {
            $limit = 6;
        }

        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_name
                FROM products p
                INNER JOIN categories c
                    ON p.product_cat = c.cat_id
                INNER JOIN brands b
                    ON p.product_brand = b.brand_id
                ORDER BY RAND()
                LIMIT $limit";

        $result = $this->conn->query($sql);

        if (!$result) {
            error_log($this->conn->error);
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductsByCategory($cat_id)
    {
        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_name
                FROM products p
                INNER JOIN categories c
                    ON p.product_cat = c.cat_id
                INNER JOIN brands b
                    ON p.product_brand = b.brand_id
                WHERE p.product_cat = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return [];
        }

        $stmt->bind_param("i", $cat_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductsByBrand($brand_id)
    {
        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_name
                FROM products p
                INNER JOIN categories c
                    ON p.product_cat = c.cat_id
                INNER JOIN brands b
                    ON p.product_brand = b.brand_id
                WHERE p.product_brand = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return [];
        }

        $stmt->bind_param("i", $brand_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function searchProducts($query)
    {
        $search = "%" . $query . "%";

        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_name
                FROM products p
                INNER JOIN categories c
                    ON p.product_cat = c.cat_id
                INNER JOIN brands b
                    ON p.product_brand = b.brand_id
                WHERE p.product_title LIKE ?
                   OR p.product_desc LIKE ?
                   OR p.product_keywords LIKE ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log($this->conn->error);
            return [];
        }

        $stmt->bind_param(
            "sss",
            $search,
            $search,
            $search
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}