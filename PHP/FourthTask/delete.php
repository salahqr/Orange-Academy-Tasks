<?php
session_start();
require_once "user.php";
require_once "category.php";
require_once "product.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth.php?mode=login");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit;
}

$id   = (int)($_POST["id"] ?? 0);
$type = $_POST["type"] ?? "";

if ($id > 0) {
    switch ($type) {
        case "user":
          (new User())->deleteUser($id);
            break;
        case "category":
            (new Category())->deleteCategory($id);
            break;
        case "product":
            (new Product())->deleteProduct($id);
            break;
    }
}

header("Location: dashboard.php");
exit;