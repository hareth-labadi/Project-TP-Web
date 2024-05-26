<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {

    $id = intval($_POST['id']);


    $stmt = $db->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);


    $stmt->execute();
    header("Location: index.php");
    exit();

    $stmt->close();
}

$db->close();
?>
