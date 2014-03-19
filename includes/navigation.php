<div id="nav">
	<div id="navcontent">
		<div id="logo">
			<a href="./index.php">CodeCollab</a>
		</div>
		<ul>
		<li><a href="index.php">Home</a></li>
		<?php
		$user = new User();
		if($user->isLoggedIn()) {
		?>
			<li><a href="profile.php?user=<?php echo escape($user->getUsername()); ?>"><?php echo escape($user->getUsername()); ?></a></li>
			<li><a href="logout.php">Log Out</a></li>
		<?php
		} else {
		?>
			<li><a href="login.php">Log In</a></li>
			<li><a href="register.php">Register</a></li>
		<?php
		}
		if(Session::exists('home')) {
			//echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . Session::flash('home');
		}
		?>
		</ul>
	</div>
</div>