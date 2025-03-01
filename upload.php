<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: upload_form.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Song</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Upload a New Song</h2>
    <form action="upload_process.php" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="artist">Artist:</label>
        <input type="text" id="artist" name="artist" required><br><br>
        
        <label for="album">Album:</label>
        <input type="text" id="album" name="album"><br><br>
        
        <label for="audioFile">Select audio file:</label>
        <input type="file" id="audioFile" name="audioFile" accept="audio/*" required><br><br>
        
        <input type="submit" value="Upload">
    </form>
</body>
</html>
