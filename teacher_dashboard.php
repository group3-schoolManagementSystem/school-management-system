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
$sql = "SELECT id, student_name, email, class, grade_level, dob, parent_contact_info 
        FROM students WHERE added_by = ? AND is_deleted = 0";

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
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
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
            display: inline-block;
            padding: 10px 20px;
            background-color: maroon;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
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
        <a href="enter_grades.php">Enter Grades</a>
        <a href="view_grades.php">View Grades</a>
        <a href="logout.php" style="color: red;">Logout</a>
    </div>

    <div class="container">
        <h2>Welcome, Teacher!</h2>
        <p>Manage your students and classes here.</p>

        <h3>Your Students</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Class</th>
                    <th>Grade Level</th>
                    <th>Date of Birth</th>
                    <th>Parent Contact Info</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['class']) ?></td>
                        <td><?= htmlspecialchars($row['grade_level']) ?></td>
                        <td><?= htmlspecialchars($row['dob']) ?></td>
                        <td><?= htmlspecialchars($row['parent_contact_info']) ?></td>
                        <td>
                            <a href="edit_student.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_student.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="add_student.php" class="button">Add Student</a>
    </div>
</body>
</html>
