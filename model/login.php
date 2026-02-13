<?php
require_once 'conn.php';

class Login
{
    private $conn;
    public function __construct()
    {
        $this->conn = database();
    }

**//
string $user
string $pw
return $user username
**
    public function login($user, $pw)
    {
        $stmt = $this->conn->prepare("SELECT pw FROM users WHERE user = :user LIMIT 1");
        $stmt->execute(['user' => $user]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($pw, $row['pw'])) {
            return false;
        }

        return $user;
    }
**//
string $user
returns bool
*
    public function userExists($user)
    {
        $stmt = $this->conn->prepare("SELECT user FROM users WHERE user = :user LIMIT 1");
        $stmt->execute(['user' => $user]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

**//
string $user
string $new_pw new password hashed
return bool
*
    public function updatePassword ($user, $new_pw) {
        $old_pw = $this->getUserPassword($user);
        if (password_verify($new_pw, $old_pw['pw'])){
            return false;
        }
        $new_pw = password_hash($new_pw, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare('UPDATE users SET pw = :new_pw WHERE user = :user');
        $stmt->execute([
            'new_pw' => $new_pw,
            'user' => $user
        ]);
        return true;
    }
**//
string $user
return $stmt array
*
    public function getUserPassword ($user) {
        $stmt = $this->conn->prepare('SELECT pw FROM users WHERE user = :user LIMIT 1');
        $stmt->execute(['user' => $user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>