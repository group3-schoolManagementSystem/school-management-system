<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: login.php");
    exit();
}

include("db.php");

// Fetch students assigned to the logged-in teacher
$teacher_id = $_SESSION["user_id"];
$sql = "SELECT id, student_name FROM students WHERE added_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$students = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Enter Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; text-align: center; }
        .container { background: white; padding: 20px; margin-top: 30px; width: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .btn-submit { background-color: maroon; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter Grades</h2>
        <form action="process_grades.php" method="POST">
            <label>Student:</label>
            <select name="student_id" class="form-control" required>
                <option value="">Select Student</option>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['student_name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Subject:</label>
            <input type="text" name="subject" class="form-control" required>

            <label>Grade:</label>
            <input type="number" name="grade" class="form-control" step="0.01" required>

            <input type="hidden" name="teacher_id" value="<?= $teacher_id ?>">

            <button type="submit" class="btn btn-submit mt-3">Submit</button>
        </form>
    </div>
</body>
</html>
