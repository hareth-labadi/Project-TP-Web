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
        <button class="login-button" onclick="window.location.href='login.php'">Login</button>
    </div>
    <div class="container">
        <!-- Input field -->
        <form id="taskForm" action="add_task.php" method="post">
            <div class="task-input-container">
                <input type="text" id="taskInput" name="description" placeholder="✍️   New task...">
                <button type="submit" class="add-button"><img src="../icons/add.svg" alt="Add Icon"></button>
            </div>
        </form>
        
        <!-- Task list -->
        <h2>Tasks:</h2>
        <ul id="taskList">
            <?php
            include 'config.php';

            // Fetch tasks
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
                echo "<img src='../icons/notask.svg' alt='SVG Image' class='notask-button'>"; // Fixed single quote issue
            }

            $db->close();
            ?>
        </ul>
    </div>
</body>
</html>
