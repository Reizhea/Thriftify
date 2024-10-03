<?php
session_start();
require_once('config.php');
require_once('db_connect.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thriftify</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha384-h/hnnw1Bi4nbpD6kE7nYfCXzovi622sY5WBxww8ARKwpdLj5kUWjRuyiXaD1U2JT" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<body>
    <header class="sticky-header">
        <nav>
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>index.php">
                    <img src="images/logo.png" alt="Thriftify Logo" class="logo-image">
                </a> 
            </div>

            <div class="nav-container">
                <ul class="nav-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a href="<?php echo BASE_URL; ?>add_item.php"><i class="fas fa-plus-circle"></i> Add Item</a></li>
                        <li class="nav-item"><a href="<?php echo BASE_URL; ?>wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
                        <li class="nav-item"><a href="<?php echo BASE_URL; ?>cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                        <li class="nav-item"><a href="<?php echo BASE_URL; ?>my_products.php"><i class="fa-solid fa-shirt"></i> My Products</a></li>
                        <li class="nav-item"><a href="<?php echo BASE_URL; ?>product_catalog.php"><i class="fas fa-store"></i> Shop</a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <div>
                            <input type="checkbox" class="checkbox" id="checkbox">
                            <label for="checkbox" class="checkbox-label">
                                <i class="fas fa-moon"></i>
                                <i class="fas fa-sun"></i>
                                <span class="ball"></span>
                            </label>
                        </div>
                    </li>
                </ul>

                <ul class="profile-nav"> 
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a href="#" class="profile-dropdown">
                                <?php 
                                    $user_id = $_SESSION['user_id']; 
                                    $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE user_id = ?");
                                    $stmt->execute([$user_id]);
                                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $profile_picture = $user['profile_picture']; 
                                ?>
                                <?php if ($profile_picture): ?>
                                    <img src="<?php echo BASE_URL . UPLOADS_DIR . PROFILE_PICTURES_DIR . $profile_picture; ?>" 
                                         alt="Profile Picture" 
                                         class="profile-picture"> 
                                <?php else: ?>
                                    <i class="fas fa-user-circle fa-2x"></i>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-content">
                                <li><a href="<?php echo BASE_URL; ?>user_profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                                <li><a href="<?php echo BASE_URL; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </li> 
                    <?php else: ?>
                        <li class="nav-item"><a href="<?php echo BASE_URL; ?>login-signup.php"><i class="fas fa-sign-in-alt"></i> Login/Signup</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <script>
    $(document).ready(function() {
    $(".profile-dropdown").click(function(e) {
        e.stopPropagation();  // Prevents the click from affecting other elements
        $(".dropdown-content").toggle();
    });

    // Close the dropdown if the user clicks outside
    $(document).click(function(e) {
        if (!$(e.target).closest('.dropdown-content, .profile-dropdown').length) {
            $(".dropdown-content").hide();
        }
    });

    const checkbox = document.getElementById("checkbox");
    checkbox.addEventListener("change", () => {
        document.body.classList.toggle("dark");
        if (document.body.classList.contains("dark")) {
            localStorage.setItem("darkMode", "true");
        } else {
            localStorage.setItem("darkMode", "false");
        }
    });

    if (localStorage.getItem("darkMode") === "true") {
        $("body, header").addClass("dark");
        checkbox.checked = true;
    }
});

    </script>
</body>
</html>
