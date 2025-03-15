<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: login.php");
    exit();
}

include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $teacher_id = $_POST["teacher_id"];
    $subject = trim($_POST["subject"]);
    $grade = $_POST["grade"];

    if (empty($student_id) || empty($teacher_id) || empty($subject) || empty($grade)) {
        $_SESSION["error"] = "❌ All fields are required.";
        header("Location: enter_grades.php");
        exit();
    }

    $sql = "INSERT INTO grades (student_id, teacher_id, subject, grade) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("iisd", $student_id, $teacher_id, $subject, $grade);
        if ($stmt->execute()) {
            $_SESSION["success"] = "✅ Grade added successfully!";
        } else {
            $_SESSION["error"] = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION["error"] = "❌ SQL Error: " . $conn->error;
    }

    $conn->close();
    header("Location: enter_grades.php");
    exit();
}
?>
