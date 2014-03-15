<?php
session_start();

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'cse.unl.edu:3306',
		'username' => 'jbishop',
		'password' => '3y7xNd',
		'database' => 'jbishop'
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)
);

spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';
?>