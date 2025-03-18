<?php
include 'includes/config.php';

// Handle Registration
if (isset($_POST['register'])) {
    $email = sanitize($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = sanitize($_POST['full_name']);
    $class = sanitize($_POST['class']);
    $parent_email = sanitize($_POST['parent_email']);

    // Insert into users table
    $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'student')");
    $stmt->execute([$email, $password]);
    $user_id = $pdo->lastInsertId();

    // Insert into students table
    $stmt = $pdo->prepare("INSERT INTO students (user_id, full_name, class, parent_email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $full_name, $class, $parent_email]);

    header("Location: dashboard.php");
    exit();
}

// Handle Login
if (isset($_POST['login'])) {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Portal</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Registration Form -->
                <div class="card mb-4">
                    <div class="card-header">Student Registration</div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                            <input type="text" name="full_name" class="form-control mb-2" placeholder="Full Name" required>
                            <input type="text" name="class" class="form-control mb-2" placeholder="Class" required>
                            <input type="email" name="parent_email" class="form-control mb-2" placeholder="Parent Email" required>
                            <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                </div>

                <!-- Login Form -->
                <div class="card">
                    <div class="card-header">Student Login</div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                            <button type="submit" name="login" class="btn btn-success w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>