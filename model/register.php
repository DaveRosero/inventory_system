<?php
require_once 'conn.php';

class Register
{
  private $conn;
  public function __construct()
  {
    $this->conn = database();
  }

  public function register($user, $pw)
  {
    $pw = password_hash($pw, PASSWORD_DEFAULT);
    $stmt = $this->conn->prepare("INSERT INTO users (user, pw) VALUES (:user,:pw)");
    $stmt->execute([
      'user' => $user,
      'pw' => $pw
    ]);
    return $this->conn->lastInsertId();
  }

  public function userTaken($user)
  {
    $stmt = $this->conn->prepare("SELECT 1 FROM users WHERE user = :user LIMIT 1");
    $stmt->execute(['user' => $user]);
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
  }
}
?>