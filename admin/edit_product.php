<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');


$pdo = get_db_connection();

// Fetch product details
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle product update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    // Handle file upload (if a new image is provided)
    if (!empty($_FILES['image']['name'])) {
        $target_dir = UPLOADS_DIR . PRODUCTS_DIR;
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // File is an image
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["image"]["name"]);
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    } else {
        $image = $product['image'];
    }

    if (!isset($error)) {
        $stmt = $pdo->prepare("UPDATE products SET title = :title, description = :description, price = :price, category_id = :category, image = :image, status = :status WHERE id = :id"); 
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'image' => $image,
            'status' => $status,
            'id' => $id
        ]);

        header('Location: manage_products.php');
        exit;
    }
}

// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <section class="edit-product">
        <h2>Edit Product</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo $product['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
    <?php foreach ($categories as $category): ?>
        <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $product['category_id']) echo 'selected'; ?>><?php echo $category['name']; ?></option>
    <?php endforeach; ?>
</select>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active" <?php if ($product['status'] == 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($product['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn">Update Product</button>
        </form>
    </section>
</main>

<?php
require_once('admin_footer.php');
?>