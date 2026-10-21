<?php
session_start();
require_once "user.php";
require_once "category.php";
require_once "product.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth.php?mode=login");
    exit;
}

$users      = (new User())->ReturnUsers();
$categories = (new Category())->returnCategories();
$products   = (new Product())->returnProducts();

// message set by delete.php (e.g. category still has products)
$flash = $_SESSION["flash"] ?? "";
unset($_SESSION["flash"]);

function deleteForm(string $type, int $id): void
{
    ?>
    <form action="delete.php" method="POST"
          onsubmit="return confirm('Are you sure?');">
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" class="btn btn-delete">Delete</button>
    </form>
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            margin: 0;
            color: #222;
        }

        header {
            background: #0D1C42;
            color: #FCF1D0;
            padding: 20px 30px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        main {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        section {
            background: white;
            border-radius: 10px;
            padding: 20px 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-head h2 {
            margin: 0;
            color: #010736;
        }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #e3e6ec;
            vertical-align: middle;
        }

        th {
            background: #0D1C42;
            color: #FCF1D0;
            font-weight: normal;
        }

        tbody tr:hover { background: #f7f9fc; }

        .empty {
            text-align: center;
            color: #888;
        }

        .thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            display: block;
        }

        .btn {
            display: inline-block;
            padding: 7px 14px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            color: white;
        }

        .btn-add    { background: #0D1C42; }
        .btn-update { background: #2563eb; }
        .btn-delete { background: #c62828; }

        .btn:hover { opacity: 0.85; }

        form { margin: 0; }

        .flash {
            background: #fdecea;
            color: #c62828;
            border-left: 4px solid #c62828;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

    <header>
        <h1>Dashboard</h1>
    </header>

    <main>

        <?php if ($flash !== ""): ?>
            <div class="flash"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <!-- USERS -->
        <section>
            <div class="section-head">
                <h2>Users</h2>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Name</th><th>Email</th><th>Update</th><th>Delete</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u["username"]) ?></td>
                            <td><?= htmlspecialchars($u["email"]) ?></td>
                            <td><a class="btn btn-update" href="updateUser.php?id=<?= (int)$u["id"] ?>">Update</a></td>
                            <td><?php deleteForm("user", (int)$u["id"]); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- CATEGORIES -->
        <section>
            <div class="section-head">
                <h2>Categories</h2>
                <a class="btn btn-add" href="categoryForm.php">+ Add category</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Name</th><th>Update</th><th>Delete</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!$categories): ?>
                            <tr><td colspan="3" class="empty">No categories yet.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($categories as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c["name"]) ?></td>
                            <td><a class="btn btn-update" href="categoryForm.php?id=<?= (int)$c["id"] ?>">Update</a></td>
                            <td><?php deleteForm("category", (int)$c["id"]); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- PRODUCTS -->
        <section>
            <div class="section-head">
                <h2>Products</h2>
                <a class="btn btn-add" href="productForm.php">+ Add product</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Category</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$products): ?>
                            <tr><td colspan="8" class="empty">No products yet.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <?php if (!empty($p["image"])): ?>
                                    <img src="<?= htmlspecialchars($p["image"]) ?>" alt="" class="thumb">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($p["productname"]) ?></td>
                            <td><?= htmlspecialchars($p["description"] ?? "-") ?></td>
                            <td><?= htmlspecialchars(number_format((float)$p["price"], 2)) ?></td>
                            <td><?= (int)$p["quantity"] ?></td>
                            <td><?= htmlspecialchars($p["category"] ?? "-") ?></td>
                            <td><a class="btn btn-update" href="productForm.php?id=<?= (int)$p["id"] ?>">Update</a></td>
                            <td><?php deleteForm("product", (int)$p["id"]); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>