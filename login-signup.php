<?php 
require_once('includes/config.php');
require_once('includes/db_connect.php');
require_once('includes/header.php');
if (isset($_SESSION['toast_message'])) {
    echo '<script type="text/javascript">
        window.onload = function() {
            showToast("' . $_SESSION['toast_message'] . '");
        };
    </script>';
    unset($_SESSION['toast_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thriftify - Login/Signup</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>
    <div id="container" class="container">
        <div class="row">
            <!-- SIGN UP -->
            <div class="col align-items-center flex-col sign-up">
                <div class="form-wrapper align-items-center">
                    <div class="form sign-up">
                        <form action="<?php echo BASE_URL; ?>includes/signup_process.php" method="post">
                            <div class="input-group">
                                <i class='bx bxs-user'></i>
                                <input type="text" name="username" placeholder="Username" required>
                            </div>
                            <div class="input-group">
                                <i class='bx bx-mail-send'></i>
                                <input type="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input type="password" name="password" placeholder="Password" required>
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input type="password" name="confirm_password" placeholder="Confirm password" required>
                            </div>
                            <button type="submit">Sign up</button>
                            <p>
                                <span>Already have an account?</span>
                                <b onclick="toggle()" class="pointer">Log In here</b>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END SIGN UP -->
            <!-- SIGN IN -->
            <div class="col align-items-center flex-col sign-in">
                <div class="form-wrapper align-items-center">
                    <div class="form sign-in">
                        <form action="<?php echo BASE_URL; ?>includes/login_process.php" method="post">
                            <div class="input-group">
                                <i class='bx bxs-user'></i>
                                <input type="text" name="username" placeholder="Username" required>
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input type="password" name="password" placeholder="Password" required>
                            </div>
                            <button type="submit">Log In</button>
                            <p><b>Forgot password?</b></p>
                            <p>
                                <span>Don't have an account?</span>
                                <b onclick="toggle()" class="pointer">Sign up here</b>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END SIGN IN -->
        </div>
        <div class="row content-row">
            <div class="col align-items-center flex-col">
                <div class="text sign-in">
                    <h2>Welcome To Thriftify</h2>
                </div>
                <div class="img sign-in"></div>
            </div>
            <div class="col align-items-center flex-col">
                <div class="img sign-up"></div>
                <div class="text sign-up">
                    <h2>Join Thriftify</h2>
                </div>
            </div>
        </div>
    </div>

    <script>
        let container = document.getElementById('container');

        const toggle = () => {
            container.classList.toggle('sign-in');
            container.classList.toggle('sign-up');
        }

        setTimeout(() => {
            container.classList.add('sign-in');
        }, 200);
    </script>
</body>
<div id="toast" class="toast">This is a toast message!</div>

<style>
.toast {
    visibility: hidden;
    min-width: 250px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 50%;
    bottom: 30px;
    transform: translateX(-50%);
    font-size: 17px;
}

.toast.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@keyframes fadein {
    from {bottom: 0; opacity: 0;}
    to {bottom: 30px; opacity: 1;}
}

@keyframes fadeout {
    from {bottom: 30px; opacity: 1;}
    to {bottom: 0; opacity: 0;}
}
</style>

<script>
function showToast(message) {
    var toast = document.getElementById("toast");
    toast.innerText = message;
    toast.className = "toast show";
    setTimeout(function() { toast.className = toast.className.replace("show", ""); }, 3000);
}
</script>
</html>
