// After successful deletion
$logStmt = $pdo->prepare("INSERT INTO audit_logs 
    (user_id, action, target_user_id)
    VALUES (?, 'delete_user', ?)");
$logStmt->execute([$_SESSION['user_id'], $_GET['id']]);