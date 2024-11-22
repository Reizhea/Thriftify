<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');



$pdo = get_db_connection();

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    // Handle file upload
    $target_dir = "../uploads/products/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        // File is an image
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);

            $stmt = $pdo->prepare("INSERT INTO products (title, description, price, category_id, image, status, user_id) VALUES (:title, :description, :price, :category_id, :image, :status, :user_id)");
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'category_id' => $category,
                'image' => $image,
                'status' => $status,
                'user_id' => $_SESSION['user_id']
            ]);

            header('Location: manage_products.php');
            exit;
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    } else {
        $error = "File is not an image.";
    }
}

// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <section class="add-item">
        <h2>Add New Item</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn">Add Item</button>
        </form>
    </section>
</main>

<?php
require_once('admin_footer.php');
?>