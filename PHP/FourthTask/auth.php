<?php
declare(strict_types=1);

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $formType = $_POST["form_type"] ?? "";

    if ($formType === "login") {

        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        $sql = "SELECT id, username, password FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {
                echo "Login successful";
            } else {
                echo "Invalid email or password";
            }

        } else {
            echo "Invalid email or password";
        }

        $stmt->close();

    } elseif ($formType === "register") {

        $name = trim($_POST["username"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        // Check whether the email already exists
        $sql = "SELECT id FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            echo "Email already exists. Use another one.";

        } else {

            // Hash the password before saving it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO user (username, email, password)
                    VALUES (?, ?, ?)";

            $stmt->close();

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {
                echo "Registration successful";
            } else {
                echo "Registration failed";
            }
        }

        $stmt->close();

    } else {
        echo "Unknown form";
    }

} else {
    echo "Invalid request method";
}

$conn->close();