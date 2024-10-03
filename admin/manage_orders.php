<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');


$pdo = get_db_connection();

// Fetch all orders
$stmt = $pdo->query("SELECT o.*, u.username 
                    FROM orders o
                    JOIN users u ON o.user_id = u.user_id");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle order status update
if (isset($_GET['status'])) {
    $order_id = $_GET['status'];
    $new_status = $_GET['new_status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = :new_status WHERE order_id = :order_id");
    $stmt->execute(['new_status' => $new_status, 'order_id' => $order_id]);
    header('Location: manage_orders.php');
    exit;
}
?>

<main>
    <section class="manage-orders">
        <h2>Manage Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['username']; ?></td>
                        <td>Rs <?php echo number_format($order['total_amount'], 2); ?></td>  
                        <td>
                            <?php echo ucfirst($order['status']); ?>
                            <?php if ($order['status'] !== 'delivered'): ?>
                                <a href="manage_orders.php?status=<?php echo $order['order_id']; ?>&new_status=delivered" class="btn btn-status">Mark as Delivered</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="view_order.php?id=<?php echo $order['order_id']; ?>" class="btn">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php
require_once('admin_footer.php');
?>