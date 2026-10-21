<?php
session_start();
require_once "product.php";
require_once "category.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth.php?mode=login");
    exit;
}

$product    = new Product();
$categories = (new Category())->returnCategories();
$error      = "";

$id  = (int)($_POST["id"] ?? $_GET["id"] ?? 0);   // 0 = creating
$row = $id ? $product->findProduct($id) : null;

if ($id && !$row) {
    exit("Product not found.");
}

function saveImage(array $file): string
{
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Upload failed, code ' . $file['error']);
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new Exception('File too large (max 2MB)');
    }

    // Is it a real image? getimagesize returns false for fake files
    if (getimagesize($file['tmp_name']) === false) {
        throw new Exception('This is not a real image');
    }

    // Check real MIME type
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        throw new Exception('Only JPG, PNG, WEBP allowed');
    }

    $newName = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];

    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        throw new Exception('Could not save file');
    }

    return 'uploads/' . $newName; // the path you reuse later
}

$name        = $row["productname"] ?? "";
$description = $row["description"] ?? "";
$price       = $row["price"] ?? "";
$quantity    = $row["quantity"] ?? 0;
$categoryId  = $row["category_id"] ?? "";
$image = $row["image"] ?? null;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ... read $name, $price, etc.

    if ($name === "" || $price === "" || $categoryId === "") {
        $error = "Name, price, and category are required.";
    } elseif (strlen($name) > 30) {
        $error = "Name must be 30 characters or less.";
    } elseif (!is_numeric($price) || $price < 0) {
        $error = "Price must be a positive number.";
    } elseif (filter_var($quantity, FILTER_VALIDATE_INT) === false || (int)$quantity < 0) {
        $error = "Quantity must be a whole number, 0 or more.";
    } else {
        try {
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
                $image = saveImage($_FILES['photo']);
            }

            $desc = $description === "" ? null : $description;

            $ok = $id
                ? $product->updateProduct($id, $name, $desc, (float)$price, (int)$quantity, (int)$categoryId, $image)
                : $product->createProduct($name, $desc, (float)$price, (int)$quantity, (int)$categoryId, $image);

            if ($ok) {
                header("Location: dashboard.php");
                exit;
            }
            $error = "Failed to save product.";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? "Update" : "Add" ?> Product</title>
</head>
<body>
    <h1><?= $id ? "Update" : "Add" ?> Product</h1>

    <?php if ($error !== ""): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Name:
            <input type="text" name="name" maxlength="30"
                   value="<?= htmlspecialchars($name) ?>" required>
        </label>
        <br><br>

        <label>Description:<br>
            <textarea name="description" maxlength="255" rows="3" cols="30"><?= htmlspecialchars((string)$description) ?></textarea>
        </label>
        <br><br>

        <label>Price:
            <input type="number" step="0.01" min="0" name="price"
                   value="<?= htmlspecialchars((string)$price) ?>" required>
        </label>
        <br><br>

        <label>Quantity:
            <input type="number" step="1" min="0" name="quantity"
                   value="<?= htmlspecialchars((string)$quantity) ?>" required>
        </label>
        <br><br>
  
        <label>iamge:
              <input type="file" name="fileToUpload" id="fileToUpload">
        </label>
        <br><br>

        <label>Category:
            <select name="category_id" required>
                <option value="">Select a category</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c["id"] ?>"
                        <?= (string)$categoryId === (string)$c["id"] ? "selected" : "" ?>>
                        <?= htmlspecialchars($c["name"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <br><br>

        <button type="submit">Save</button>
        <a href="dashboard.php">Cancel</a>
    </form>
</body>
</html>