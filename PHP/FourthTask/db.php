<?php
declare(strict_types=1);

try {
    $dbhost = 'localhost';
    $dbname = 'dash';
    $dbuser = 'root';
    $dbpass = '';

    $conn = new PDO(
        "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4",
        $dbuser,
        $dbpass,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $user = "
        CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            email VARCHAR(50) UNIQUE,
            password VARCHAR(255) NOT NULL
        )
    ";

    $categories = "
        CREATE TABLE IF NOT EXISTS categories (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL UNIQUE
        )
    ";

    $product = "
        CREATE TABLE IF NOT EXISTS products (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            category_id INT UNSIGNED NOT NULL,
            productname VARCHAR(30) NOT NULL,
            description VARCHAR(255),
            price DECIMAL(10,2) NOT NULL,
            quantity INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (category_id)
                REFERENCES categories(id)
                ON DELETE RESTRICT
        )
    ";

    $conn->exec($user);
    $conn->exec($categories);
    $conn->exec($product);


} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
