<?php
session_start();
session_unset();  // Clear session variables
session_destroy(); // Destroy the session

// Optional: redirect to homepage or login page
header("Location: login.php");
exit();
?>
