<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');


$pdo = get_db_connection();

// Fetch dashboard statistics
$stmt = $pdo->query("SELECT COUNT(*) AS total_products FROM products");
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_orders FROM orders");
$total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

$stmt = $pdo->query("SELECT SUM(total_amount) AS total_revenue FROM orders");
$total_revenue = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'];

$stmt = $pdo->query("SELECT COUNT(*) AS new_orders 
                     FROM orders 
                     WHERE DATE(order_date) = CURDATE()");
$new_orders = $stmt->fetch(PDO::FETCH_ASSOC)['new_orders'];

$stmt = $pdo->query("SELECT COUNT(*) AS new_users
                     FROM users
                     WHERE DATE(created_at) = CURDATE()");
$new_users = $stmt->fetch(PDO::FETCH_ASSOC)['new_users'];
?>

<main>
    <section class="admin-dashboard">
        <h2>Admin Dashboard</h2>
        <div class="dashboard-overview">
            <div class="dashboard-card">
                <h3>Total Products</h3>
                <p><?php echo $total_products; ?></p>
            </div>
            <div class="dashboard-card">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="dashboard-card">
                <h3>Total Orders</h3>
                <p><?php echo $total_orders; ?></p>
            </div>
            <div class="dashboard-card">
                <h3>Total Revenue</h3>
                <p>Rs <?php echo number_format($total_revenue, 2); ?></p>
            </div>
            <div class="dashboard-card">
                <h3>New Orders</h3>
                <p><?php echo $new_orders; ?></p>
            </div>
            <div class="dashboard-card">
                <h3>New Users</h3>
                <p><?php echo $new_users; ?></p>
            </div>
        </div>
    </section>
</main>

<?php
require_once('admin_footer.php');
?>