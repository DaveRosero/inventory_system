<?php
require_once '../model/login.php';

class LoginController
{
    private $model;
    public function __construct()
    {
        $this->model = new Login();
    }
    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $user = $_POST['user'] ?? '';
            $pw = $_POST['pw'] ?? '';

            if (empty($user) || empty($pw)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'All fields are required.'
                ]);
                return;
            }

            if (!$this->model->userExists($user)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid username or password.'
                ]);
                return;
            }

            $user = $this->model->login($user, $pw);

            if ($user === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid username or password.'
                ]);
                return;
            }

            echo json_encode([
                'success' => true,
                'message' => 'You are logged in as ' . strtoupper($user)
            ]);
            return;
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

$controller = new LoginController();
$controller->handleRequest();
?>