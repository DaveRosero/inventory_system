<?php
require_once 'conn.php';

class Login
{
    private $conn;
    public function __construct()
    {
        $this->conn = database();
    }

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

    public function userExists($user)
    {
        $stmt = $this->conn->prepare("SELECT user FROM users WHERE user = :user LIMIT 1");
        $stmt->execute(['user' => $user]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}
?>