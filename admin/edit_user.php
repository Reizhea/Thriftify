<?php
require_once('admin_header.php');
require_once('../includes/db_connect.php');


$pdo = get_db_connection();

// Fetch user details
$user_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle user update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    // Handle file upload (if a new image is provided)
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = UPLOADS_DIR . PROFILE_PICTURES_DIR; 
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
        $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, name = :name, address = :address, role = :role, profile_picture = :profile_picture WHERE user_id = :user_id");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'address' => $address,
            'role' => $role,
            'profile_picture' => $profile_picture,
            'user_id' => $user_id
        ]);

        header('Location: manage_users.php');
        exit;
    }
}
?>

<main>
    <section class="edit-user">
        <h2>Edit User</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
                    <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture">
            </div>
            <button type="submit" class="btn">Update User</button>
        </form>
    </section>
</main>

<?php
require_once('admin_footer.php');
?>