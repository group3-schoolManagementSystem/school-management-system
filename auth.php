<?php
// auth.php

// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php'; // Include DB after session check

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
// Session expires after 30 minutes of inactivity
$timeout = 1800; // 30 minutes in seconds

if (isset($_SESSION['last_activity']) && 
    time() - $_SESSION['last_activity'] > $timeout) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=Session+expired");
    exit();
}

$_SESSION['last_activity'] = time();
?>