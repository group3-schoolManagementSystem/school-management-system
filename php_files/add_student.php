<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: login.php");
    exit();
}

include("db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST["student_name"];
    $email = $_POST["email"];
    $class = $_POST["class"];
    $grade_level = $_POST["grade_level"];
    $dob = $_POST["dob"];
    $parent_contact_info = $_POST["parent_contact_info"];
    $added_by = $_SESSION["user_id"]; // Teacher ID

    if (!empty($student_name) && !empty($email) && !empty($class) && !empty($grade_level) && !empty($dob) && !empty($parent_contact_info)) {
        $sql = "INSERT INTO students (student_name, email, class, grade_level, dob, parent_contact_info, added_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $student_name, $email, $class, $grade_level, $dob, $parent_contact_info, $added_by);

        if ($stmt->execute()) {
            $message = "✅ Student added successfully!";
        } else {
            $message = "❌ Error adding student: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "⚠️ All fields are required!";
    }
}

$conn->close();
?>
