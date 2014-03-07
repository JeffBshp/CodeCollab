<?php
if(isset($_GET['s'])) {
	$search = $_GET['s'];
	echo "Searching for: " . $search;
} else {
	echo "No search term specified";
}

echo '<br /><a href="index.php">HOME</a>';
?>