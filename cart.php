<?php
require_once('includes/header.php');
require_once('includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = get_db_connection();

// Handle adding items to the cart
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    $user_id = $_SESSION['user_id'];
    $quantity = 1; // Default quantity is 1

    // Check if the item is already in the cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $exists = $stmt->fetch();

    if ($exists) {
        // Update the quantity if the item already exists
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$quantity, $user_id, $product_id]);
        $_SESSION['message'] = "Quantity updated in cart."; 
    } else {
        // Insert the item into the cart
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
        $_SESSION['message'] = "Item added to cart."; 
    }

    // Redirect back to the cart
    header('Location: cart.php');
    exit;
}

// Handle removing items from the cart
if (isset($_GET['remove'])) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $_GET['remove']]);
    $_SESSION['message'] = "Item removed from cart.";
    header('Location: cart.php');
    exit;
}

// Handle updating cart quantities
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$quantity, $_SESSION['user_id'], $product_id]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$_SESSION['user_id'], $product_id]);
        }
    }
    $_SESSION['message'] = "Cart updated.";
    header('Location: cart.php');
    exit;
}

// Fetch cart items
$stmt = $pdo->prepare("SELECT c.*, p.title, p.price, p.image 
                      FROM cart c 
                      JOIN products p ON c.product_id = p.id 
                      WHERE c.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>

<main id="cart">
  <h1>Your Cart</h1>
  <div class="container-fluid">
    <div class="row align-items-start">
      <div class="col-12 col-sm-8 cart-items">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?> 
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <form method="post">
                <?php foreach ($cart_items as $item): ?>
                    <?php $item_total = $item['price'] * $item['quantity']; $total += $item_total; ?>
                    <div class="cartItem row align-items-start">
                        <div class="col-3 mb-2">
                            <img class="w-100" src="<?php echo BASE_URL . UPLOADS_DIR . PRODUCTS_DIR . $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                        </div>
                        <div class="col-5 mb-2">
                            <h6 class=""><?php echo $item['title']; ?></h6>
                            <p class="mb-0">Quantity: <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1"></p>
                        </div>
                        <div class="col-2">
                            <p class="cartItemPrice p-1 text-center">Rs <?php echo number_format($item['price'], 2); ?></p>
                        </div>
                        <div class="col-2">
                            <a href="?remove=<?php echo $item['product_id']; ?>" class="btn-remove">Remove</a>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>
                <div class="total">
                    <h5>Total</h5>
                    <p>Rs <?php echo number_format($total, 2); ?></p>
                </div>
                <button type="submit" name="update_cart" class="btn-update">Update Cart</button>
                <a href="<?php echo BASE_URL; ?>checkout.php" class="btn-checkout">Proceed to Checkout</a>
            </form>
        <?php endif; ?>
      </div>
      <div class="col-12 col-sm-4 p-3 proceed form">
        <!-- Add subtotal, tax, and total calculation here if needed -->
      </div>
    </div>
  </div>
</main>

<?php require_once('includes/footer.php'); ?>
