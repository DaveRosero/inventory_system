<?php
require_once '../model/product.php';

class ProductController
{
    private $model;
    public function __construct()
    {
        $this->model = new Product();
    }
    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $product = $_POST['product'] ?? '';
            $description = $_POST['description'] ?? '';
            $stock = $_POST[''] ?? 0;

            if (empty($product)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Product name is required.'
                ]);
                return;
            }

            if ($this->model->productExists($product)){

            }
        } else {
            $this->methodNotAllowed();
        }
    }
    public function methodNotAllowed()
    {
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode([
            'message' => 'Method Not Allowed'
        ]);
        exit();
    }
}

$controller = new ProductController();
$controller->handleRequest();
?>