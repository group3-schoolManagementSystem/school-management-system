<?php
// dashboard.php
require_once 'db.php'; // ← ADD THIS LINE
require_once 'auth.php'; // Keep this after db.php

// Rest of your code
?>

<?php
// dashboard.php
require_once 'db.php'; // ← ADD THIS LINE
require_once 'auth.php'; // Keep this after db.php

// Rest of your code
?>

<?php
// db.php
$host = 'localhost';
$dbname = 'school_system'; // Match your database name
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
// db.php
$host = 'localhost';
$dbname = 'school_system'; // Match your database name
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>