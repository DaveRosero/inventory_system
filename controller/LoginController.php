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
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                break;
            case 'POST':
                $user = $data['user'] ?? '';
                $pw = $data['pw'] ?? '';

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
                break;
            case 'PUT':
                break;
            case 'PATCH':
                $user = $data['user'] ?? '';
                $old_pw = $data['old_pw'] ?? '';
                $new_pw = $data['new_pw'] ?? '';
                $new_pw_confirm = $data['new_pw_confirm'] ?? '';

                if (empty($user)||empty($old_pw)||empty($new_pw)||empty($new_pw_confirm)){
                    echo json_encode([
                        'success' => false,
                        'message' => 'All fields are required.'
                    ]);
                    return;
                }

                $errors = [];

                if ($new_pw !== $new_pw_confirm) {
                    $errors[] = 'Passwords does not match.';
                }

                if (strlen($new_pw) < 8) {
                    $errors[] = 'Password must be at least 8 charaters long.';
                }

                if (!empty($errors)) {
                    echo json_encode([
                        'success' => false,
                        'errors' => $errors
                    ]);
                    return;
                }

                if ($this->model->userExists($user) && $this->model->login($user, $old_pw) !== false) {
                    if ($this->model->updatePassword($user, $new_pw)) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Password has been changed.'
                        ]);
                        return;
                    } else {
                        echo json_encode([
                            'success' => false, // Old Password and New Password is the same, no changes on database
                            'message' => 'Password has been changed.'
                        ]);
                        return;
                    }
                } else {
                    echo json_encode([
                        'success' => false, // New Password is Correct but Invalid username and password, no changes on database
                        'message' => 'Password has been changed.' // This should be displayed to the user even tho the user and old password is incorrect for security purposes
                    ]);
                    return;
                }
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

$controller = new LoginController();
$controller->handleRequest();
?>