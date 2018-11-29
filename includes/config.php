<?php
ob_start();
session_start();

//database credentials
define('DBHOST','localhost');
define('DBUSER','1123263');
define('DBPASS','Dima61949');
define('DBNAME','1123263');

$db = new PDO("mysql:host=".DBHOST.";port=8889;dbname=".DBNAME, DBUSER, DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//set timezone
date_default_timezone_set('America/Chicago');

//load classes as needed
spl_autoload_register(function ($class) {
   
   $class = strtolower($class);

	//if call from within assets adjust the path
   $classpath = 'classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
	} 	
	
	//if call from within admin adjust the path
   $classpath = '../classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
	}
	
	//if call from within admin adjust the path
   //$classpath = '../../classes/class.'.$class . '.php';
   //if ( file_exists($classpath)) {
      //require_once $classpath;
	//} 		
	 
});

$user = new User($db); 
?>
