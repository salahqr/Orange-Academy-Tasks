<?php
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit;
}

$id          = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$productname = trim($_POST['productname'] ?? '');
$description = trim($_POST['description'] ?? '');
$price       = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
$quantity    = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

if ($productname === '' || strlen($productname) > 30) {
    die("Invalid product name.");
}
if (strlen($description) > 255) {
    die("Description is too long.");
}
if ($price === false || $price === null || $price < 0) {
    die("Invalid price.");
}
if ($quantity === false || $quantity === null || $quantity < 0) {
    die("Invalid quantity.");
}

if ($id) {
    // UPDATE
    $stmt = $conn->prepare(
        "UPDATE products SET productname = ?, description = ?, Price = ?, Quantity = ? WHERE id = ?"
    );
    $stmt->bind_param("ssdii", $productname, $description, $price, $quantity, $id);
} else {
    // INSERT
    $stmt = $conn->prepare(
        "INSERT INTO products (productname, description, Price, Quantity, created_at)
         VALUES (?, ?, ?, ?, CURDATE())"
    );
    $stmt->bind_param("ssdi", $productname, $description, $price, $quantity);
}

$stmt->execute();

header("Location: dashboard.php");
exit;