<?php

session_start();

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$governorate = trim($_POST['governorate'] ?? '');
$track = trim($_POST['track'] ?? '');
$skills = $_POST['skills'] ?? [];
$message = trim($_POST['message'] ?? '');
$agree = isset($_POST['agree']);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($name === '') {
        $errors[] = "Name is required.";
    }

    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email format is invalid.";
    }

    if ($mobile === '') {
        $errors[] = "Mobile is required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $errors[] = "Phone number must contain exactly 10 digits.";
    }

    if ($governorate === '') {
        $errors[] = "Please select a governorate.";
    }

    if ($track === '') {
        $errors[] = "Please select a track.";
    }

    if (empty($skills)) {
        $errors[] = "Please select at least one skill.";
    }

    if (!$agree) {
        $errors[] = "You must agree to the terms.";
    }

    if (empty($errors)) {
        $user = new User(
            $name,
            $email,
            $mobile,
            $governorate,
            $track,
            $skills,
            $message
        );

        $_SESSION['user'] = $user;

        setcookie(
            'user',
            $user->name,
            time() + (60 * 60 * 24),
            '/'
        );

        echo "Registration successful!";
        echo htmlspecialchars($user->name);
    } else {
        foreach ($errors as $error) {
            echo "<p>" . htmlspecialchars($error) . "</p>";
        }
    }
}

class User {
    public $name;
    public $email;
    public $mobile;
    public $governorate;
    public $track;
    public $skills;
    public $message;

    public function __construct(
        $name,
        $email,
        $mobile,
        $governorate,
        $track,
        $skills,
        $message
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->governorate = $governorate;
        $this->track = $track;
        $this->skills = $skills;
        $this->message = $message;
    }
}

?>
