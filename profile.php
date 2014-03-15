<?php
require_once 'core/init.php';

if(!$username = Input::get('user')) {
	Redirect::to('index.php');
} else {
	$user = new User($username);
	if(!$user->exists()) {
		Session::flash('home', 'Page does not exist.');
		Redirect::to('index.php');
	} else {
		$data = $user->data();
	}
	
	$viewer = new User();
	if($viewer->isLoggedIn()) {
		if($viewer->data()->username === $username) {
			?>
			<p><a href="update.php">Edit Profile</a></p>
			<p><a href="changepassword.php">Change Password</a></p>
			<?php
		}
	}
	?>
	
	<p><a href="index.php">Home</a></p>
	
	<h3><?php echo escape($data->username) ?></h3>
	
	<?php
	if($data->name_visible) {
		?>
		<p><?php echo 'Full Name: ' . escape($data->first_name . ' ' . $data->last_name) ?></p>
		<?php
	}
	?>
	
	<?php
	if($data->email_visible) {
		?>
		<p><?php echo 'Email Address: ' . escape($data->email) ?></p>
		<?php
	}
	?>
	
	<p><?php echo 'Joined: ' . escape($data->registration_date) ?></p>
	
	<?php
	if($data->about_visible) {
		?>
		<p><?php echo 'About: ' . escape($data->about) ?></p>
		<?php
	}
	?>
	
	<?php
}
?>