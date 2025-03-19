<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: login.php");
    exit();
}
?>
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
<html lang="en">
<head>
    <title>Student Dashboard</title>
</head>
<body>
    <h2>Welcome, Student!</h2>
    <a href="logout.php">Logout</a>
</body>
</html>