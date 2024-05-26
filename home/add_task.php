<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    $stmt = $db->prepare("INSERT INTO tasks (description, status, user_id) VALUES (?, 'pending', ?)");
    if ($stmt === false) {
        die("Error: " . $db->error);
    }
    $stmt->bind_param("si", $description, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$db->close();
?>
