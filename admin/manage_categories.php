<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');


$pdo = get_db_connection();

// Handle category addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute(['name' => $category_name]);
    header('Location: manage_categories.php');
    exit;
}

// Handle category deletion
if (isset($_GET['delete'])) {
    $category_id = $_GET['delete'];

    // Check if any products are associated with this category
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = :category_id");
    $stmt->execute(['category_id' => $category_id]);
    $product_count = $stmt->fetchColumn();

    if ($product_count > 0) {
        // Display an error message if products are associated with this category
        echo "<p class='error'>Cannot delete category. There are products associated with it.</p>";
    } else {
        // Delete the category if no products are associated
        $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $category_id]);
        header('Location: manage_categories.php');
        exit;
    }
}

// Fetch all categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <section class="manage-categories">
        <h2>Manage Categories</h2>

        <h3>Add New Category</h3>
        <form action="" method="post">
            <div class="form-group">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" required>
            </div>
            <button type="submit" name="add_category" class="btn">Add Category</button>
        </form>

        <h3>Existing Categories</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['category_id']; ?></td>
                        <td><?php echo $category['name']; ?></td>
                        <td>
                            <a href="edit_category.php?id=<?php echo $category['category_id']; ?>" class="btn btn-secondary">Edit</a>
                            <a href="?delete=<?php echo $category['category_id']; ?>" class="btn btn-remove" onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">Delete</a>
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