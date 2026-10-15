<?php
require_once "db.php";

// ---------- Delete handlers (before any SELECT) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $id = filter_input(INPUT_POST, 'delete_user_id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_id'])) {
    $id = filter_input(INPUT_POST, 'delete_product_id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: dashboard.php");
    exit;
}

// ---------- Data for the page ----------
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];

$users = $conn->query("SELECT id, username, email FROM users")->fetch_all(MYSQLI_ASSOC);
$products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);

// small helper so the HTML below stays readable
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./dashboard.css">
</head>

<body>

<header>
    <h1>Dashboard</h1>
    <div>
        <button id="user">Users</button>
        <button id="product">Products</button>
    </div>
    <button id="logout">Logout</button>
</header>

<main>
    <section id="datastatic">
        <h3>Number of users: <?= (int)$totalUsers ?></h3>
        <h3>Number of products: <?= (int)$totalProducts ?></h3>
    </section>

    <!-- Users section -->
    <section id="userdata" class="cards" hidden>
        <h2>Users</h2>

        <button class="adduser" data-type="user">Add New User</button>

        <?php foreach ($users as $u): ?>
            <article class="user-card">
                <h3><?= e($u['username'] ?? 'No name') ?></h3>
                <p>ID: <?= (int)$u['id'] ?></p>
                <p>Email: <?= e($u['email'] ?? 'No email') ?></p>

                <button class="info" data-id="<?= (int)$u['id'] ?>" data-type="user">Info</button>

                <button class="update"
                        data-type="user"
                        data-id="<?= (int)$u['id'] ?>"
                        data-username="<?= e($u['username']) ?>"
                        data-email="<?= e($u['email']) ?>">Update</button>

                <form method="POST" onsubmit="return confirm('Delete this user?')">
                    <input type="hidden" name="delete_user_id" value="<?= (int)$u['id'] ?>">
                    <button class="delete" type="submit">Delete</button>
                </form>
            </article>
        <?php endforeach; ?>
    </section>

    <!-- Products section -->
    <section id="productdata" class="cards" hidden>
        <h2>Products</h2>

        <button class="adduser" data-type="product">Add New Product</button>

        <?php foreach ($products as $p): ?>
            <article class="user-card">
                <h3><?= e($p['productname'] ?? 'No name') ?></h3>
                <p>ID: <?= (int)$p['id'] ?></p>

                <?php if (isset($p['description'])): ?>
                    <p><?= e($p['description']) ?></p>
                <?php endif; ?>

                <?php if (isset($p['Price'])): ?>
                    <p>Price: <?= e($p['Price']) ?></p>
                <?php endif; ?>

                <?php if (isset($p['Quantity'])): ?>
                    <p>Quantity: <?= (int)$p['Quantity'] ?></p>
                <?php endif; ?>

                <button class="info" data-id="<?= (int)$p['id'] ?>" data-type="product">Info</button>

                <button class="update"
                        data-type="product"
                        data-id="<?= (int)$p['id'] ?>"
                        data-productname="<?= e($p['productname']) ?>"
                        data-description="<?= e($p['description']) ?>"
                        data-price="<?= e($p['Price']) ?>"
                        data-quantity="<?= e($p['Quantity']) ?>">Update</button>

                <form method="POST" onsubmit="return confirm('Delete this product?')">
                    <input type="hidden" name="delete_product_id" value="<?= (int)$p['id'] ?>">
                    <button class="delete" type="submit">Delete</button>
                </form>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<!-- User popup (Add + Update) -->
<dialog id="userDialog">
    <form method="POST" action="user.php">
        <h2 id="userDialogTitle">User</h2>

        <input type="hidden" name="id" id="user_id">

        <label>Username
            <input type="text" name="username" id="user_username" maxlength="30" required>
        </label>

        <label>Email
            <input type="email" name="email" id="user_email" maxlength="50" required>
        </label>

        <label>Password
            <input type="password" name="password" id="user_password">
        </label>

        <button type="submit">Save</button>
        <button type="button" class="cancel">Cancel</button>
    </form>
</dialog>

<!-- Product popup (Add + Update) -->
<dialog id="productDialog">
    <form method="POST" action="product.php">
        <h2 id="productDialogTitle">Product</h2>

        <input type="hidden" name="id" id="product_id">

        <label>Name
            <input type="text" name="productname" id="product_productname" maxlength="30" required>
        </label>

        <label>Description
            <input type="text" name="description" id="product_description" maxlength="255">
        </label>

        <label>Price
            <input type="number" name="price" id="product_price" step="0.01" min="0" required>
        </label>

        <label>Quantity
            <input type="number" name="quantity" id="product_quantity" min="0" required>
        </label>

        <button type="submit">Save</button>
        <button type="button" class="cancel">Cancel</button>
    </form>
</dialog>

<script>
    const userBtn = document.getElementById('user');
    const productBtn = document.getElementById('product');
    const userData = document.getElementById('userdata');
    const productData = document.getElementById('productdata');

    const userDialog = document.getElementById('userDialog');
    const productDialog = document.getElementById('productDialog');

    userBtn.addEventListener('click', () => {
        userData.hidden = false;
        productData.hidden = true;
    });

    productBtn.addEventListener('click', () => {
        productData.hidden = false;
        userData.hidden = true;
    });

    document.getElementById('logout').addEventListener('click', () => {
        location.href = 'login.php';
    });

    // Cancel buttons close their own dialog
    document.querySelectorAll('dialog .cancel').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('dialog').close());
    });

    // ----- ADD -----
    document.querySelectorAll('.adduser').forEach(button => {
        button.addEventListener('click', () => {
            if (button.dataset.type === 'user') {
                userDialog.querySelector('form').reset();
                document.getElementById('user_id').value = '';
                document.getElementById('user_password').required = true;
                document.getElementById('userDialogTitle').textContent = 'Add User';
                userDialog.showModal();
            } else {
                productDialog.querySelector('form').reset();
                document.getElementById('product_id').value = '';
                document.getElementById('productDialogTitle').textContent = 'Add Product';
                productDialog.showModal();
            }
        });
    });

    // ----- UPDATE -----
    document.querySelectorAll('.update').forEach(button => {
        button.addEventListener('click', () => {
            const d = button.dataset;

            if (d.type === 'user') {
                userDialog.querySelector('form').reset();
                document.getElementById('user_id').value = d.id;
                document.getElementById('user_username').value = d.username;
                document.getElementById('user_email').value = d.email;
                document.getElementById('user_password').required = false;
                document.getElementById('user_password').placeholder = 'Leave empty to keep current';
                document.getElementById('userDialogTitle').textContent = 'Update User';
                userDialog.showModal();
            } else {
                productDialog.querySelector('form').reset();
                document.getElementById('product_id').value = d.id;
                document.getElementById('product_productname').value = d.productname;
                document.getElementById('product_description').value = d.description;
                document.getElementById('product_price').value = d.price;
                document.getElementById('product_quantity').value = d.quantity;
                document.getElementById('productDialogTitle').textContent = 'Update Product';
                productDialog.showModal();
            }
        });
    });

    // ----- INFO (still a placeholder) -----
    document.querySelectorAll('.info').forEach(button => {
        button.addEventListener('click', () => {
            alert('Info ' + button.dataset.type + ' ID: ' + button.dataset.id);
        });
    });
</script>

</body>
</html>