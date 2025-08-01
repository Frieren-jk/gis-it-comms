<?php
session_start();

// Destroy all session variables
$_SESSION = array();

// Destroy the session itself
session_destroy();

// Redirect to login page (change path as needed)
header("Location: ../login.php");
exit();
?>
