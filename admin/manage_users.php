<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');

$pdo = get_db_connection();

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);

$stmt = $pdo->prepare("DELETE FROM reviews WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    // Delete order items associated with user's orders
$stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id IN (SELECT order_id FROM orders WHERE user_id = :user_id)");
$stmt->execute(['user_id' => $user_id]);

// Now delete orders
$stmt = $pdo->prepare("DELETE FROM orders WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);

// Finally, delete the user
$stmt = $pdo->prepare("DELETE FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);

    header('Location: manage_users.php');
    exit;
}

// Handle user role update
if (isset($_GET['role'])) {
    $user_id = $_GET['role'];
    $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE user_id = :user_id");
    $stmt->execute(['role' => 'admin', 'user_id' => $user_id]);
    header('Location: manage_users.php');
    exit;
}
?>

<main>
    <section class="manage-users">
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td>
                        <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-secondary">Edit</a> 
<?php if ($user['role'] !== 'admin'): ?>
    <a href="manage_users.php?role=<?php echo $user['user_id']; ?>" class="btn btn-admin">Make Admin</a>
<?php endif; ?>
<a href="manage_users.php?delete=<?php echo $user['user_id']; ?>" class="btn btn-delete">Delete</a>
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