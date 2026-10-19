<?php
require_once "db.php"; 

class Category
{
    private PDO $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;    
    }

    public function returnCategories(): array
    {
        return $this->conn->query("SELECT id, name FROM categories ORDER BY name")
                          ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findCategory(int $id)
    {
        $stmt = $this->conn->prepare("SELECT id, name FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCategory(string $name): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function updateCategory(int $id, string $name): bool
    {
        $stmt = $this->conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public function deleteCategory(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}