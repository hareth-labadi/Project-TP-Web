<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['category_name'];

    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Validate input
        if (empty($category_name)) {
            $_SESSION['error_message'] = "Category name cannot be empty.";
            header("Location: index.php");
            exit();
        }

        // Insert category into the database
        $stmt = $db->prepare("INSERT INTO categories (name, user_id) VALUES (?, ?)");
        if ($stmt === false) {
            die("Error: " . $db->error);
        }
        $stmt->bind_param("si", $category_name, $user_id);

        if ($stmt->execute()) {
            // Redirect after successful insertion
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "You need to log in first.";
        header("Location: index.php");
        exit();
    }
}

$db->close();
?>
