<?php
require_once 'auth.php'; // Include authentication check

// Ensure only admins can access
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 bg-light sidebar">
                <div class="p-3">
                    <h4>School Admin</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                        <li class="nav-item"><a href="manage_users.php" class="nav-link">Manage Users</a></li>
                        <li class="nav-item"><a href="manage_classes.php" class="nav-link">Manage Classes</a></li>
                        <li class="nav-item"><a href="logout.php" class="nav-link text-danger">Logout</a></li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 p-4">
                <h3>Welcome, <?= $_SESSION['role'] ?>!</h3>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Total Users</h5>
                                <?php
                                    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
                                    echo $stmt->fetchColumn();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>