<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

include("db.php");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            padding: 15px 40px;
            background-color: maroon;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            font-size: 16px;
        }
        .button:hover {
            background-color: #800000;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="admin_dashboard.php">🏠 Home</a>
        <a href="manage_students.php">Manage Students</a>
        <a href="manage_teachers.php">Manage Teachers</a>
        <a href="manage_classes.php">Manage Classes</a>
        <a href="view_reports.php">View Reports</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php" style="color: red;">Logout</a>
    </div>

    <div class="container">
        <h2>Welcome, Admin!</h2>
        <p>Manage school operations from your dashboard.</p>
        <a href="manage_students.php" class="button">Manage Students</a><br>
        <a href="manage_teachers.php" class="button">Manage Teachers</a><br>
        <a href="manage_classes.php" class="button">Manage Classes</a><br>
        <a href="view_reports.php" class="button">View Reports</a><br>
        <a href="settings.php" class="button">Settings</a>
    </div>
</body>
</html>
