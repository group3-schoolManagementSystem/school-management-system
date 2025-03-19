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
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="enter_grades.php">Enter Grades</a>
        <a href="view_grades.php">View Grades</a>
        <a href="logout.php" style="color: red;">Logout</a>
    </div>

    <div class="container">
        <h2>Welcome, Teacher!</h2>
        <p>Manage your students and classes here.</p>
    </div>
</body>
</html>
