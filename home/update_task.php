<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['task_id'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'pending';
    $description = $_POST['description'];
    $category_id = isset($_POST['category_id']) && !empty($_POST['category_id']) ? $_POST['category_id'] : NULL;

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $stmt = $db->prepare("UPDATE tasks SET description = ?, status = ?, category_id = ? WHERE id = ? AND user_id = ?");
        if ($stmt === false) {
            die("Error: " . $db->error);
        }
        $stmt->bind_param("sssii", $description, $status, $category_id, $task_id, $user_id);

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
