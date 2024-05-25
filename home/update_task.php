<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id'])) {
    $id = $_POST['task_id'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'pending';

    $stmt = $db->prepare("UPDATE tasks SET description = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $description, $status, $id);
    $stmt->execute();

    // Redirect to index.php after successful update
    header("Location: index.php");
    exit(); // Ensure no further code execution after redirection

    $stmt->close();
}

$db->close();
?>
