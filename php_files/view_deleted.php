<?php
session_start();
include("db.php"); // Ensure this path is correct

// Check if the user is logged in and is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

// Fetch deleted students
$sql = "SELECT * FROM students WHERE is_deleted = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Deleted Records</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Class</th>
                <th>Grade Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($student = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($student['student_name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= htmlspecialchars($student['class']) ?></td>
                    <td><?= htmlspecialchars($student['grade_level']) ?></td>
                    <td>
                        <a href="restore_student.php?id=<?= $student['id'] ?>" class="btn btn-success btn-sm">Restore</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>
