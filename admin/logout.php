<?php
//include config
require_once('../includes/config.php');

//log user out
$usero->logout();
header('Location: index.php'); 

?>