<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /dealer-portal/pages/login/login.php");
    exit;
}

include 'C:/xampp/htdocs/dealer-portal/config.php';

$conn = getDBConnection();

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$user_id = $_SESSION['user_id'];
$total_amount = 0;
$shipping_fee = 1000;
$vat_rate = 0.15;

// Calculate the total amount
foreach ($cart_items as $product_id => $quantity) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $total_amount += $product['price'] * $quantity;
}

$vat = $total_amount * $vat_rate;
$total_amount += $shipping_fee + $vat;

// Insert the order
$stmt = $conn->prepare("INSERT INTO orders (user_id, status, total_amount) VALUES (?, 'Pending', ?)");
$stmt->bind_param("id", $user_id, $total_amount);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert the order items and update product quantities
foreach ($cart_items as $product_id => $quantity) {
    $stmt = $conn->prepare("SELECT price, quantity FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $price = $product['price'];
    $new_quantity = $product['quantity'] - $quantity;

    // Insert into orderitems
    $stmt = $conn->prepare("INSERT INTO orderitems (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
    $stmt->execute();

    // Update product quantity
    $stmt = $conn->prepare("UPDATE products SET quantity = ? WHERE product_id = ?");
    $stmt->bind_param("ii", $new_quantity, $product_id);
    $stmt->execute();
}

// Clear the cart
unset($_SESSION['cart']);

// Close the database connection
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
    <link rel="stylesheet" href="/dealer-portal/assets/css/place_order.css">
    <script src="https://kit.fontawesome.com/4feafd16e4.js" crossorigin="anonymous"></script>
    <title>Order Confirmation</title>
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

    <div class="thank-you-message">
        <h2 class="roboto-medium">Thank You for Your Order!</h2>
        <p class="roboto-regular">Your order has been successfully placed and will be at your factory,
            workshop or warehouse in the next couple days.
        </p>
        <p class="roboto-regular"> Orders will show up in the orders tab and a statement
        will be sent to your email address each month by our accounting team.</p>
        <p class="roboto-regular">Order Number: <?php echo $order_id; ?></p>
        <p class="roboto-regular">We appreciate your business, please contact us should you need anything.</p>
    </div>

    <script src="/dealer-portal/assets/js/index.js"></script>
</body>

</html>