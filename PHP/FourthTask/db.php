<?php
declare(strict_types=1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL
)";

$product = "CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    productname VARCHAR(30) NOT NULL,
    description VARCHAR(255),
    Price DECIMAL(10,5),
    Quantity INT,
    created_at DATE
)";

if (!$conn->query($user)) {
    die("User table error: " . $conn->error);
}

if (!$conn->query($product)) {
    die("Product table error: " . $conn->error);
}


//$conn->close();
?>
