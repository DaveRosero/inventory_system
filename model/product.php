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

    }

    public function productExists ($product) {
        $stmt = $this->conn->prepare("SELECT product FROM products WHERE product = :product LIMIT 1");
        $stmt->execute(['product' => $product]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}