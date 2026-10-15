<?php
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit;
}

$id       = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || strlen($username) > 30) {
    die("Invalid username.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 50) {
    die("Invalid email.");
}

try {
    if ($id) {
        // UPDATE
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
                "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?"
            );
            $stmt->bind_param("sssi", $username, $email, $hash, $id);
        } else {
            $stmt = $conn->prepare(
                "UPDATE users SET username = ?, email = ? WHERE id = ?"
            );
            $stmt->bind_param("ssi", $username, $email, $id);
        }
    } else {
        // INSERT
        if ($password === '') {
            die("Password is required for a new user.");
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $username, $email, $hash);
    }

    $stmt->execute();
} catch (mysqli_sql_exception $ex) {
    if ($ex->getCode() === 1062) {
        die("That email is already used.");
    }
    throw $ex;
}

header("Location: dashboard.php");
exit;