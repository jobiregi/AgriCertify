<?php
session_start();
session_unset();  // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to homepage (update to correct page if needed)
header("Location: index.php"); 
exit();
?>
