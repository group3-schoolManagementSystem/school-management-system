<?php
session_start();
include("db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

// Handle new class submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_name = trim($_POST["class_name"]);
    $teacher_id = $_POST["teacher_id"];
    $schedule = trim($_POST["schedule"]);
    $room_number = trim($_POST["room_number"]);

    if (!empty($class_name) && !empty($teacher_id) && !empty($schedule) && !empty($room_number)) {
        $sql = "INSERT INTO classes (class_name, teacher_id, schedule, room_number) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siss", $class_name, $teacher_id, $schedule, $room_number);

        if ($stmt->execute()) {
            $_SESSION["success"] = "✅ Class added successfully!";
        } else {
            $_SESSION["error"] = "❌ Error adding class: " . $stmt->error;
        }
    } else {
        $_SESSION["error"] = "❌ Please fill in all fields.";
    }
}

// Fetch existing classes
$sql_classes = "SELECT classes.id, classes.class_name, classes.schedule, classes.room_number, users.first_name, users.last_name 
                FROM classes 
                JOIN users ON classes.teacher_id = users.id";
$result_classes = $conn->query($sql_classes);

// Fetch teachers for dropdown
$sql_teachers = "SELECT id, first_name, last_name FROM users WHERE role = 'teacher'";
$result_teachers = $conn->query($sql_teachers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: maroon; color: white; }
        .btn-maroon { background: maroon; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px; }
        .btn-maroon:hover { background: #800000; }
        .alert { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">📅 Manage Classes</h2>
        
        <!-- Success or error message -->
        <?php 
        if (isset($_SESSION["success"])) {
            echo "<div class='alert alert-success'>" . $_SESSION["success"] . "</div>";
            unset($_SESSION["success"]);
        }
        if (isset($_SESSION["error"])) {
            echo "<div class='alert alert-danger'>" . $_SESSION["error"] . "</div>";
            unset($_SESSION["error"]);
        }
        ?>

        <!-- Add new class form -->
        <form method="POST" class="mb-3">
            <div class="mb-3">
                <input type="text" name="class_name" class="form-control" placeholder="Class Name" required>
            </div>
            <div class="mb-3">
                <input type="text" name="schedule" class="form-control" placeholder="Schedule (e.g., Mon-Fri 10AM-12PM)" required>
            </div>
            <div class="mb-3">
                <input type="text" name="room_number" class="form-control" placeholder="Room Number" required>
            </div>
            <div class="mb-3">
                <select name="teacher_id" class="form-control" required>
                    <option value="">Select Teacher</option>
                    <?php while ($teacher = $result_teachers->fetch_assoc()) { ?>
                        <option value="<?= $teacher['id'] ?>"><?= $teacher['first_name'] . " " . $teacher['last_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-maroon w-100">➕ Add Class</button>
        </form>

        <!-- Display existing classes -->
        <table class="table table-bordered">
            <tr>
                <th>Class Name</th>
                <th>Schedule</th>
                <th>Room Number</th>
                <th>Assigned Teacher</th>
                <th>Actions</th>
            </tr>
            <?php while ($class = $result_classes->fetch_assoc()) { ?>
                <tr>
                    <td><?= $class["class_name"] ?></td>
                    <td><?= $class["schedule"] ?></td>
                    <td><?= $class["room_number"] ?></td>
                    <td><?= $class["first_name"] . " " . $class["last_name"] ?></td>
                    <td>
                        <a href="delete_class.php?id=<?= $class['id'] ?>" class="btn btn-danger btn-sm">❌ Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">⬅️ Back to Dashboard</a>
    </div>
</body>
</html>
