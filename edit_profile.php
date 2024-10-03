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
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $address = $_POST['address'];

    // Handle file upload (if a new image is provided)
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = UPLOADS_DIR . PROFILE_PICTURES_DIR; // Set your directory
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if($check !== false) {
            // File is an image
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = basename($_FILES["profile_picture"]["name"]);
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    } else {
        $profile_picture = $user['profile_picture'];
    }

    if (!isset($error)) {
        // Update user profile
        $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, name = :name, address = :address, profile_picture = :profile_picture WHERE user_id = :user_id");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'address' => $address,
            'profile_picture' => $profile_picture,
            'user_id' => $user_id
        ]);

        header('Location: user_profile.php');
        exit;
    }
}
?>

<main>
    <section class="edit-profile">
        <h2>Edit Profile</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture">
            </div>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </section>
</main>

<?php
require_once('includes/footer.php');
?>