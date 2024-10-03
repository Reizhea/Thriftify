<?php
require_once('includes/header.php');
require_once('includes/db_connect.php');

$pdo = get_db_connection();

// Fetch categories
$stmt = $pdo->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
switch ($sort) {
    case 'price-asc':
        $order_by = 'price ASC';
        break;
    case 'price-desc':
        $order_by = 'price DESC';
        break;
    default:
        $order_by = 'created_at DESC';
}

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch products based on category filter
if ($category_filter !== "") {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = :category_id AND status = 'active' ORDER BY $order_by");
    $stmt->execute(['category_id' => $category_filter]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch all active products if no category is selected
    $stmt = $pdo->prepare("SELECT * FROM products WHERE status = 'active' ORDER BY $order_by");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<main>
    <section class="product-catalog">
        <h2>Product Catalog</h2>
        <div class="filter-bar">
    <form id="filterForm" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="category">Filter by Category:</label>
        <select id="category" name="category" onchange="submitFilterForm()">
            <option value="">All</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>" 
                    <?php if (isset($_GET['category']) && $_GET['category'] == $category['category_id']) echo 'selected'; ?>>
                    <?php echo $category['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="sort-options">
            <label for="sort-by">Sort by:</label>
            <select id="sort-by" name="sort" onchange="submitFilterForm()">
                <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                <option value="price-asc" <?php echo $sort == 'price-asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price-desc" <?php echo $sort == 'price-desc' ? 'selected' : ''; ?>>Price: High to Low</option>
            </select>
        </div>
    </form>
</div>

<script>
    function submitFilterForm() {
        document.getElementById('filterForm').submit();
    }
</script>

        </div>
        <div class="grid-container"> <!-- New wrapper -->
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo BASE_URL . UPLOADS_DIR . PRODUCTS_DIR . $product['image']; ?>" alt="<?php echo $product['title']; ?>">
                        <h3><?php echo $product['title']; ?></h3>
                        <p class="price">Rs <?php echo $product['price']; ?></p>
                        <a href="<?php echo BASE_URL; ?>product_details.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> <!-- End of wrapper -->
    </section>
</main>

<?php require_once('includes/footer.php'); ?>