<?php
// Initialize the session
session_start();
include "del_session.php";
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: index.php");
exit;
?>