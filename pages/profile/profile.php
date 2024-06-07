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
$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT username, email, first_name, last_name, address, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, address = ?, phone = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $username, $email, $first_name, $last_name, $address, $phone, $user_id);

    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . $conn->error;
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
    <link rel="stylesheet" href="/dealer-portal/assets/css/profile.css">
    <script src="https://kit.fontawesome.com/4feafd16e4.js" crossorigin="anonymous"></script>
    <title>My Profile</title>
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
            Update your profile information here. Please note that you cannot change your admin status.
        </p>
    </div>

    <div class="profile-form">
        <?php if (isset($message)) { echo '<p>' . $message . '</p>'; } ?>
        <form action="profile.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
    <script src="/dealer-portal/assets/js/index.js"></script>
</body>

</html>
