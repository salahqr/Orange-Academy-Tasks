<?php
require_once "db.php";

class Product
{
    private PDO $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;    
    }

public function returnProducts(): array
{
    $sql = "SELECT p.id, p.productname, p.description, p.price, p.quantity, p.imageUrl,
                   c.name AS category
            FROM products p
            JOIN categories c ON c.id = p.category_id
            ORDER BY p.id DESC";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function findProduct(int $id)
{
    $stmt = $this->conn->prepare(
        "SELECT id, productname, description, price, quantity, category_id
         FROM products WHERE id = ?"
    );
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function createProduct(string $name, ?string $description, float $price, int $quantity, int $categoryId, string $imageUrl): bool
{
    $stmt = $this->conn->prepare(
        "INSERT INTO products (productname, description, price, quantity, category_id, imageUrl)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    return $stmt->execute([$name, $description, $price, $quantity, $categoryId, $imageUrl]);
}

public function updateProduct(int $id, string $name, ?string $description, float $price, int $quantity, int $categoryId, string $imageUrl): bool
{
    $stmt = $this->conn->prepare(
        "UPDATE products
         SET productname = ?, description = ?, price = ?, quantity = ?, category_id = ?, imageUrl = ?
         WHERE id = ?"
    );
    return $stmt->execute([$name, $description, $price, $quantity, $categoryId, $id, $imageUrl]);
}
    public function deleteProduct(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
//imageUrl