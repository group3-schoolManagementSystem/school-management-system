<?php
// db.php - Database connection file

$servername = "localhost"; // Your database server (localhost for local server)
$username = "root"; // Your MySQL username (default for XAMPP is 'root')
$password = ""; // Your MySQL password (default for XAMPP is an empty string)
$dbname = "schoolmanagement"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
