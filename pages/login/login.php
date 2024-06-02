<?php
// Start a session
session_start();

// Include the config file
require_once 'C:/xampp/htdocs/dealer-portal/config.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Get the database connection
    $conn = getDBConnection();

    // Prepare and bind
    $stmt = $conn->prepare("SELECT user_id, username, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username, $password_hash);
        $stmt->fetch();

        // Verify the password
        if (password_verify($input_password, $password_hash)) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            // Redirect to a secure page
            header("C:/xampp/htdocs/dealer-portal/index.php");
            exit();
        } else {
            // Invalid password
            $error = "Invalid password.";
        }
    } else {
        // Invalid username
        $error = "Invalid username.";
    }

    $stmt->close();
    $conn->close();
}
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
    <link rel="stylesheet" href="/dealer-portal/assets/css/login.css">
    <script src="https://kit.fontawesome.com/4feafd16e4.js" crossorigin="anonymous"></script>
    <title>Login - Tom's Manufacturing</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <h2 class="login-title lobster-regular">Tom's Manufacturing</h2>
        <form action="/dealer-portal/login.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control roboto-medium" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control roboto-medium" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="roboto-bold login-button">Login</button>
        </form>
        <?php if (isset($error)) { echo '<div class="alert alert-danger">' . $error . '</div>'; } ?>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
