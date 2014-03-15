<?php
require_once 'core/init.php';

if(!$id = Input::get('id')) {
	Redirect::to('index.php');
} else {
	$post = new Post($id);
	if($post->exists()) {
		$data = $post->data();
	} else {
		Session::flash('home', 'Page does not exist.');
		Redirect::to('index.php');
	}
	
	$user = new User($data->user_id);
	
	$viewer = new User();
	if($viewer->isLoggedIn()) {
		// viewer is a logged-in user
		if($viewer->data()->id === $data->user_id) {
			// viewer is viewing his or her own post
		}
	}
	?>
<?php
}
?>

<!DOCTYPE html>
<head>
	<title>Post Page: <?php echo $data->title ?></title>
	<meta charset="utf-8">
	<link rel="stylesheep" type="text/css" media="all" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
   			$('.search').keydown(function(event) {
	        	if (event.keyCode == 13) {
	            this.form.submit();
	            return false;
         		}
    		});
		});
	</script>
</head>

<body>
<div id="nav">
	<a href="index.php">HOME</a>
</div>
<div id="content" class="clearfix">
	<div class="lcol">
	<h1><?php echo $data->title ?></h1>
	<p><em>
		<a href="profile.php?user=<?php echo $user->data()->username ?>"><?php echo $user->data()->username ?></a><br>
		<?php echo escape($data->post_date) ?>
	</em></p>
	<hr />
		<?php echo $data->content ?>
	</div>
	<div class="rcol">
		<form method="get" action="search.php">
			<input class="search" type="text" placeholder="Search" name="s" />
		</form>
	</div>

	<div class="lcol" style="background: #F4F4F4; border-top: 1px solid #DDDDDD;">
		<a href="#">username1</a>
		<span style="font-style: italic; font-size: 12px;">03/05/2014 11:26am</span>
		<p>Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit.</p>
		<hr />
		<a href="#">username2</a>
		<span style="font-style: italic; font-size: 12px;">03/05/2014 11:24am</span>
		<p>Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit.</p>
		<hr />
	</div>
</div>
</body>
</html>