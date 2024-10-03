<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');


$pdo = get_db_connection();

// Fetch order details
$order_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT o.*, u.username, u.email, u.address, o.total_cost, o.payment_method
                        FROM orders o
                        JOIN users u ON o.user_id = u.user_id
                        WHERE o.order_id = :order_id");
$stmt->execute(['order_id' => $order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch order items (Corrected line)
$stmt = $pdo->prepare("SELECT oi.*, p.title, p.price
                        FROM order_items oi
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = :order_id");
$stmt->execute(['order_id' => $order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <section class="view-order">
        <h2>Order Details</h2>
        <div class="order-info">
            <h3>Order #<?php echo $order['order_id']; ?></h3>
            <p><strong>Customer:</strong> <?php echo $order['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $order['email']; ?></p>
            <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
            <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
            <p><strong>Total:</strong> Rs <?php echo number_format($order['total_amount'], 2); ?></p>
            <p><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
        </div>
        <div class="order-items">
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?php echo $item['title']; ?></td>
                            <td>Rs <?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Rs <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php
require_once('admin_footer.php');
?>