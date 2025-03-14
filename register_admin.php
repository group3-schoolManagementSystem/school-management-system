<?php
// register_admin.php
require_once 'db.php';

$email = 'admin@school.com';
$password = 'admin123'; // Change this!
$role = 'admin';
$full_name = 'Admin User';

// Hash the password
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Insert into database
try {
    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role, full_name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $password_hash, $role, $full_name]);
    echo "Admin account created! Delete this file for security.";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>