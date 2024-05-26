<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $category = $_POST['category'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $db->prepare("INSERT INTO tasks (description, category, status, user_id) VALUES (?, ?, 'pending', ?)");
        $stmt->bind_param("ssi", $description, $category, $user_id);
    } else {
        $stmt = $db->prepare("INSERT INTO tasks (description, category, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("ss", $description, $category);
    }

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
