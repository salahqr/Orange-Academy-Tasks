<?php
declare(strict_types=1);
require_once __DIR__ . "/../db.php";

class Tour
{
    private PDO $conn;

    public function __construct() { 
        global $conn;
        $this->conn = $conn;        
    }

    public function create(
        string $name,
        string $museumName,
        string $description,
        float $price,
        string $image
    ): int {
        $sql = "INSERT INTO tours (name, museum_name, description, price, image)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$name, $museumName, $description, $price, $image]);

        return (int) $this->conn->lastInsertId();
    }

    public function update(
        int $id,
        string $name,
        string $museumName,
        string $description,
        float $price,
        string $image
    ): bool {
        $sql = "UPDATE tours
                SET name = ?, museum_name = ?, description = ?, price = ?, image = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$name, $museumName, $description, $price, $image, $id]);
    }

    public function delete(int $id): bool
    {
        // tour_sessions and reservations are removed automatically
        // by ON DELETE CASCADE in the schema.
        $stmt = $this->conn->prepare("DELETE FROM tours WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    public function find(int $id): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT id, name, museum_name, description, price, image
             FROM tours WHERE id = ?"
        );
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getAll(): array
    {
        $stmt = $this->conn->query(
            "SELECT id, name, museum_name, description, price, image
             FROM tours ORDER BY name"
        );

        return $stmt->fetchAll();
    }
}