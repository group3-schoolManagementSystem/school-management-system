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

// Fetch students assigned to the teacher
$sql = "SELECT id, student_name, class_id FROM students WHERE added_by = ? AND is_deleted = 0";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);
}
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = date("Y-m-d"); // Get today's date

    $valid_status = ["Present", "Absent", "Late"]; // Valid attendance statuses

    foreach ($_POST['attendance'] as $student_id => $status) {
        // Validate the attendance status
        if (!in_array($status, $valid_status)) {
            die("Invalid attendance status for student ID: " . $student_id);
        }

        // Get the class_id for the student (already fetched in the main query)
        while ($row = $result->fetch_assoc()) {
            if ($row['id'] == $student_id) {
                $class_id = $row['class_id'];
                break;
            }
        }

        // Insert attendance
        $sql = "INSERT INTO attendance (student_id, class_id, teacher_id, date, status) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE status = VALUES(status)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL Prepare Error: " . $conn->error . " Query: " . $sql);
        }
        
        $stmt->bind_param("iiiss", $student_id, $class_id, $teacher_id, $date, $status);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION["success"] = "✅ Attendance marked successfully!";
    header("Location: mark_attendance.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: maroon;
            padding: 15px;
        }
        .navbar a {
            color: white;
            margin: 15px;
            text-decoration: none;
            font-size: 18px;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: white;
            margin-top: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .button {
            padding: 10px 20px;
            background-color: maroon;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #800000;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="teacher_dashboard.php">🏠 Home</a>
        <a href="manage_students.php">Manage Students</a>
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="view_grades.php">View Grades</a>
        <a href="logout.php" style="color: red;">Logout</a>
    </div>

    <div class="container">
        <h2>Mark Attendance</h2>
        <p>Select attendance for each student.</p>

        <?php if (isset($_SESSION["success"])): ?>
            <div class="alert alert-success"><?= $_SESSION["success"] ?></div>
            <?php unset($_SESSION["success"]); ?>
        <?php endif; ?>

        <form method="POST">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['student_name']) ?></td>
                            <td><?= htmlspecialchars($row['class_id']) ?></td>
                            <td>
                                <select name="attendance[<?= $row['id'] ?>]" class="form-select">
                                    <option value="Present">Present</option>
                                    <option value="Absent">Absent</option>
                                    <option value="Late">Late</option>
                                </select>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <button type="submit" class="button">Submit Attendance</button>
        </form>
    </div>
</body>
</html>
