<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: login.php");
    exit();
}
?>
<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title>
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
                <h4>Welcome, <?= $student['first_name'] . ' ' . $student['last_name'] ?>!</h4>
                <ul class="list-group">
                    <li class="list-group-item">Class: <?= $student['class'] ?></li>
                    <li class="list-group-item">Email: <?= $student['email'] ?></li>
                    <li class="list-group-item">Date of Birth: <?= $student['date_of_birth'] ?></li>
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
