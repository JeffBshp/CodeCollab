<?php
require_once 'core/init.php';	
require_once 'searchbar.php';

if($user->isLoggedIn()) {
?>
	<hr /><p><a href="createpost.php">Create a new post</a></p>
<?php
}
?>