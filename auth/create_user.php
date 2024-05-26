<?php
include '../home/config.php';

$username = 'testuser'; // Replace with your desired username
$password = 'testpassword'; // Replace with your desired password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "User created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$db->close();
?>
