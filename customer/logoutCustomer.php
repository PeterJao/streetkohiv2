<?php
session_start(); 
$_SESSION = array();
session_destroy();
header("Location: ../landingpage/LandingPage.php");
exit();
?>
