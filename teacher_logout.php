<?php
session_start();
session_destroy();

// Clear all session variables
$_SESSION = array();

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect with JavaScript to prevent back button
header("Location: teacher_login.php");
exit();
?>