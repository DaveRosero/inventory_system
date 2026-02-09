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
    $json = [];
    if ($this->isUserTaken($user)) {
      $json['error'] = 'Username is already taken.';
      echo $json['error'];
      exit();
    }
    $pw = password_hash($pw, PASSWORD_DEFAULT);
    $stmt = $this->conn->prepare("INSERT INTO user (user, pw) VALUES (:user,:pw)");
    $stmt->execute([
      'user' => $user,
      'pw' => $pw
    ]);
    echo "New user registered with ID: " . $this->conn->lastInsertId();
  }

  public function isUserTaken($user)
  {
    $stmt = $this->conn->prepare("SELECT 1 FROM user WHERE user = :user");
    $stmt->execute(['user' => $user]);
    return $stmt->fetch() !== false;
  }
}
?>