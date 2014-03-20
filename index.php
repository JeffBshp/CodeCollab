<?php
require_once 'core/init.php';
?>

<!DOCTYPE html>
<head>
	<title>CodeCollab</title>
	<meta charset="utf-8">
	<link rel="stylesheep" type="text/css" media="all" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>
<?php require_once 'includes/navigation.php'; ?>
<div id="content" class="clearfix">
	<div class="lcol">
		<h1>New Posts</h1>
		<br />
		<?php
			$postList = array();

			$database = Database::getInstance();
			$posts = $database->query('SELECT Post.*, COUNT(Promotion.post_id) AS promotions
										FROM Post LEFT JOIN Promotion ON Post.id = Promotion.post_id
										GROUP BY Post.id ORDER BY post_date DESC LIMIT 10');
			foreach($posts->results() as $post) {
				array_push($postList, $post->id);
				$author = new User($post->user_id);
				$date = new DateTime($post->post_date);
				$date = $date->format('F d, Y \a\t h:ia');
				echo "
					<div class='post clearfix'>
						<div class='score'>" . $post->promotions . "</div>
						<div class='info'>
							<div class='title'><a href='post.php?id=" . $post->id . "'>" . $post->title . "</a></div>
							<div class='author'><a href='profile.php?user=" . $author->getUsername() . "'>" . $author->getUsername() . "</a></div>
							<div class='date'>". $date ."</div>
						</div>
					</div>
					";
			}
		?>
		<br /><br />
		<h1>Top Posts</h1>
		<br />
		<?php

			/*
				Top Posts only display if they are not being displayed in New Posts
			*/
				
			$database = Database::getInstance();
			$posts = $database->query('SELECT Post.*, COUNT(Promotion.post_id) AS promotions
										FROM Post LEFT JOIN Promotion ON Post.id = Promotion.post_id
										GROUP BY Post.id ORDER BY promotions DESC LIMIT 10');
			foreach($posts->results() as $post) {
				if(!in_array($post->id, $postList)) {
					$author = new User($post->user_id);
					$date = new DateTime($post->post_date);
					$date = $date->format('F d, Y \a\t h:ia');
					echo "
					<div class='post clearfix'>
						<div class='score'>" . $post->promotions . "</div>
						<div class='info'>
							<div class='title'><a href='post.php?id=" . $post->id . "'>" . $post->title . "</a></div>
							<div class='author'><a href='profile.php?user=" . $author->getUsername() . "'>" . $author->getUsername() . "</a></div>
							<div class='date'>". $date ."</div>
						</div>
					</div>
					";
				}
			}
		?>
	</div>
	<div class="rcol">
		<?php require_once 'includes/sidebar.php'; ?>
	</div>
</div>
</body>
</html>