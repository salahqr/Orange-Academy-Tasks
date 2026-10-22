<?php 
require_once __DIR__ . "/../model/user.php";
session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user = new User();
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        if ($email === "" || $password === "") {
            $error = "Email and password are required.";
        } else {
            $user = $user->loginUser($email, $password);

            if ($user[0] !== null) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user[0];
                $_SESSION['role'] = $user[1];


                if ($user[1] == 'admin') {
                    header("Location: admin.php");
                    exit;
                }

                header("Location: user.php");
                exit;
            }
            $error = "Invalid email or password.";  
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Museum Tours</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="auth">
        <section class="auth-hero">
            <div class="hero-content">
                <span class="brand">Museum Tours</span>
                <h1>Step inside the story.</h1>
                <p>Reserve your seat on a guided tour and discover the collection with an expert at your side.</p>
                <ul class="hero-points">
                    <li>Browse scheduled tours</li>
                    <li>Reserve seats in seconds</li>
                    <li>Manage your bookings anytime</li>
                </ul>
            </div>
        </section>

        <section class="auth-panel">
            <form class="auth-card" action="login.php" method="POST" novalidate>
                <h2>Welcome back</h2>
                <p class="subtitle">Log in to manage your reservations.</p>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Your password" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn">Log in</button>

                <p class="switch">New to Museum Tours? <a href="register.php">Create an account</a></p>
            </form>
        </section>
    </main>
</body>
</html>