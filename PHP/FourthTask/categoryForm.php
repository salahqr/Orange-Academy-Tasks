<?php
session_start();
require_once "category.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth.php?mode=login");
    exit;
}

$category = new Category();
$error    = "";

$id  = (int)($_POST["id"] ?? $_GET["id"] ?? 0);   // 0 = creating
$row = $id ? $category->findCategory($id) : null;

if ($id && !$row) {
    exit("Category not found.");
}

$name = $row["name"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");

    if ($name === "") {
        $error = "Name is required.";
    } elseif (strlen($name) > 30) {
        $error = "Name must be 30 characters or less.";
    } else {
        try {
            $ok = $id
                ? $category->updateCategory($id, $name)
                : $category->createCategory($name);

            if ($ok) {
                header("Location: dashboard.php");
                exit;
            }
            $error = "Failed to save category.";
        } catch (PDOException $e) {
            // 23000 = duplicate name (the column is UNIQUE)
            $error = $e->getCode() === "23000"
                ? "This category already exists."
                : "Failed to save category.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? "Update" : "Add" ?> Category</title>
</head>
<body>
    <h1><?= $id ? "Update" : "Add" ?> Category</h1>

    <?php if ($error !== ""): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Name:
            <input type="text" name="name" maxlength="30"
                   value="<?= htmlspecialchars($name) ?>" required>
        </label>
        <br><br>

        <button type="submit">Save</button>
        <a href="dashboard.php">Cancel</a>
    </form>
</body>
</html>