<?php
session_start();

// Ensure the correct path to db.php
include($_SERVER['DOCUMENT_ROOT'] . "/webproject/php_files/db.php"); 

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

// Check if teacher ID is provided
if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); // Ensure ID is an integer

    // Debugging: Confirm teacher ID is received
    echo "Teacher ID received: " . $id . "<br>";

    // Check if the teacher exists before deletion
    $check_sql = "SELECT id FROM users WHERE id = ? AND role = 'teacher'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Delete the teacher
        $delete_sql = "DELETE FROM users WHERE id = ? AND role = 'teacher'";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id);

        if ($delete_stmt->execute()) {
            $_SESSION["success"] = "✅ Teacher removed successfully!";
        } else {
            $_SESSION["error"] = "❌ Error removing teacher.";
        }
        $delete_stmt->close();
    } else {
        $_SESSION["error"] = "❌ Teacher not found.";
    }

    $check_stmt->close();
} else {
    $_SESSION["error"] = "❌ No teacher ID provided.";
}

// Redirect back to manage_teachers.php
header("Location: manage_teachers.php");
exit();
?>
