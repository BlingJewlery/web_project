<?php
session_start(); 

// Check if the user is logged in, then destroy the session
if(isset($_SESSION['username'])){
    session_destroy(); 
}

// Redirect to the home page after logout
header("Location: home.php");
exit();
?>
