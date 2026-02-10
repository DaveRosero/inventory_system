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

    public function productExists ($product) {
        $stmt = $this->conn->prepare("SELECT product FROM products WHERE product = :product LIMIT 1");
        $stmt->execute(['product' => $product]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}