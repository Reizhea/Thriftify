<?php
require_once('includes/header.php');
require_once('includes/db_connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$pdo = get_db_connection();

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<main>
    <section class="user-profile">
        <h2>My Profile</h2>
        <div class="profile-container">
            <div class="profile-image">
                <img src="<?php echo BASE_URL . UPLOADS_DIR . PROFILE_PICTURES_DIR . $user['profile_picture']; ?>" 
                     alt="Profile Picture" class="resized-profile-picture">
            </div>
            <div class="profile-details">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <a href="edit_profile.php" class="btn">Edit Profile</a>
            </div>
        </div>
    </section>
</main>



<?php require_once('includes/footer.php'); ?>