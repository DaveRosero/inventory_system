<?php
require_once 'conn.php';

class Product
{
    private $conn;
    public function __construct()
    {
        $this->conn = database();
    }

    public function createProduct ($product, $description, $stock) {
        $stmt = $this->conn->prepare('INSERT INTO products (product, description, stock) VALUES (:product, :description, :stock)');
        $stmt->execute([
            'product' => $product,
            'description' => $description,
            'stock' => $stock
        ]);
        return $this->conn->lastInsertId();
    }

    public function updateProduct ($id, $product, $description, $stock) {
        $stmt = $this->conn->prepare("UPDATE products SET product = :product, description = :description, stock = :stock WHERE id = :id");
        $stmt->execute([
            "id" => $id,
            "product" => $product,
            "description" => $description,
            "stock" => $stock
        ]);
        return $id;
    }

    public function existsByName ($product) {
        $stmt = $this->conn->prepare("SELECT 1 FROM products WHERE product = :product LIMIT 1");
        $stmt->execute(['product' => $product]);
        return $stmt->fetchColumn() !== false;
    }

    public function existsByID ($id) {
        $stmt = $this->conn->prepare("SELECT 1 FROM products WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() !== false;
    }

    public function updateProductPartial ($id, $product = null, $description = null, $stock = null) { // Optional params for the product patch function. Product ID sent via json is required.
        $queries = [];

        if ($product !== null) {
            $queries['product'] = $this->conn->prepare("UPDATE products SET product = :product WHERE id = :id");
        }
        if ($description !== null) {
            $queries['description'] = $this->conn->prepare("UPDATE products SET description = :description WHERE id = :id");
        }
        if ($stock !== null) {
            $queries['stock'] = $this->conn->prepare("UPDATE products SET stock = :stock WHERE id = :id");
        }

        foreach ($queries as $key => $stmt) {
            switch ($key) {
                case 'product':
                    $stmt->execute(['product' => $product, 'id' => $id]);
                    break;
                case 'description':
                    $stmt->execute(['description' => $description, 'id' => $id]);
                    break;
                case 'stock':
                    $stmt->execute(['stock' => $stock, 'id' => $id]);
                    break;
            }
        }
        return $id;
    }
}