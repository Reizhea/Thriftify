<?php
ob_start();  // Start output buffering

require_once('includes/header.php');
require_once('includes/db_connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = get_db_connection();
$user_id = $_SESSION['user_id'];

// Handle product deletion
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$product_id, $user_id]);
    header('Location: my_products.php');
    exit();
}

// Handle status change
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status = $_GET['status'];
    $product_id = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE products SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$status, $product_id, $user_id]);
    header('Location: my_products.php');
    exit();
}

// Fetch user's products
$stmt = $pdo->prepare("SELECT * FROM products WHERE user_id = ?");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_end_flush();  // Flush the output buffer and turn off output buffering
?>


<main>
    <section class="my-products">
        <h2>My Products</h2>
        <?php if (empty($products)): ?>
            <p>You haven't listed any products yet.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo BASE_URL . UPLOADS_DIR . PRODUCTS_DIR . $product['image']; ?>" alt="<?php echo $product['title']; ?>">
                        <h3><?php echo $product['title']; ?></h3>
                        <p class="price">Rs <?php echo $product['price']; ?></p>
                        <p>Status: <?php echo $product['status']; ?></p>

                        <!-- Status toggle -->
                        <?php if ($product['status'] == 'active'): ?>
                            <a href="?status=inactive&id=<?php echo $product['id']; ?>" class="btn">Mark as Inactive</a>
                        <?php else: ?>
                            <a href="?status=active&id=<?php echo $product['id']; ?>" class="btn">Mark as Active</a>
                        <?php endif; ?>

                        <!-- Delete product -->
                        <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-delete">Delete</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once('includes/footer.php'); ?>
