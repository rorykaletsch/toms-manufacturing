<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not, redirect to the login page
    header("Location: /dealer-portal/pages/login/login.php");
    exit;
}

include 'C:/xampp/htdocs/dealer-portal/config.php';

// Call for DB opening
$conn = getDBConnection();

// Fetch product attributes
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    // Store product attributes in an arr
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "0 results";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Init cart in session variable
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if product is already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If exists update quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Add product if not
        $_SESSION['cart'][$product_id] = $quantity;
    }
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
    <link rel="stylesheet" href="/dealer-portal/assets/css/index.css">
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

    <div class="intro-text">
        <p class="roboto-regular">
            At Tom's Manufacturing, we pride ourselves on being a leading provider of heavy machinery equipment and spares. With a commitment to excellence and a passion for innovation, we deliver top-quality products designed to meet the rigorous demands of the industry. Our extensive range of machinery and spare parts ensures that you have everything you need to keep your operations running smoothly.
        </p>
    </div>

    <div class="product-list">
        <?php
        if (!empty($products)) {
            foreach ($products as $product) {
                echo '
                <div class="product-card">
                    <img src="' . $product["image_path"] . '" alt="' . $product["name"] . '" class="product-image">
                    <div class="product-info">
                        <h4 class="roboto-bold product-name">' . $product["name"] . '</h4>
                        <br>
                        <p class="roboto">' . $product["description"] . '</p>
                    </div>
                    <div class="product-meta">
                        <p class="roboto-medium">SKU: ' . $product["product_id"] . '</p>
                        <p class="roboto-medium">Quantity: ' . $product["quantity"] . '</p>
                        <p class="roboto-medium">Category: ' . $product["category"] . '</p>
                    </div>
                    <div class="product-price roboto-bold">
                        <h4>R ' . $product["price"] . '</h4>
                    </div>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="product_id" value="' . $product["product_id"] . '">
                        <input type="number" name="quantity" min="1" max="' . $product["quantity"] . '" value="1" required>
                        <button type="submit" class="add-to-cart">Add to Cart</button>
                    </form>
                </div>';
            }
        } else {
            echo '<p>No products found.</p>';
        }
        ?>
    </div>
    <script src="/dealer-portal/assets/js/index.js"></script>
</body>

</html>