<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>
// In the above code, the logout.php file destroys the session and redirects the user to the login page.
// The auth.php file is included in the dashboard.php and manage_users.php files to ensure that only authenticated
// users with the role of admin can access these pages. If the user is not authenticated or does not have the role of admin,
// they are redirected to the login page. This helps to secure the admin management system by restricting access to unauthorized users.