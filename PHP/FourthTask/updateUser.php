<?php
require_once "user.php";

$user = new User();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int)($_POST["id"] ?? 0);

    if (!$id) {
        exit("User ID is required.");
    }

    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $email === "" || $password === "") {
        $error = "Username, email, and password are required.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        if ($user->updateUser($id, $username, $email, $password)) {
            header("Location: dashboard.php");
            exit;
        }
        $error = "Failed to update user.";
    }
} else {
    $id = (int)($_GET["id"] ?? 0);

    if (!$id) {
        exit("User ID is required.");
    }
}

$Result = $user->findUser($id);

if (!$Result) {
    exit("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
</head>
<body>

    <h1>Update User</h1>

    <?php if ($error !== ""): ?>
        <p style="color: red;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form action="updateUser.php" method="POST">

         <input type="hidden" name="id" value="<?= $id ?>">

    <label for="username">Username:</label>
    <input
        type="text"
        id="username"
        name="username"
        value="<?= htmlspecialchars($_POST["username"] ?? $Result["username"]) ?>"
        required
    >
        <br><br>

        <label for="email">Email:</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($_POST["email"] ?? $Result["email"]) ?>"
            required
        >

        <br><br>

        <label for="password">New Password:</label>
        <input
            type="password"
            id="password"
            name="password"
            minlength="8"
            required
        >

        <br><br>

        <button type="submit">Save Changes</button>
        <a href="dashboard.php">Cancel</a>

    </form>

</body>
</html>
