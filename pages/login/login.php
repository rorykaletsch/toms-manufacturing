<?php
include 'C:/xampp/htdocs/dealer-portal/config.php';

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDBConnection();

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        // Get the result
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            // Debugging: Check if session variables are set
            if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
                echo "Session variables set: user_id = " . $_SESSION['user_id'] . ", username = " . $_SESSION['username'];
            }

            // Redirect to the dashboard or home page
            header("Location: /dealer-portal/index.php");
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Error executing query: " . $stmt->error;
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
</head>

<body>
    <div class="login-container">
        <h2 class="login-title lobster-regular">Tom's Manufacturing</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" class="form-control roboto-medium" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control roboto-medium" id="password" name="password" placeholder="Password" required>
            </div>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger roboto-medium" style="margin-top: 10px;"><?php echo $error; ?></div>
            <?php } ?>
            <button type="submit" class="roboto-bold login-button">Login</button>
        </form>
    </div>
</body>

</html>
