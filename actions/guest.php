<?php
session_start();

// Set a session variable to identify guest user
$_SESSION['guest'] = true;

// Optionally unset any logged-in user
unset($_SESSION['user_id']);
unset($_SESSION['username']);

// Redirect to index.php or your public-facing page
header("Location: ../index.php");
exit;
?>