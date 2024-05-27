<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['id'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $stmt = $db->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        if ($stmt === false) {
            die("Error: " . $db->error);
        }
        $stmt->bind_param("ii", $task_id, $user_id);

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
