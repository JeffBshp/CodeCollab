<?php
session_start();

date_default_timezone_set("America/Chicago");

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
	$exploded = explode("\\", $class);
	if($exploded[0] != "Michelf" && $class != "Markdown") {
		require_once 'classes/' . $class . '.php';
	} else {
		require_once 'Michelf/MarkdownInterface.php';
		require_once 'Michelf/Markdown.php';
	}
});


require_once 'functions/sanitize.php';
?>