<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "schoolmanagement";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hash the new password
$new_password = password_hash("admin123", PASSWORD_DEFAULT);
$user = "admin1";

// Update password in the database
$sql = "UPDATE users SET password='$new_password' WHERE username='$user'";

if ($conn->query($sql) === TRUE) {
    echo "✅ Password updated successfully! Now login with admin1 / admin123";
} else {
    echo "❌ Error updating password: " . $conn->error;
}

// Close connection
$conn->close();
?>
