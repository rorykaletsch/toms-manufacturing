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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, email, password_hash, first_name, last_name, address, phone, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $username, $email, $password_hash, $first_name, $last_name, $address, $phone, $is_admin);
    if ($stmt->execute()) {
        echo "New user added successfully!";
    } else {
        echo "Error: " . $stmt->error;
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/dealer-portal/assets/css/add-user.css">
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
    
    <div class="container">
        <h2>Add New User</h2>
        <br>
        <p>Add new username, password, email address and other user attributes here.</p>
        <br>
        <form action="add-user.php" method="post" enctype="multipart/form-data" class="user-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name">

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name">

            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone">

            <div class="checkbox-container">
                <label for="is_admin">Is Admin:</label>
                <input type="checkbox" id="is_admin" name="is_admin">
            </div>

            <input type="submit" value="Add User">
        </form>
    </div>

    <script src="/dealer-portal/assets/js/users.js"></script>
</body>
</html>