<?php
session_start();

// define config configuration
$GLOBALS['config'] = array (
	'mysql' => array(
		'host' => 'localhost',  
		'username' => 'roots',
		'password' => '',
		'db' => 'login_system'
	),
	'session' => array(
		'session_name' => 'user'
	)
);

// automatically loading PHP classes without explicitly loading them with the require() , require_once() and so on.
spl_autoload_register(function($class) {
	require_once 'classes/' .$class. '.php';
});

//include  function
require_once 'functions/sanitize.php';
