<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: login.php");
    exit();
}

include("db.php");

$teacher_id = $_SESSION["user_id"];
$sql = "SELECT g.id, s.student_name, g.subject, g.grade 
        FROM grades g 
        JOIN students s ON g.student_id = s.id
        WHERE g.teacher_id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    die("❌ SQL Error: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Student Grades</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Subject</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= htmlspecialchars($row['grade']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="teacher_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</body>
</html>
