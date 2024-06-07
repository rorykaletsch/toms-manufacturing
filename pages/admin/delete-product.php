<?php
session_start();
include 'C:/xampp/htdocs/dealer-portal/config.php';

$conn = getDBConnection();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
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

// Handle form submission to delete the product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_select'])) {
    $product_id = intval($_POST['product_select']);

    $query = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "Product deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all products
$query = "SELECT product_id, name FROM products";
$result = $conn->query($query);
$products = $result->fetch_all(MYSQLI_ASSOC);

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
    <link rel="stylesheet" href="/dealer-portal/assets/css/delete-product.css">
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
                <li class="nav-icon"><i id="admin-portal" class="fa-solid fa-toolbox"></i>
                    <h3 class="roboto-medium">Admin Portal</h3>
                </li>
                <li class="nav-icon"><i id="website" class="fa-solid fa-house"></i>
                    <h3 class="roboto-medium">Website</h3>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="container">
        <h2>Delete Product</h2>
        <form id="delete-form" class="product-form" method="post">
            <label for="product_select">Select Product:</label>
            <select id="product_select" name="product_select" required>
                <option value="">Select a product</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['product_id'] ?>"><?= $product['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Delete Product">
        </form>
    </div>
</body>
<script src="/dealer-portal/assets/js/delete-product.js"></script>
</html>
