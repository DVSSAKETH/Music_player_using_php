<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_player";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve songs from the database
$sql = "SELECT * FROM songs";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome to the Music Player</h1>
    <?php
    if (isset($_SESSION['username'])) {
        echo "<p>Logged in as: " . $_SESSION['username'] . "</p>";
    }
    ?>
    <h3>Available Songs:</h3>
    <div class="song-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="song">';
                echo '<p><strong>' . htmlspecialchars($row['title']) . '</strong> by ' . htmlspecialchars($row['artist']) . '</p>';
                echo '<p>' . (isset($row['album']) ? htmlspecialchars($row['album']) : 'N/A') . '</p>';
                echo '<audio controls><source src="' . htmlspecialchars($row['file_path']) . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
                echo '</div>';
            }
        } else {
            echo "<p>No songs available.</p>";
        }
        ?>
    </div>
    <?php
    // Display upload link only if logged in as admin
    if ($_SESSION['username'] === 'admin') {
        echo '<p><a href="upload_form.php" class="button">Upload a new song</a></p>';
    }
    ?>
    <p><a href="logout.php" class="button">Logout</a></p>
</body>
</html>

<?php
$conn->close();
?>
