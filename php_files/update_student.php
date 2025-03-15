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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["student_id"])) {
    $student_id = $_POST["student_id"];
    $student_name = $_POST["student_name"];
    $email = $_POST["email"];
    $class = $_POST["class"];
    $grade_level = $_POST["grade_level"];
    $dob = $_POST["dob"];
    $parent_contact_info = $_POST["parent_contact_info"];
    $teacher_id = $_SESSION["user_id"];

    $sql = "UPDATE students SET student_name=?, email=?, class=?, grade_level=?, dob=?, parent_contact_info=? 
            WHERE id=? AND added_by=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssii", $student_name, $email, $class, $grade_level, $dob, $parent_contact_info, $student_id, $teacher_id);

    if ($stmt->execute()) {
        $message = "✅ Student updated successfully!";
    } else {
        $message = "❌ Error updating student: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
