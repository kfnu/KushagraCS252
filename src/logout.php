<?php
/**
 * Created by PhpStorm.
 * User: Kushagra
 * Date: 12/7/2017
 * Time: 11:01 PM
 */

session_start();

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/guest.css">
    <title>Logout</title>
</head>
<body>
<div class="header">
    <?php include("headerguest.php");?>
</div>
<div class="login-page" align="center">
    <p style="color:white;font-family: Roboto, sans-serif; font-size: 14px;">
        Thank you for using Calorie Counter. To login again, click <a href="login.php" style="color: gold;">here</a>.
    </p>
</div>
</body>
</html>
