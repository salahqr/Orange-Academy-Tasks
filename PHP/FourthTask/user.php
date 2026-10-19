<?php

require_once "db.php";

class User
{
    private PDO $conn;

    public function __construct() { 
        global $conn;
        $this->conn = $conn;        
    }

    public function createUser(
        string $name,
        string $email,
        string $password
    ): void {
        $sql = "INSERT INTO users (username, email, password)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function loginUser(string $email, string $password): ?int
    {
        $sql = "SELECT id, password FROM users WHERE email = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        return (int) $user['id'];
    }

        public function ReturnUsers() {
            $sql = "SELECT id, username, email FROM users";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    public function findUser(int $id)
    {
        $sql = "SELECT id, username, email FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser(
        int $id,
        string $username,
        string $email,
        string $password
    ): bool {
  $sql = "UPDATE users
            SET username = ?, email = ?, password = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        $username,
        $email,
        password_hash($password, PASSWORD_DEFAULT),
        $id
        ]);
    }

    public function deleteUser(int $id)
    {
        $sql = "DELETE FROM users WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id]);
    }
}
