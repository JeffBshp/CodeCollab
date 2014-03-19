<?php
require_once 'core/init.php';

$database = Database::getInstance();

if(!$user = Input::get('user')) {
	Redirect::to('index.php');
}
$user = new User($user);
if(!$user->exists()) {
	Session::flash('home', 'Page does not exist.');
	Redirect::to('index.php');
}

$follows = false;
$followed = false;
$owned = false;
$viewer = new User();
if($viewer->isLoggedIn()) {
	if($viewer->getId() === $user->getId()) {
		$owned = true;
	}/* else {
		foreach($database->get('Follow', array('follower_id', '=', $user->getId()))->results() as $follow) {
			if($follow->followee_id === $viewer->getId()) {
				$follows = true;
			}
		}
		foreach($database->get('Follow', array('follower_id', '=', $viewer->getId()))->results() as $follow) {
			if($follow->followee_id === $user->getId()) {
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
				foreach($database->get('Follow', array('followee_id', '=', $user->getId()))->results() as $follower) {
					if($follower->follower_id === $viewer->getId()) {
						$follow_id = $follower->id;
					}
				}
				$database->delete('Follow', array('id', '=', $follow_id));
				Redirect::to('profile.php?user=' . $user->getUsername());
			} catch(Exception $e) {
				die($e->getMessage());
			}
		} else {
			try {
				$database->insert('Follow', array(
					'follower_id' => $viewer->getId(),
					'followee_id' => $user->getId(),
					'follow_date' => date('Y-m-d H:i:s')
				));
				Redirect::to('profile.php?user=' . $user->getUsername());
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}
	}
}*/
?>

<!DOCTYPE html>
<head>
	<title>CodeCollab User: <?php echo $user->getUsername() ?></title>
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
		echo '<p><h3>' . $user->getUsername() . '</h3></p><hr />';
		
		/*if($viewer->isLoggedIn()) {
			if($follows) {
				echo "<p>{$user->getUsername()} follows you.</p>";
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
		
		if($user->getVisible()->name) {
			echo '<p>Full Name: ' . escape($user->getFullName()) . '</p>';
		}
		
		if($user->getVisible()->email) {
			echo '<p>Email Address: ' . $user->getEmail() . '</p>';
		}*/
		
		$date = new DateTime($user->getRegistrationDate());
		$date = $date->format('F d, Y \a\t h:ia');
		echo '<p>Joined: ' . escape($date) . '</p>';
		
		echo '<p>Posts: ' . $database->action('SELECT COUNT(id) AS num', 'Post', array('user_id', '=', $user->getId()))->first()->num . '</p>';
		
		echo '<p>Comments: ' . $database->action('SELECT COUNT(id) AS num', 'Comments', array('user_id', '=', $user->getId()))->first()->num . '</p>';
		
		/*if($user->getAbout() && $user->getVisible()->about) {
			echo '<hr /><p>' . $user->getAbout() . '</p>';
		}*/
		
		echo '<hr /><p><h3>Posts:</h3></p>';
		$posts = $database->get('Post', array('user_id', '=', $user->getId()))->results();
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
		require_once 'includes/searchbar.php';
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