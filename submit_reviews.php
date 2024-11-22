<?php
session_start();
require_once('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    // Insert review into the database
    $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$product_id, $user_id, $rating, $review_text]);

    $_SESSION['message'] = "Review submitted successfully!";
    header("Location: product_details.php?id=$product_id");
    exit();
}
?>
