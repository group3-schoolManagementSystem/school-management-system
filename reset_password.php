<?php
require_once 'auth.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL WHERE id = ?");
        $stmt->execute([$newPassword, $userId]);
        header("Location: manage_users.php?success=Password+reset");
    } catch (PDOException $e) {
        header("Location: manage_users.php?error=" . urlencode($e->getMessage()));
    }
}