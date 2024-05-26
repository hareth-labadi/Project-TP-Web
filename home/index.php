<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT id, description, status, category FROM tasks WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $user_id);
} else {
    $sql = "SELECT id, description, status, category FROM tasks WHERE user_id IS NULL";
    $stmt = $db->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="icon-container">
        <img src="../icons/icon.svg" alt="SVG Image">
        <h1>To-Do List</h1>    
        <?php if (isset($_SESSION['user_id'])): ?>
            <button class="login-button" onclick="window.location.href='../auth/logout.php'">Logout</button>
        <?php else: ?>
            <button class="login-button" onclick="window.location.href='../auth/login.php'">Login</button>
            <button class="register-button" onclick="window.location.href='../auth/register.php'">Register</button>    
        <?php endif; ?>
    </div>

    <div class="container">

        <form id="taskForm" action="add_task.php" method="post">
            <div class="task-input-container">
                <input type="text" id="taskInput" name="description" placeholder="✍️   New task...">
                <button type="submit" class="add-button"><img src="../icons/add.svg" alt="Add Icon"></button>
            </div>
        </form>
        
        <h2>Tasks:</h2>

        <ul id="taskList">
            <?php
            include 'config.php';

            $sql = "SELECT id, description, status FROM tasks";
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li" . ($row['status'] == 'completed' ? " class='completed'" : "") . ">";
                    echo "<form action='update_task.php' method='post'>";
                    echo "<input type='hidden' name='task_id' value='" . $row['id'] . "'>";
                    echo "<input type='checkbox' class='custom-checkbox' name='status' value='completed'" . ($row['status'] == 'completed' ? 'checked' : '') . " onchange='this.form.submit()'>";
                    echo "<input type='text' name='description' value='" . htmlspecialchars($row['description']) . "'" . ($row['status'] == 'completed' ? " style='text-decoration: line-through; color:white;'" : "") . ">";
                    echo "</form>";
                    echo "<form action='delete_task.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' class='del-button'><img src='../icons/delete.svg' alt='Delete Icon'></button>";
                    echo "</form>";
                    echo "</li>";
                }
            } else {
                echo "<img src='../icons/notask.svg' alt='SVG Image' class='notask-button'>"; 
            }

            $db->close();
            ?>
        </ul>
    </div>
</body>
</html>