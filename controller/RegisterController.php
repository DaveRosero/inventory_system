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
            $json = [];

            if (empty($user) || empty($pw) || empty($pw2)) {
                http_response_code(400);
                $json['error'] = 'All fields are required.';
                echo $json['error'];
                exit();
            }

            if ($pw !== $pw2) {
                http_response_code(400);
                $json['error'] = 'Passwords does not match.';
                echo $json['error'];
                exit();
            }

            if (strlen($pw) < 8) {
                http_response_code(400);
                $json['error'] = 'Password must be at least 8 charaters long.';
                echo $json['error'];
                exit();
            }

            $this->model->register($user, $pw);
        } else {
            $this->methodNotAllowed();
        }
    }
    public function methodNotAllowed()
    {
        http_response_code(405);
        echo 'Method Not Allowed';
        exit();
    }
}

$controller = new RegisterController();
$controller->handleRequest();
?>