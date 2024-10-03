<?php
require_once('includes/header.php');
require_once('includes/db_connect.php');

if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit();
}

$pdo = get_db_connection();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->execute([$_GET['order_id'], $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT oi.*, p.title FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order['order_id']]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <section class="order-confirmation">
        <h2>Order Confirmation</h2>
        <p>Thank you for your order! Your order number is: <?php echo $order['order_id']; ?></p>
        <h3>Order Details</h3>
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
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo $item['title']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>Rs <?php echo number_format($item['price'], 2); ?></td>
                        <td>Rs <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total</td>
                    <td>Rs <?php echo number_format($order['total_amount'], 2); ?></td>
                </tr>
            </tfoot>
        </table>
        <p>We'll email you when your order has been shipped.</p>
        <a href="<?php echo BASE_URL; ?>index.php" class="btn">Continue Shopping</a>
    </section>
</main>

<?php require_once('includes/footer.php'); ?>