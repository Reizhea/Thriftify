<?php
require_once('includes/header.php');
require_once('includes/db_connect.php');

$id = $_GET['id'];
$pdo = get_db_connection();

// Fetch product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch reviews and calculate average rating
$stmt = $pdo->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.product_id = ?");
$stmt->execute([$id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average rating and total reviews
$stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(review_id) as total_reviews FROM reviews WHERE product_id = ?");
$stmt->execute([$id]);
$rating_data = $stmt->fetch(PDO::FETCH_ASSOC);

$avg_rating = round($rating_data['avg_rating'], 1); // Round to 1 decimal
$total_reviews = $rating_data['total_reviews'];
?>
<main>
<section class="product">
    <div class="product__photo">
        <div class="photo-container">
            <div class="photo-main">
                <div class="controls">
                    <!-- Share Icon -->
                    <a href="javascript:void(0);" onclick="shareProduct()">
                        <i class="fas fa-share-alt"></i>
                    </a>
                    <!-- Wishlist Icon -->
                    <a href="<?php echo BASE_URL; ?>wishlist.php?add=<?php echo $product['id']; ?>">
                        <i class="fas fa-heart"></i>
                    </a>
                </div>
                <img src="<?php echo BASE_URL . UPLOADS_DIR . PRODUCTS_DIR . $product['image']; ?>" alt="<?php echo $product['title']; ?>">
            </div>
        </div>
    </div>
    <div class="product__info">
        <div class="title">
            <h1><?php echo $product['title']; ?></h1>
            <span>Product ID: <?php echo $product['id']; ?></span>
        </div>
        <div class="price">
            Rs <span><?php echo $product['price']; ?></span>
        </div>
        <div class="description">
            <h3>Description</h3>
            <p><?php echo $product['description']; ?></p>
        </div>
        <button class="buy--btn">
            <a href="<?php echo BASE_URL; ?>cart.php?add=<?php echo $product['id']; ?>" style="color: white; text-decoration: none;">
                ADD TO CART
            </a>
        </button>
    </div>
</section>

<!-- Reviews Section -->
<section class="product-reviews">
    <h3>Reviews Average Rating: <span id="avg-rating"><?php echo $avg_rating; ?></span>/5, (<?php echo $total_reviews; ?> reviews)</h3>
    <?php if (empty($reviews)): ?>
        <p>No reviews yet.</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <p class="review-author"><?php echo $review['username']; ?></p>
                <p class="review-rating">
                    <?php for ($i = 0; $i < $review['rating']; $i++) { ?>
                        <i class="fas fa-star"></i>
                    <?php } ?>
                </p>
                <p class="review-text"><?php echo $review['review_text']; ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<!-- Submit Review Form -->
<section class="submit-review">
    <h3>Submit a Review</h3>
    <form action="submit_review.php" method="post">
        <label for="rating">Rating:</label>
        <div class="rating">
            <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
            <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
            <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
            <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
            <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
        </div>
        <label for="review_text">Review:</label>
        <textarea name="review_text" id="review_text" rows="4" required></textarea>
        <button type="submit" class="btn-submit">Submit Review</button>
    </form>
</section>

<script>
    function shareProduct() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert("Product link copied to clipboard!");
        }).catch(err => {
            alert("Failed to copy the link.");
        });
    }
</script>
</main>

<?php require_once('includes/footer.php'); ?>
