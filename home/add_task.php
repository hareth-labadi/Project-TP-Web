<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $category_id = isset($_POST['category_id']) && !empty($_POST['category_id']) ? $_POST['category_id'] : NULL;

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        if (empty($description)) {
            $_SESSION['error_message'] = "Task description cannot be empty.";
            header("Location: index.php");
            exit();
        }

        $stmt = $db->prepare("INSERT INTO tasks (description, user_id, category_id) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Error: " . $db->error);
        }
        $stmt->bind_param("sii", $description, $user_id, $category_id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error: " . $stmt->error;
            header("Location: index.php");
            exit();
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
