<?php
session_start(); 

// Logout 
session_unset(); 
session_destroy(); 

header('Location: login.php'); 
exit;