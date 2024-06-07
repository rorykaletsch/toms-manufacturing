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

// Fetch orders for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

if ($result->num_rows > 0) {
    // Store orders in an array
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    echo "No orders found.";
}

// Fetch order items for each order
foreach ($orders as &$order) {
    $sql = "SELECT orderitems.*, products.name FROM orderitems JOIN products ON orderitems.product_id = products.product_id WHERE orderitems.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $order['order_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $order_items = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $order_items[] = $row;
        }
    }
    $order['items'] = $order_items;
}

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
    <link rel="stylesheet" href="/dealer-portal/assets/css/orders.css">
    <script src="https://kit.fontawesome.com/4feafd16e4.js" crossorigin="anonymous"></script>
    <title>My Orders</title>
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

    <div class="intro-text">
        <p class="roboto-regular">
            Here you can find the details of all your past orders. Review your order history, track the status of your current orders, and get information on each order's items and total amount.
        </p>
    </div>

    <div class="orders-list">
        <?php
        if (!empty($orders)) {
            foreach ($orders as $order) {
                echo '
                <div class="order-card">
                    <h4 class="roboto-bold order-id">Order ID: ' . $order["order_id"] . '</h4>
                    <p class="roboto">Order Date: ' . $order["order_date"] . '</p>
                    <p class="roboto">Total Amount: R ' . $order["total_amount"] . '</p>
                    <div class="order-items">';
                
                if (!empty($order['items'])) {
                    foreach ($order['items'] as $item) {
                        echo '
                        <div class="order-item">
                            <p class="roboto">Product: ' . $item["name"] . '</p>
                            <p class="roboto">Quantity: ' . $item["quantity"] . '</p>
                        </div>';
                    }
                } else {
                    echo '<p class="roboto">No items found for this order.</p>';
                }
                
                echo '
                    </div>
                </div>';
            }
        } else {
            echo '<p>No orders found.</p>';
        }
        ?>
    </div>
    <script src="/dealer-portal/assets/js/index.js"></script>
</body>

</html>
