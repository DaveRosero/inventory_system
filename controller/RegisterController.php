<?php
require_once '../model/register.php';

class RegisterController
{
    private $model;
    public function __construct()
    {
        $this->model = new Register();
    }
    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['user'] ?? '';
            $pw = $_POST['pw'] ?? '';
            $pw2 = $_POST['pw2'] ?? '';
            $errors = [];

            if (empty($user) || empty($pw) || empty($pw2)) {
                $errors[] = 'All fields are required.';
            }

            if ($this->model->userTaken($user)) {
                $errors[] = 'Username is already taken.';
            }

            if ($pw !== $pw2) {
                $errors[] = 'Passwords does not match.';
            }

            if (strlen($pw) < 8) {
                $errors[] = 'Password must be at least 8 charaters long.';
            }

            if (!empty($errors)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'errors' => $errors
                ]);
                return;
            }

            $id = $this->model->register($user, $pw);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'New user registered with ID: ' . $id
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

$controller = new RegisterController();
$controller->handleRequest();
?>