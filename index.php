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
		<h1>CodeCollab Home Page</h1>
		<br /><br />
		<?php
			$database = Database::getInstance();
			$posts = $database->query('SELECT Post.*, COUNT(Promotion.post_id) AS promotions
										FROM Post LEFT JOIN Promotion ON Post.id = Promotion.post_id
										GROUP BY Post.id ORDER BY post_date DESC LIMIT 10');
			foreach($posts->results() as $post) {
				$author = new User($post->user_id);
				$date = new DateTime($post->post_date);
				$date = $date->format('F d, Y \a\t h:ia');
				echo "<h3><a href='post.php?id=" . $post->id . "'>" . $post->title . "</a></h3> <b style='margin-left: 10px;'>" . $post->promotions . "</b><br />
					<em style='font-size: 12px;'>". $date ."</em>
					<p style='font-style: italic;'>Author: <a href='profile.php?user=" . $author->getUsername() . "'>" . $author->getUsername() . "</a></p>
					<hr />";
			}
		?>
	</div>
	<div class="rcol">
		<?php require_once 'includes/sidebar.php'; ?>
	</div>
</div>
</body>
</html>