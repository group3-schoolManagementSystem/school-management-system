<?php
session_start();
include("db.php"); // Database connection

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

// Fetch teachers from the database
$sql = "SELECT id, first_name, last_name, email FROM users WHERE role = 'teacher'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Teachers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #800000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 800px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #800000;
            text-align: center;
        }
        .btn-maroon {
            background-color: #800000;
            color: white;
        }
        .btn-maroon:hover {
            background-color: #660000;
        }
    </style>
</head>
<body>
    <div class="container shadow">
        <h2 class="title">Manage Teachers</h2>
        <a href="add_teacher.php" class="btn btn-maroon mb-3">➕ Add Teacher</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["first_name"] . " " . $row["last_name"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td>
                        <a href="delete_teacher.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">❌ Remove</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
