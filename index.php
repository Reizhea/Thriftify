<?php
require_once('includes/header.php');
require_once('includes/db_connect.php');

// Fetch featured products
$pdo = get_db_connection();
$stmt = $pdo->prepare("SELECT * FROM products WHERE featured = 1 LIMIT 4");
$stmt->execute();
$featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <section class="hero">
        <div class="hero-content">
            <!-- <h1><span>Thriftify</span></h1> -->
            <img src="images/logo.png" alt="Thriftify Logo" class="hero-logo">
            <p><span>Your destination for sustainable and affordable shopping.</span></p>
            <a href="<?php echo BASE_URL; ?>product_catalog.php" class="btn">Shop Now</a>
    </section>

    <section class="about">
        <h2>About Thriftify</h2>
        <div class="about-content">
            <p>Thriftify is an online thrift store dedicated to promoting sustainable consumption and reducing waste.<br> We offer a wide range of pre-owned clothing, accessories, and household items at affordable prices.</p>
        </div>
        <div class="about-image">
            <a href="<?php echo BASE_URL; ?>about_us.php" class="btn-about">About Us</a>
        </div>
    </section>

    <section class="featured-products">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <?php foreach ($featured_products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo BASE_URL . UPLOADS_DIR . PRODUCTS_DIR . $product['image']; ?>" alt="<?php echo $product['title']; ?>">
                    <h3><?php echo $product['title']; ?></h3>
                    <p class="price">Rs <?php echo $product['price']; ?></p>
                    <a href="<?php echo BASE_URL; ?>product_details.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="call-to-action">
        <h2>Join the Thriftify Community</h2>
        <p>Sign up today to stay updated on our latest arrivals and exclusive offers.</p>
        <a href="<?php echo BASE_URL; ?>login-signup.php" class="btn">Sign Up</a>
    </section>
</main>

<?php require_once('includes/footer.php'); ?>