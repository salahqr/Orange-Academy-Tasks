<?php 
require_once __DIR__ . "/../model/user.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user = new User();
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";


        if ($name === "" || $email === "" || $password === "") {
            $error = "Name, email, and password are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters.";
        } else {
            try {
                $user=$user->createUser($name, $email, $password);

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
            } catch (PDOException $e) {
                if ($e->getCode() === "23000") {
                    $error = "This email is already registered.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Museum Tours</title>
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
                <h1>Your seat is waiting.</h1>
                <p>Create a free account to book guided tours, track your reservations, and never miss a session.</p>
                <ul class="hero-points">
                    <li>Instant booking confirmation</li>
                    <li>Cancel if plans change</li>
                </ul>
            </div>
        </section>

        <section class="auth-panel">
            <form class="auth-card" id="registerForm" action="register.php" method="POST" novalidate>
                <h2>Create your account</h2>
                <p class="subtitle">It only takes a minute.</p>

                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Your name" maxlength="30" required autocomplete="username">
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" maxlength="50" required autocomplete="email">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="At least 8 characters" minlength="8" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn">Create account</button>

                <p class="switch">Already have an account? <a href="login.php">Log in</a></p>
            </form>
        </section>
    </main>

    <script>
        const form = document.getElementById('registerForm');
        const pass = document.getElementById('password');

        confirmPass.addEventListener('input', () => {
            error.hidden = true;
            confirmPass.classList.remove('invalid');
        });
    </script>
</body>
</html>