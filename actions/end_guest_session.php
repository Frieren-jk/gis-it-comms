<?php
session_start();

// Only clear guest session
if (isset($_SESSION['guest']) && $_SESSION['guest'] === true) {
    session_unset();
    session_destroy();
}

// Redirect to login page
header("Location: ../login.php");
exit;
?>