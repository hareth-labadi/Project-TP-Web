<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];

    $stmt = $db->prepare("INSERT INTO tasks (description, status) VALUES (?, 'pending')");
    $stmt->bind_param("s", $description);
    $stmt->execute();

    // Redirect to index.php after successful insertion
    header("Location: index.php");
    exit(); // Ensure no further code execution after redirection

    $stmt->close();
}

$db->close();
?>
