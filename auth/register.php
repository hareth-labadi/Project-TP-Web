<?php
include '../home/config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header-container">
        <a href="../home/index.php">
            <img src="../icons/icon.svg" alt="SVG Image">
            <h1>To-Do List</h1>
        </a>
    </div>
    <div class="form-container">
        <form action="register.php" method="post">
            <h2>Register</h2>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
            <button type="button" class="secondary" onclick="window.location.href='login.php'">Back to Login</button>
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
