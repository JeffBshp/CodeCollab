<?php
require_once 'core/init.php';

$database = Database::getInstance();

if(!$user = Input::get('user')) {
	Redirect::to('index.php');
}
$user = new User($user);
if($user->exists()) {
	$data = $user->data();
} else {
	Session::flash('home', 'Page does not exist.');
	Redirect::to('index.php');
}

$follows = false;
$followed = false;
$owned = false;

$viewer = new User();
if($viewer->isLoggedIn()) {
	if($viewer->data()->id === $data->id) {
		$owned = true;
		?>
		<p><a href="update.php">Edit Profile</a></p>
		<p><a href="changepassword.php">Change Password</a></p>
		<?php
	} else {
		foreach($database->get('Follow', array('follower_id', '=', $data->id))->results() as $follow) {
			if($follow->followee_id === $viewer->data()->id) {
				$follows = true;
			}
		}
		foreach($database->get('Follow', array('follower_id', '=', $viewer->data()->id))->results() as $follow) {
			if($follow->followee_id === $data->id) {
				$followed = true;
			}
		}
	}
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		if($followed) {
			try {
				$follow_id = 0;
				foreach($database->get('Follow', array('followee_id', '=', $data->id))->results() as $follower) {
					if($follower->follower_id === $viewer->data()->id) {
						$follow_id = $follower->id;
					}
				}
				$database->delete('Follow', array('id', '=', $follow_id));
				Redirect::to('profile.php?user=' . $data->username);
			} catch(Exception $e) {
				die($e->getMessage());
			}
		} else {
			try {
				$database->insert('Follow', array(
					'follower_id' => $viewer->data()->id,
					'followee_id' => $data->id,
					'follow_date' => date('Y-m-d H:i:s')
				));
				Redirect::to('profile.php?user=' . $data->username);
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}
	}
}
?>

<p><a href="index.php">Home</a></p>

<h3><?php echo escape($data->username) ?></h3>

<?php
if($viewer->isLoggedIn()) {
	if($follows) {
		echo "<p>{$data->username} follows you.</p>";
	}
	if($followed) {
		echo "<form action='' method='post'>
			<input type='hidden' name='token' value='". Token::generate() ."'>
			<input type='submit' value='Unfollow'>
			</form>";
	} else if(!$owned) {
		echo "<form action='' method='post'>
			<input type='hidden' name='token' value='". Token::generate() ."'>
			<input type='submit' value='Follow'>
			</form>";
	}
}
?>

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