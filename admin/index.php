<?php
require_once('admin_header.php'); // Correct path
require_once('../includes/db_connect.php');


$pdo = get_db_connection();

// Fetch counts for dashboard overview
$stmt = $pdo->query("SELECT COUNT(*) AS total_products FROM products");
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_orders FROM orders");
$total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

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
        </div>
    </section>
</main>

<?php
require_once('admin_footer.php'); // Correct path
?>