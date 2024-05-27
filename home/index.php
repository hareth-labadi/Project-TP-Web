<?php
session_start();
include 'config.php';

// Check if there's an error message
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Remove the error message from session
}

// Check if the user is logged in or set a temporary session user ID
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Set a temporary session user ID for testing purposes
    $user_id = -1; 
}

// Fetch tasks for the logged-in user or the temporary user ID
$sql = "SELECT tasks.id, tasks.description, tasks.status, tasks.category_id, categories.name AS category 
        FROM tasks 
        LEFT JOIN categories ON tasks.category_id = categories.id 
        WHERE tasks.user_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch categories for the logged-in user
$categoriesSql = "SELECT id, name FROM categories WHERE user_id = ?";
$categoriesStmt = $db->prepare($categoriesSql);
$categoriesStmt->bind_param("i", $user_id);
$categoriesStmt->execute();
$categoriesResult = $categoriesStmt->get_result();
$categories = [];
if ($categoriesResult->num_rows > 0) {
    while ($category = $categoriesResult->fetch_assoc()) {
        $categories[] = $category;
    }
}
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

    <?php if (isset($error_message)): ?>
        <h2 class="error-message"><?php echo $error_message; ?></h2>
    <?php endif; ?>

    <div class="container">
        <form id="categoryForm" action="add_category.php" method="post">
            <div class="category-input-container">
                <input type="text" id="categoryInput" name="category_name" placeholder="➕   New category...">
                <button type="submit" class="add-button"><img src="../icons/add.svg" alt="Add Icon"></button>
            </div>
        </form>

        <form id="taskForm" action="add_task.php" method="post">
            <div class="task-input-container">
                <input type="text" id="taskInput" name="description" placeholder="✍️   New task...">
                <select name="category_id">
                    <option value="">No Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="add-button"><img src="../icons/add.svg" alt="Add Icon"></button>
            </div>
        </form>
        
        <h2>Tasks:</h2>
        <ul id="taskList">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li <?php echo ($row['status'] == 'completed') ? "class='completed'" : ""; ?>>
                        <form action='update_task.php' method='post'>
                            <input type='hidden' name='task_id' value='<?php echo $row['id']; ?>'>
                            <input type='checkbox' class='custom-checkbox' name='status' value='completed' <?php echo ($row['status'] == 'completed') ? 'checked' : ''; ?> onchange='this.form.submit()'>
                            <input type='text' name='description' value='<?php echo htmlspecialchars($row['description']); ?>' <?php echo ($row['status'] == 'completed') ? "style='text-decoration: line-through; color:white;'" : ""; ?>>
                            <select name='category_id' onchange='this.form.submit()'>
                                <option value=''>No Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value='<?php echo $category['id']; ?>' <?php echo ($category['id'] == $row['category_id']) ? "selected" : ""; ?>><?php echo $category['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                        <form action='delete_task.php' method='post'>
                            <input type='hidden' name='id' value='<?php echo $row['id']; ?>'>
                            <button type='submit' class='del-button'><img src='../icons/delete.svg' alt='Delete Icon'></button>
                        </form>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <img src='../icons/notask.svg' alt='SVG Image' class='notask-button'> 
            <?php endif; ?>

            <?php $db->close(); ?>
        </ul>
    </div>
</body>
</html>
