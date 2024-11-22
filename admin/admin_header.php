<?php
session_start();
require_once('../includes/config.php'); 
require_once('../includes/db_connect.php'); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thriftify Admin</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/admin.css">
</head>
<body>
    <header class="admin-header">
        <nav>
            <h1><a href="<?php echo BASE_URL; ?>admin/index.php">Thriftify Admin</a></h1>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php 
                        $user_id = $_SESSION['user_id']; 
                        $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE user_id = ?");
                        $stmt->execute([$user_id]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        $profile_picture = $user['profile_picture']; 
                    ?>
                    <li>
                        <?php if ($profile_picture): ?>
                            <img src="<?php echo BASE_URL . UPLOADS_DIR . PROFILE_PICTURES_DIR . $profile_picture; ?>" 
                                 alt="Profile Picture" 
                                 class="profile-picture"
                                 style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;"> 
                        <?php endif; ?>
                        <li><a href="<?php echo BASE_URL; ?>admin/admin_profile.php">My Profile</a></li>
                    </li>
                    <li><a href="<?php echo BASE_URL; ?>admin/manage_products.php">Products</a></li>
                    <li><a href="<?php echo BASE_URL; ?>admin/manage_categories.php">Manage Categories</a></li>
                    <li><a href="<?php echo BASE_URL; ?>admin/manage_users.php">Users</a></li>
                    <li><a href="<?php echo BASE_URL; ?>admin/manage_orders.php">Orders</a></li>
                    <li><a href="<?php echo BASE_URL; ?>logout.php">Logout</a></li> 
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>login.php">Login</a></li>
                    <li><a href="<?php echo BASE_URL; ?>signup.php">Signup</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
</html>