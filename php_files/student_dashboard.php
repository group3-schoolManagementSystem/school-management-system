<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: login.php");
    exit();
}
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
