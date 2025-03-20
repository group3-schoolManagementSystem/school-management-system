<?php
session_start();
include("db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Invalid student ID.");
}

$student_id = intval($_GET['id']);
$sql = "UPDATE students SET is_deleted = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {
    $message = "✅ Student deleted successfully!";
} else {
    $message = "❌ Error deleting student: " . $stmt->error;
}

$stmt->close();
$conn->close();
header("Location: teacher_dashboard.php?message=" . urlencode($message));
exit();
?>
