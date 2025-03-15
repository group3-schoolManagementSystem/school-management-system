<?php
session_start();
include("db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["student_id"])) {
    $student_id = intval($_POST["student_id"]);
    $name = $_POST["student_name"];
    $email = $_POST["email"];
    $class = $_POST["class"];
    $grade_level = $_POST["grade_level"];
    $dob = $_POST["dob"];
    $parent_contact = $_POST["parent_contact_info"];

    $sql = "UPDATE students SET student_name=?, email=?, class=?, grade_level=?, dob=?, parent_contact_info=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $email, $class, $grade_level, $dob, $parent_contact, $student_id);

    if ($stmt->execute()) {
        $message = "✅ Student updated successfully!";
    } else {
        $message = "❌ Error updating student: " . $stmt->error;
    }

    $stmt->close();
    header("Location: teacher_dashboard.php?message=" . urlencode($message));
    exit();
}

$conn->close();
?>
