<?php
session_start(); //to ensure you are using same session
session_destroy();
unset($_SESSION);
session_regenerate_id(true);
$_SESSION = array();
//to redirect back to "index.php" after logging out
header("location: /");
