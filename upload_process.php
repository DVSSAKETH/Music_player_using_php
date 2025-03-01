<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: upload_form.php");
    exit;
}

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $album = $_POST['album'] ?? null;

    // File upload handling
    $file_name = basename($_FILES['audioFile']['name']);
    $file_path = 'uploads/' . $file_name;
    $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($file_path)) {
        echo "File already exists.";
    } else {
        // Allow certain file formats (MP3, WAV, OGG)
        if ($file_type != "mp3" && $file_type != "wav" && $file_type != "ogg") {
            echo "Only MP3, WAV, and OGG files are allowed.";
        } else {
            // Attempt to upload file
            if (move_uploaded_file($_FILES['audioFile']['tmp_name'], $file_path)) {
                // Insert song details into database
                $sql = "INSERT INTO songs (title, artist, album, file_path) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $title, $artist, $album, $file_path);
                
                if ($stmt->execute()) {
                    echo "Song uploaded successfully.";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                $stmt->close();
            } else {
                echo "Error uploading file.";
            }
        }
    }
}

$conn->close();
?>
