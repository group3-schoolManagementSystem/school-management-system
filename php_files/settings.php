<?php
session_start();
include("db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

// Fetch admin details
$admin_id = $_SESSION["user_id"];
$result = $conn->query("SELECT * FROM users WHERE id = $admin_id");
$admin = $result->fetch_assoc();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];

    // Update details
    $update_sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $username, $email, $admin_id);
    if ($stmt->execute()) {
        $success = "✅ Profile updated successfully!";
    } else {
        $error = "❌ Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { max-width: 500px; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 10px; text-align: center; }
        .btn-maroon { background: maroon; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .btn-maroon:hover { background: #800000; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">⚙️ Admin Settings</h2>
        <?php if ($success) echo "<p style='color: green;'>$success</p>"; ?>
        <?php if ($error) echo "<p style='color: red;'>$error</p>"; ?>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required class="form-control mb-2">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required class="form-control mb-2">
            <button type="submit" class="btn btn-maroon w-100">Save Changes</button>
        </form>
        <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">⬅️ Back to Dashboard</a>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>
