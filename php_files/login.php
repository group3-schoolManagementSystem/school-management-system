<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Enables error display for debugging

include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $_SESSION["error"] = "❌ Please enter both username and password.";
        header("Location: login.php");
        exit();
    }

    // Check if user exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("❌ SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];

            // Redirect based on role
            if ($user["role"] == "admin") {
                header("Location: admin_dashboard.php");
            } elseif ($user["role"] == "teacher") {
                header("Location: teacher_dashboard.php");
            } elseif ($user["role"] == "student") {
                header("Location: student_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $_SESSION["error"] = "❌ Invalid username or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION["error"] = "❌ User not found.";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
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
        .login-container {
            width: 40%;
            margin: auto;
            margin-top: 80px;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-maroon {
            background-color: maroon;
            color: white;
            border: none;
        }
        .btn-maroon:hover {
            background-color: #800000;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="index.php">🏠 Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </div>

    <div class="login-container">
        <h2>Login</h2>

        <?php 
        if (isset($_SESSION["error"])) {
            echo "<div class='alert alert-danger'>" . $_SESSION["error"] . "</div>";
            unset($_SESSION["error"]);
        }
        ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-maroon btn-lg w-100">Login</button>
        </form>

        <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
    </div>

</body>
</html>
