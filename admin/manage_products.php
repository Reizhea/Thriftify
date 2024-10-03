<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');

$pdo = get_db_connection();

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <section class="admin-products">
        <h2>Manage Products</h2>
        <a href="add_item.php" class="btn">Add New Product</a>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['title']; ?></td>
                        <td>Rs <?php echo $product['price']; ?></td>
                        <td><?php echo $product['status']; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn">Edit</a>
                            <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once('admin_footer.php'); ?>