<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Convert ID to integer
    $id = intval($_POST['id']);

    // Prepare the delete statement
    $stmt = $db->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement and redirect to index.php
    $stmt->execute();
    header("Location: index.php");
    exit(); // Ensure no further code execution after redirection

    $stmt->close();
}

$db->close();
?>
