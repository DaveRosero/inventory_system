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
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                break;
            case 'POST':
                $product = $data['product'] ?? '';
                $description = $data['description'] ?? '';
                $stock = $data['stock'] ?? 0;

                if (empty($product)) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Product field is required.'
                    ]);
                    return;
                }

                if ($this->model->productExists($product)) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Product already exist.'
                    ]);
                    return;
                }

                $id = $this->model->createProduct($product, $description, $stock);
                echo json_encode([
                    'success' => true,
                    'message' => 'New Product added with ID: '.$id
                ]);
                return;
            case 'PUT':
                $id = $data['id'] ?? null; // This is a hidden data type when user submits the form and should dynamically change to match the product that the user wants to update
                if ($id === null) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Product ID is required.' // Product ID is expected to be sent in json via AJAX. This message is for developers only.
                    ]);
                    return;
                }

                $product = $data['product'] ?? '';
                $description = $data['description'] ?? '';
                $stock = $data['stock'] ?? 0;

                if (empty($product)||empty($description)||!is_numeric($stock)) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'All fields are required.'
                    ]);
                    return;
                }

                $updated_id = $this->model->updateProduct($id, $product, $description, $stock);
                echo json_encode([
                    'success' => true,
                    'message' => 'Updated product with ID: ' . $updated_id
                ]);
                return;
            case 'PATCH':
                break;
            case 'DELETE':
                break;
            default:
                $this->methodNotAllowed();
                break;
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