<?php
require_once 'auth.php';
require_once 'db.php';

if (!isset($_GET['class_id']) || !isset($_GET['student_id'])) {
    http_response_code(400);
    die("Invalid request");
}

$classId = $_GET['class_id'];
$studentId = $_GET['student_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM class_students WHERE class_id = ? AND student_id = ?");
    $stmt->execute([$classId, $studentId]);
    http_response_code(200);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}