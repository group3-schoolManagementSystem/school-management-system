<?php
session_start();
include("db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

// Fetch teacher data
$query = "SELECT u.username, t.assigned_class, t.last_login 
          FROM teachers t 
          JOIN users u ON u.id = t.id 
          WHERE u.role = 'teacher'";
$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Teachers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .table th { background: maroon; color: white; }
        .btn-maroon { background: maroon; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .btn-maroon:hover { background: #800000; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">📚 Manage Teachers</h2>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Teacher Name</th>
                    <th>Assigned Class</th>
                    <th>Last Login</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= !empty($row['assigned_class']) ? htmlspecialchars($row['assigned_class']) : "Not Assigned" ?></td>
                        <td><?= !empty($row['last_login']) ? htmlspecialchars($row['last_login']) : "No Login Yet" ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">⬅️ Back to Dashboard</a>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>
