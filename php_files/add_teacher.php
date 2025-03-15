<?php
session_start();
include("db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // ✅ Removed the backslashes
    $username = trim($_POST["username"]); // ✅ Removed backslashes
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);

    // Check if username already exists
    $check_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_user->bind_param("s", $username);
    $check_user->execute();
    $check_user->store_result();

    if ($check_user->num_rows > 0) {
        $_SESSION["error"] = "❌ Username already exists!";
        header("Location: add_teacher.php");
        exit();
    }
    $check_user->close();

    // Insert new teacher
    $sql = "INSERT INTO users (username, password, first_name, last_name, email, role) VALUES (?, ?, ?, ?, ?, 'teacher')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $password, $first_name, $last_name, $email);

    if ($stmt->execute()) {
        $_SESSION["success"] = "✅ Teacher added successfully!";
        header("Location: manage_teachers.php");
        exit();
    } else {
        $_SESSION["error"] = "❌ Error adding teacher.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #800000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            width: 400px;
            padding: 20px;
            border-radius: 10px;
        }
        .btn-maroon {
            background-color: #800000;
            color: white;
        }
        .btn-maroon:hover {
            background-color: #660000;
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #800000;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card shadow bg-light">
        <h3 class="title">Add New Teacher</h3>
        <?php if (isset($_SESSION["error"])): ?>
            <div class="alert alert-danger"> <?= $_SESSION["error"] ?> </div>
            <?php unset($_SESSION["error"]); ?>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">First Name:</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name:</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-maroon w-100">Register Teacher</button>
        </form>
        <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
