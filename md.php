<?php

# Install PSR-0-compatible class autoloader
spl_autoload_register(function($class){
	require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});

# Get Markdown class
use \Michelf\Markdown;

$input = $_POST['toMarkdown'];
$html = Markdown::defaultTransform($input);

?>

<!DOCTYPE html>
<head>
	<title>Markdown output</title>

	<style type="text/css">
		pre {
			padding: 10px 20px 10px 20px;
			white-space: pre-wrap; /* css-3 */
			white-space: -moz-pre-wrap !important; 
			white-space: -pre-wrap; /* Opera 4-6 */
			white-space: -o-pre-wrap; /* Opera 7 */
			word-wrap: break-word; /* Internet Explorer 5.5+ */
			color: #48A6D9;
			background: #333333;
		}
	</style>
</head>
<body>
	<?php echo $html ?>
</body>
</html>