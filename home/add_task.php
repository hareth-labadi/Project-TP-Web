<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    
    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Insert task with user ID
        $stmt = $db->prepare("INSERT INTO tasks (description, status, user_id) VALUES (?, 'pending', ?)");
        if ($stmt === false) {
            die("Error: " . $db->error);
        }
        $stmt->bind_param("si", $description, $user_id);

        if ($stmt->execute()) {
            // Redirect after successful insertion
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error occurred while adding task.";
            header("Location: index.php");
            exit();
        }

        $stmt->close();
    } else {
        // Set error message and redirect to index.php
        $_SESSION['error_message'] = "You need to login first!";
        header("Location: index.php");
        exit();
    }
}

$db->close();
?>
