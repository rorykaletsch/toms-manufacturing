<?php
$password = 'Admin1234'; // Replace with the actual password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Hashed Password: " . $hashed_password;
?>