<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');


$user_id = $_SESSION['user_id'];

$pdo = get_db_connection();
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<main>
    <section class="admin-profile">
        <h2>My Admin Profile</h2>
        <div class="profile-details">
            <?php if ($user['profile_picture']): ?>
                <img src="<?php echo BASE_URL . UPLOADS_DIR . PROFILE_PICTURES_DIR . $user['profile_picture']; ?>" 
                     alt="Profile Picture" 
                     class="profile-picture">
            <?php endif; ?>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <a href="edit_profile.php" class="btn">Edit Profile</a> 
        </div>
    </section>
</main>

<?php require_once('admin_footer.php'); ?>