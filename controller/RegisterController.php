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
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                break;
            case 'POST':
                $user = $data['user'] ?? '';
                $pw = $data['pw'] ?? '';
                $pw2 = $data['pw2'] ?? '';
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
                    echo json_encode([
                        'success' => false,
                        'errors' => $errors
                    ]);
                    return;
                }

                $id = $this->model->register($user, $pw);
                echo json_encode([
                    'success' => true,
                    'message' => 'New user registered with ID: ' . $id
                ]);
                break;
            case 'PUT':
                break;
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

$controller = new RegisterController();
$controller->handleRequest();
?>