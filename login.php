<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "music_player";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password']; // Retrieve hashed password from database

        // Verify password using password_verify
        if (password_verify($pass, $hashed_password)) {
            // Password is correct, set session and redirect
            $_SESSION['username'] = $user;
            header("Location: music_player.php"); // Redirect to main application page
            exit();
        } else {
            // Password is incorrect
            $login_error = "Invalid password";
        }
    } else {
        // No user found
        $login_error = "Username not found";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
     <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>User Login</h2>

    <?php if (isset($login_error)): ?>
        <p style="color: red;"><?php echo $login_error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <center><input type="submit" value="Login"></center>
    </form>

    <p>Don't have an account? <a href="register.php" class = "button">Register here</a></p>
</body>
</html>
