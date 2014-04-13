<?php
require_once 'core/init.php';

$database = Database::getInstance();

if(!$userProfile = Input::get('user')) {
	Redirect::to('index.php');
}
$userProfile = new User($userProfile);
if(!$userProfile->exists()) {
	Session::flash('home', 'Page does not exist.');
	Redirect::to('index.php');
}

$follows = false;
$followed = false;
$owned = false;
$viewer = new User();
if($viewer->isLoggedIn()) {
	if($viewer->getId() === $userProfile->getId()) {
		$owned = true;
	}/* else {
		foreach($database->get('Follow', array('follower_id', '=', $userProfile->getId()))->results() as $follow) {
			if($follow->followee_id === $viewer->getId()) {
				$follows = true;
			}
		}
		foreach($database->get('Follow', array('follower_id', '=', $viewer->getId()))->results() as $follow) {
			if($follow->followee_id === $userProfile->getId()) {
				$followed = true;
			}
		}
	}*/
}

/*if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		if($followed) {
			try {
				$follow_id = 0;
				foreach($database->get('Follow', array('followee_id', '=', $userProfile->getId()))->results() as $follower) {
					if($follower->follower_id === $viewer->getId()) {
						$follow_id = $follower->id;
					}
				}
				$database->delete('Follow', array('id', '=', $follow_id));
				Redirect::to('profile.php?user=' . $userProfile->getUsername());
			} catch(Exception $e) {
				die($e->getMessage());
			}
		} else {
			try {
				$database->insert('Follow', array(
					'follower_id' => $viewer->getId(),
					'followee_id' => $userProfile->getId(),
					'follow_date' => date('Y-m-d H:i:s')
				));
				Redirect::to('profile.php?user=' . $userProfile->getUsername());
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}
	}
}*/
?>

<!DOCTYPE html>
<html>
<head>
	<title>CodeCollab User: <?php echo $userProfile->getUsername() ?></title>
	<meta charset="utf-8">
	<link rel="stylesheep" type="text/css" media="all" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>
<?php require_once 'includes/navigation.php'; ?>
<div id="content" class="clearfix">
	<div class="lcol">

		<?php
		echo '<p><h3>'. $userProfile->getUsername() .'</h3></p>';
		
		/*if($viewer->isLoggedIn()) {
			if($follows) {
				echo "<p>{$userProfile->getUsername()} follows you.</p>";
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
		
		if($userProfile->getVisible()->name) {
			echo '<p>Full Name: ' . escape($userProfile->getFullName()) . '</p>';
		}
		
		if($userProfile->getVisible()->email) {
			echo '<p>Email Address: ' . $userProfile->getEmail() . '</p>';
		}*/
		
		$date = new DateTime($userProfile->getRegistrationDate());
		$date = $date->format('F d, Y \a\t h:ia');
		echo '<p>Joined: ' . escape($date) . '</p>';
		
		echo '<p>Posts: ' . $database->action('SELECT COUNT(id) AS num', 'Post', array('user_id', '=', $userProfile->getId()))->first()->num . '</p>';
		
		echo '<p>Comments: ' . $database->action('SELECT COUNT(id) AS num', 'Comments', array('user_id', '=', $userProfile->getId()))->first()->num . '</p>';
		
		/*if($userProfile->getAbout() && $userProfile->getVisible()->about) {
			echo '<hr /><p>' . $userProfile->getAbout() . '</p>';
		}*/
		
		echo '<hr /><p><h3>Posts:</h3></p>';
		$posts = $database->get('Post', array('user_id', '=', $userProfile->getId()))->results();
		if(!count($posts)) {
			echo '<p><em>No posts yet.</em></p>';
		} else {
			foreach($posts as $post) {
				$date = new DateTime($post->post_date);
				$date = $date->format('F d, Y \a\t h:ia');
				echo "<p><h4><a href='post.php?id=" . $post->id . "'>" . $post->title . "</a></h4><br /><em style='font-size: 12px;'>". $date ."</em></p>";
			}
		}
		?>
	</div>
	<div class="rcol">
		<?php
		require_once 'includes/sidebar.php';
		if($viewer->isLoggedIn() && $owned) {
			?>
			<p><a href="update.php">Edit Profile</a></p>
			<p><a href="changepassword.php">Change Password</a></p>
			<?php
		}
		?>
	</div>
</div>
</body>
</html>