<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not, redirect to the login page
    header("Location: /dealer-portal/pages/login/login.php");
    exit;
}

include 'C:/xampp/htdocs/dealer-portal/config.php';

$conn = getDBConnection();

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Clear cart if requested
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    $cart_items = [];
}

$product_details = [];

if (!empty($cart_items)) {
    $product_ids = array_keys($cart_items);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $product_details[] = $row;
    }
}

$total_price = 0;
foreach ($product_details as $product) {
    $total_price += $product['price'] * $cart_items[$product['product_id']];
}

// Calculate shipping and VAT
$shipping_fee = 1000.00;
$vat = 0.15 * $total_price;
$total_with_charges = $total_price + $shipping_fee + $vat;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/dealer-portal/assets/css/cart.css">
    <script src="https://kit.fontawesome.com/4feafd16e4.js" crossorigin="anonymous"></script>
    <title>Tom's Manufacturing</title>
</head>

<body>
    <div class="nav-block" id="header">
        <div class="title">
            <a class="heading" href="/dealer-portal/index.php">
                <h1 class="lobster-regular">Tom's Manufacturing</h1>
            </a>
        </div>
        <div class="nav-buttons">
            <ul class='nav-list'>
                <li class="nav-icon"><i id="home" class="fa-solid fa-house"></i>
                    <h3 class="roboto-medium">Home</h3>
                </li>
                <li class="nav-icon"><i id="orders" class="fa-solid fa-truck"></i>
                    <h3 class="roboto-medium">Orders</h3>
                </li>
                
                <li class="nav-icon"><i id="profile" class="fa-solid fa-user"></i>
                    <h3 class="roboto-medium">Profile</h3>
                </li>
                <li class="nav-icon"><i id="cart" class="fa-solid fa-cart-shopping"></i>
                    <h3 class="roboto-medium">Cart</h3>
                </li>
            </ul>
        </div>
    </div>

    <div class="heading-block">
        <h3 class="roboto-medium">Cart</h3>
    </div>
    
    <div class="intro-text">
        <p class="roboto-regular">
            Please note that every single order no matter how big or small, far or wide around
            South Africa has a flat shipping rate of R1000.00.
        </p>
    </div>

    <div class="cart-items">
        <?php if (!empty($product_details)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($product_details as $product): ?>
                        <tr>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $cart_items[$product['product_id']]; ?></td>
                            <td>R <?php echo $product['price']; ?></td>
                            <td>R <?php echo $product['price'] * $cart_items[$product['product_id']]; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3">Subtotal</td>
                        <td>R <?php echo $total_price; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">Flat Shipping Rate - Nationwide</td>
                        <td>R <?php echo $shipping_fee; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">VAT (15%)</td>
                        <td>R <?php echo number_format($vat, 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>R <?php echo number_format($total_with_charges, 2); ?></strong></td>
                    </tr>
                    </tbody>
            </table>
            <div class="cart-actions">
                <form action="place_order.php" method="POST" style="display: inline;">
                    <button type="submit" class="place-order-btn">Place Order</button>
                </form>
                <form action="cart.php" method="POST" style="display: inline;">
                    <input type="hidden" name="clear_cart" value="1">
                    <button type="submit" class="clear-cart-btn">Clear Cart</button>
                </form>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <script src="/dealer-portal/assets/js/index.js"></script>
</body>

</html>
