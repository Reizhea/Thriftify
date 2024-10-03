<?php
session_start();
require_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm_password) {
        $_SESSION['toast_message'] = "Passwords do not match";
        header("Location: ../login-signup.php");
        exit();
    }

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['username'] === $username) {
            $_SESSION['toast_message'] = "Username already exists";
        } else {
            $_SESSION['toast_message'] = "Email already exists";
        }
        header("Location: ../login-signup.php");
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Handle profile picture upload (if any)
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = UPLOADS_DIR . PROFILE_PICTURES_DIR;
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['toast_message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header("Location: ../login-signup.php");
            exit();
        } else {
            if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $_SESSION['toast_message'] = "Error uploading profile picture.";
                header("Location: ../login-signup.php");
                exit();
            } else {
                $profile_picture = basename($_FILES["profile_picture"]["name"]);
            }
        }
    } else {
        $profile_picture = null;
    }

    // Insert user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, profile_picture) 
                            VALUES (:username, :email, :password, :profile_picture)");
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword,
        'profile_picture' => $profile_picture
    ]);

    $_SESSION['toast_message'] = 'Registration successful. You can now log in.';
    header("Location: ../login-signup.php");
    exit();
}
