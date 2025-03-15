<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Ensure correct CSS path -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }
        .navbar {
            background-color: #333;
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
        }
        .button {
            display: block;
            padding: 10px;
            margin: 10px auto;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            width: 250px;
            text-align: center;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="admin_dashboard.php">🏠 Home</a>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_teachers.php">Manage Teachers</a>
        <a href="manage_classes.php">Manage Classes</a>
        <a href="view_reports.php">View Reports</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php" style="color: red;">Logout</a>
    </div>

    <div class="container">
        <h2>Welcome, Admin!</h2>
        <p>Manage school operations from your dashboard.</p>

        <a href="manage_teachers.php" class="button">Manage Teachers</a>
        <a href="manage_classes.php" class="button">Manage Classes</a>
        <a href="view_reports.php" class="button">View Reports</a>
        <a href="settings.php" class="button">Settings</a>
    </div>

</body>
</html>
