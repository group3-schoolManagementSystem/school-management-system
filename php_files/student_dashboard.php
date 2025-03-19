<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if not logged in as a student
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: login.php");
    exit();
}

// database configuration
include("db.php");

$student_id = $_SESSION["user_id"]; // Get the student's ID from the session

// Fetch the student's details from the database
$sql = "SELECT student_name, email, class, dob FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("❌ SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the student was found
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc(); // Fetch student details
} else {
    die("❌ Student not found.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Student Dashboard
                <a href="logout.php" class="btn btn-danger float-end">Logout</a>
            </div>
            <div class="card-body">
                <?php if ($student) : ?>
                    <h4>Welcome, <?= htmlspecialchars($student['student_name']) ?>!</h4>
                    <ul class="list-group">
                        <li class="list-group-item">Class: <?= htmlspecialchars($student['class']) ?></li>
                        <li class="list-group-item">Email: <?= htmlspecialchars($student['email']) ?></li>
                        <li class="list-group-item">Date of Birth: <?= htmlspecialchars($student['dob']) ?></li>
                    </ul>
                <?php else : ?>
                    <div class="alert alert-danger">Student data not found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>