<?php

require_once __DIR__ . "/../db.php";
class User {
    private PDO $conn;

    public function __construct() { 
        global $conn;
        $this->conn = $conn;        
    }

    public function createUser( string $name, string $email, string $password ): array {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt->execute([ $name, $email, password_hash($password, PASSWORD_DEFAULT) ])) {
            return [];
        }

        $id = $this->conn->lastInsertId();
        $stmt = $this->conn->prepare("SELECT id, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? [$user['id'], $user['role']] : [];
    }

    public function loginUser(string $email, string $password): ?array {
        $sql = "SELECT id,role, password FROM users WHERE email = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        return [$user['id'], $user['role']];
    }

        public function ReturnUsers() {
            $sql = "SELECT id, username, email FROM users";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    public function findUser(int $id) {
        $sql = "SELECT id, username, email FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
