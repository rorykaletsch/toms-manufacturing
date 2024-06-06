<?php
session_start();

include 'C:/xampp/htdocs/dealer-portal/config.php';

$conn = getDBConnection();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not, redirect to the login page
    header("Location: /dealer-portal/pages/login/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Query database to check if the user is admin
$query = "SELECT is_admin FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row["is_admin"] != 1) {
    header("Location: /dealer-portal/index.php");
    exit;
}

$stmt->close();

// Query for products
$productQuery = "SELECT * FROM products";
$productResult = $conn->query($productQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/dealer-portal/assets/css/admin.css">
    <script src="https://kit.fontawesome.com/4feafd16e4.js" crossorigin="anonymous"></script>
    <title>Tom's Manufacturing - Admin</title>
</head>

<body>
    <div class="nav-block" id="header">
        <div class="title">
            <a class="heading" href="/dealer-portal/pages/admin/admin.php">
                <h1 class="lobster-regular">Tom's Manufacturing</h1>
            </a>
        </div>
        <div class="nav-buttons">
            <ul class='nav-list'>
                <li class="nav-icon"><i id="admin-portal"class="fa-solid fa-toolbox"></i>
                    <h3 class="roboto-medium">Admin Portal</h3>
                </li>
                <li class="nav-icon"><i id="website" class="fa-solid fa-house"></i>
                    <h3 class="roboto-medium">Website</h3>
                </li>
            </ul>
        </div>
    </div>

    <div class="heading-block">
    <h3 class="roboto-medium">Admin Portal</h3>
    <div class="heading-block-buttons">
        <button class="add-product-button" id="add-product">Add Product</button>
        <button class="update-product-button" id="update-product">Update Product</button>
        <button class="delete-product-button">Delete Product</button>
    </div>
</div>

    <div class="product-list">
        <?php
        if ($productResult->num_rows > 0) {
            while ($product = $productResult->fetch_assoc()) {
                echo '
                <a href="/dealer-portal/pages/admin/product.php?id=' . $product["product_id"] . '" class="product-card-link">
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
                    </div>
                </a>';
            }
        } else {
            echo '<p>No products found.</p>';
        }
        $conn->close();
        ?>
    </div>
</body>
<script src="/dealer-portal/assets/js/admin.js"></script>
</html>