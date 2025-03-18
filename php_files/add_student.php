<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if the user is not a teacher
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: login.php");
    exit();
}

include("db.php");

$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $student_name = trim($_POST["student_name"]);
    $email = trim($_POST["email"]);
    $class = trim($_POST["class"]);
    $grade_level = trim($_POST["grade_level"]);
    $dob = trim($_POST["dob"]);
    $parent_contact_info = trim($_POST["parent_contact_info"]);
    $added_by = $_SESSION["user_id"]; // Teacher ID

    // Validate required fields
    if (!empty($student_name) && !empty($email) && !empty($class) && !empty($grade_level) && !empty($dob) && !empty($parent_contact_info)) {
        
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Invalid email format!";
        } else {
            // Prepare SQL query
            $sql = "INSERT INTO students (student_name, email, class, grade_level, dob, parent_contact_info, added_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssssssi", $student_name, $email, $class, $grade_level, $dob, $parent_contact_info, $added_by);

                if ($stmt->execute()) {
                    $message = "✅ Student added successfully!";
                } else {
                    $message = "❌ Error adding student: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $message = "❌ Database error: " . $conn->error;
            }
        }
    } else {
        $message = "⚠️ All fields are required!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 450px;
            background: maroon;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: white;
        }

        h2 {
            margin-bottom: 15px;
            font-size: 24px;
        }

        input, select {
            width: 95%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: white;
            color: maroon;
            padding: 12px;
            border: none;
            width: 100%;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 8px;
        }

        button:hover {
            background: #ddd;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .dashboard-btn {
            background: #ffcc00;
            color: black;
        }

        .dashboard-btn:hover {
            background: #e6b800;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Student</h2>

    <?php if (!empty($message)) { ?>
        <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <input type="text" name="student_name" placeholder="Student Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="class" placeholder="Class" required>
        <input type="text" name="grade_level" placeholder="Grade Level" required>
        <input type="date" name="dob" required>
        <input type="text" name="parent_contact_info" placeholder="Parent Contact Info" required>

        <button type="submit">Add Student</button>
        <button type="button" class="dashboard-btn" onclick="window.location.href='teacher_dashboard.php'">Return to Dashboard</button>
    </form>
</div>

</body>
</html>
