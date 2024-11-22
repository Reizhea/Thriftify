<?php
ob_start();
require_once('includes/header.php');
require_once('includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = get_db_connection();

// Handle removing items from the wishlist
if (isset($_GET['remove'])) {
    $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $_GET['remove']]);
}

// Handle adding items to the wishlist
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    $user_id = $_SESSION['user_id'];

    // Check if the item is already in the wishlist
    $stmt = $pdo->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $exists = $stmt->fetch();

    if (!$exists) {
        // Insert the item into the wishlist
        $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $product_id]);
    }

    // Redirect back to the wishlist
    header('Location: wishlist.php');
    exit;
}

// Fetch wishlist items
$stmt = $pdo->prepare("SELECT w.*, p.title, p.price, p.image FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
ob_end_flush();
?>

<main>
    <section class="wishlist">
        <h2>Your Wishlist</h2>
        <?php if (empty($wishlist_items)): ?>
            <p>Your wishlist is empty.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="product-card">
                        <img src="<?php echo BASE_URL . UPLOADS_DIR . PRODUCTS_DIR . $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                        <h3><?php echo $item['title']; ?></h3>
                        <p class="price">Rs <?php echo $item['price']; ?></p>
                        <a href="<?php echo BASE_URL; ?>product_details.php?id=<?php echo $item['product_id']; ?>" class="btn">View Details</a>
                        <a href="?remove=<?php echo $item['product_id']; ?>" class="btn btn-remove">Remove</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>


<?php require_once('includes/footer.php'); ?>