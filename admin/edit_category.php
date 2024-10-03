<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');


$pdo = get_db_connection();

// Fetch category details
$category_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = :category_id");
$stmt->execute(['category_id' => $category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle category update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    $stmt = $pdo->prepare("UPDATE categories SET name = :name WHERE category_id = :category_id");
    $stmt->execute([
        'name' => $category_name,
        'category_id' => $category_id
    ]);

    header('Location: manage_categories.php');
    exit;
}
?>

<main>
    <section class="edit-category">
        <h2>Edit Category</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" id="category_name" name="category_name" value="<?php echo $category['name']; ?>" required>
            </div>
            <button type="submit" class="btn">Update Category</button>
        </form>
    </section>
</main>

<?php
require_once('admin_footer.php');
?>