<?php
ob_start();
require_once('includes/header.php');
require_once('includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = get_db_connection();

$stmt = $pdo->prepare("SELECT c.*, p.title, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$_SESSION['user_id'], $total]);
    $order_id = $pdo->lastInsertId();

    foreach ($cart_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // Clear the cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    header('Location: order_confirmation.php?order_id=' . $order_id);
    exit();
}
ob_end_flush();
?>

<main>
    <section class="checkout">
        <h2>Checkout</h2>
        <form method="post">
            <h3>Order Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo $item['title']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td>$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>

            <h3>Payment Information</h3>
            <div>
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" required>
            </div>
            <div>
                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
            </div>
            <div>
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
            </div>

            <button type="submit" class="btn">Place Order</button>
        </form>
    </section>
</main>

<?php require_once('includes/footer.php'); ?>