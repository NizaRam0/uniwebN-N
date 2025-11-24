<?php

session_start();
$_SESSION['loggedIn']=false;
session_destroy();// Destroy all session data to log out the user
header("Location: ../front/homePage.php");
exit();
?>