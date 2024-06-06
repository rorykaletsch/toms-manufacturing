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

// Handle AJAX request to fetch product details
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    $query = "SELECT name, description, price, quantity, category FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    echo json_encode($product);

    $stmt->close();
    $conn->close();
    exit;
}

// Handle form submission to update the product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_select'])) {
    $product_id = intval($_POST['product_select']);
    $product_name = htmlspecialchars($_POST['product_name']);
    $product_description = htmlspecialchars($_POST['product_description']);
    $product_price = floatval($_POST['product_price']);
    $product_quantity = intval($_POST['product_quantity']);
    $product_category = htmlspecialchars($_POST['product_category']);
    $product_image = $_FILES['product_image'];

    $query = "UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, category = ? WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdisi", $product_name, $product_description, $product_price, $product_quantity, $product_category, $product_id);

    if ($stmt->execute()) {
        // If a new image is uploaded, handle the image update
        if ($product_image['name']) {
            $upload_dir = 'C:/xampp/htdocs/dealer-portal/assets/images/';
            $upload_file = $upload_dir . basename($product_image['name']);
            $image_path = '/dealer-portal/assets/images/' . basename($product_image['name']);

            $check = getimagesize($product_image['tmp_name']);
            if ($check !== false && move_uploaded_file($product_image['tmp_name'], $upload_file)) {
                $imageQuery = "UPDATE products SET image_path = ? WHERE product_id = ?";
                $imageStmt = $conn->prepare($imageQuery);
                $imageStmt->bind_param("si", $image_path, $product_id);
                $imageStmt->execute();
                $imageStmt->close();
            }
        }
        echo "Product updated successfully.";
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
    <link rel="stylesheet" href="/dealer-portal/assets/css/update-product.css">
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
        <h2>Update Product</h2>
        <form id="update-form" class="product-form" method="post" enctype="multipart/form-data">
            <label for="product_select">Select Product:</label>
            <select id="product_select" name="product_select" onchange="loadProductDetails()" required>
                <option value="">Select a product</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['product_id'] ?>"><?= $product['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required>

            <label for="product_description">Product Description:</label>
            <textarea id="product_description" name="product_description" required></textarea>

            <label for="product_price">Product Price:</label>
            <input type="number" id="product_price" name="product_price" step="0.01" required>

            <label for="product_quantity">Product Quantity:</label>
            <input type="number" id="product_quantity" name="product_quantity" required>

            <label for="product_category">Product Category:</label>
            <input type="text" id="product_category" name="product_category" required>

            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image" accept="image/*">

            <input type="submit" value="Update Product">
        </form>
    </div>
    <script src="/dealer-portal/assets/js/product.js"></script>
</body>

</html>
