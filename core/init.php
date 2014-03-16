<?php
session_start();

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'database' => 'csce361'
	),
	'session' => array(
		'session_name' => 'user',
		'token_name_1' => 'token1',
		'token_name_2' => 'token2'
	)
);

spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';
?>