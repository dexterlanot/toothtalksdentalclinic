<?php
// Destroy the session and redirect to the login page
session_start();
session_destroy();
header("Location: login.php");
exit;
?>
