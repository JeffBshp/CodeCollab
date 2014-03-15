<div id="nav">
	<a href="index.php">HOME</a>
	<?php
	$user = new User();
	if($user->isLoggedIn()) {
		?>
		&nbsp;&nbsp;&nbsp;<a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>
		&nbsp;&nbsp;&nbsp;<a href="logout.php">Log Out</a>
		<?php
	} else {
		?>
		&nbsp;&nbsp;&nbsp;<a href="login.php">Log In</a>
		&nbsp;&nbsp;&nbsp;<a href="register.php">Register</a>
		<?php
	}
	?>
</div>