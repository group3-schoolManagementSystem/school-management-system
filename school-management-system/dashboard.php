<?php
include 'includes/config.php';
require_auth(); // Ensure user is logged in

// Fetch student data
$stmt = $pdo->prepare("SELECT students.* FROM students JOIN users ON students.user_id = users.id WHERE users.id = ?");
$stmt->execute([$_SESSION['user_id']]);
$student = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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
                <h4>Welcome, <?= $student['full_name'] ?>!</h4>
                <ul class="list-group">
                    <li class="list-group-item">Class: <?= $student['class'] ?></li>
                    <li class="list-group-item">Parent Email: <?= $student['parent_email'] ?></li>
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>