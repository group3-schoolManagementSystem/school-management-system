<?php
require "db.php";
session_start(); // Start the session

// Enable error reporting to catch any issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if form data is set
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $sql = "SELECT user_id, username, password_hash FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check the result and verify the password
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id']; // Store user ID in session
            $_SESSION['username'] = $user['username']; // Store username in session
            echo json_encode(["message" => "login successful", "user_id" => $user['user_id']]);
        } else {
            echo json_encode(["message" => "invalid password"]);
        }
    } else {
        echo json_encode(["message" => "user not found"]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["message" => "All fields are required."]);
}
?>
