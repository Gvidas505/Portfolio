<?php
session_start();

// Force login every time index.php is opened 
session_unset();
session_destroy();

header('Location: login.php');
exit;