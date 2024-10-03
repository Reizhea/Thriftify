<?php
session_start();
require_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Store the user's role in the session

            // Redirect to the appropriate page based on the role
            if ($_SESSION['role'] === 'admin') {
                header('Location: ../admin/index.php'); // Redirect to admin panel
            } else {
                header('Location: ../index.php'); // Redirect to the regular website
            }
            exit;
        } else {
            header('Location: ../login-signup.php?error=Invalid username or password');
            exit;
        }
    } catch (PDOException $e) {
        // Handle the database error
        echo "Error: " . $e->getMessage();
    }
}
?>