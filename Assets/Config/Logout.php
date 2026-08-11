<?php
session_start();

// require file
require_once __DIR__ . "/../Helpers/flash_message.php";

// CLEAR ALL USER DATA
session_unset();
session_destroy();

// NEW SESSION START (for message)
session_start();

// SET MESSAGE
setMsg("success", "Logged out successfully"); 

// REDIRECT
header("Location: /BACKUP/login.php");
exit();

?>