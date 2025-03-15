<?php
$servername = "localhost";  // Change if using a different host
$username = "root";         // Your database username
$password = "";             // Your database password (empty if using default XAMPP settings)
$dbname = "schoolmanagement";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
