<?php
require "db.php";

// Enable error reporting to catch any issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if form data is set
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement to insert user data
    $sql = "INSERT INTO users (user_id, username, email, password_hash) VALUES (UUID(), ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password_hash);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "All fields are required.";
}
?>
