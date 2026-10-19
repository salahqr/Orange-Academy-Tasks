<?php
session_start();
require_once "user.php";

$error = "";

// 1. Pick the mode (login or register)
$mode = $_POST["mode"] ?? $_GET["mode"] ?? "login";
if (!in_array($mode, ["login", "register"], true)) {
    $mode = "login";
}

$name = "";
$email = "";

// 2. Handle the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = new User();

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($mode === "register") {

        if ($name === "" || $email === "" || $password === "") {
            $error = "Name, email, and password are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters.";
        } else {
            try {
                $user->createUser($name, $email, $password);
                header("Location: auth.php?mode=login");
                exit;
            } catch (PDOException $e) {
                // 23000 = integrity constraint violation (e.g. duplicate email)
                if ($e->getCode() === "23000") {
                    $error = "This email is already registered.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }

    } else { // login

        if ($email === "" || $password === "") {
            $error = "Email and password are required.";
        } else {
            $userId = $user->loginUser($email, $password);

            if ($userId !== null) {
                session_regenerate_id(true);
                $_SESSION["user_id"] = $userId;
                header("Location: dashboard.php");
                exit;
            }

            $error = "Invalid email or password.";
        }
    }
}

$isRegister = ($mode === "register");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isRegister ? "Register" : "Login" ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        form {
            padding: 30px;
            width: 340px;
            border-radius: 10px;
        }

        h2 { text-align: center; }

        label {
            display: block;
            margin-top: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: #c62828;
            text-align: center;
        }

        .switch {
            text-align: center;
            margin-top: 15px;
        }

        /* Login theme */
        body.login { background: #0D1C42; }
        body.login form { background: #FCF1D0; }
        body.login h2 { color: #010736; }
        body.login button { background: #010736; }

        /* Register theme */
        body.register { background: #f2f2f2; }
        body.register form { background: white; box-shadow: 0 0 10px #ccc; }
        body.register button { background: #0d1c42; }
    </style>
</head>

<body class="<?= $mode ?>">

    <form action="" method="POST">
        <input type="hidden" name="mode" value="<?= $mode ?>">

        <h2><?= $isRegister ? "Create Account" : "Login" ?></h2>

        <?php if ($error !== ""): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($isRegister): ?>
            <label for="name">Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($name) ?>"
                required
            >
        <?php endif; ?>

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($email) ?>"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            required
            <?= $isRegister ? 'minlength="8"' : '' ?>
        >

        <button type="submit"><?= $isRegister ? "Register" : "Login" ?></button>

        <p class="switch">
            <?php if ($isRegister): ?>
                Already have an account? <a href="auth.php?mode=login">Login</a>
            <?php else: ?>
                No account? <a href="auth.php?mode=register">Register</a>
            <?php endif; ?>
        </p>
    </form>

</body>
</html>