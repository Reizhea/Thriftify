<?php
require_once('db_connect.php');

$payment_method = $_POST['payment-method'];

$pdo = get_db_connection();
$stmt = $pdo->prepare("INSERT INTO orders (user_id, payment_method, total_cost) VALUES (:user_id, :payment_method, :total_cost)");
$stmt->execute([
    'user_id' => $_SESSION['user_id'],
    'payment_method' => $payment_method,
    'total_cost' => $_SESSION['total_cost']
]);

$order_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
foreach ($_SESSION['cart'] as $item) {
    $stmt->execute([
        'order_id' => $order_id,
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity']
    ]);
}

unset($_SESSION['cart']);
unset($_SESSION['total_cost']);

header('Location: ../order_confirmation.php');
exit;
?>