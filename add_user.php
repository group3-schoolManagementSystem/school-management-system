// After receiving POST data
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$_POST['email']]);

if ($stmt->fetch()) {
    header("Location: manage_users.php?error=Email+already+exists");
    exit();
}
