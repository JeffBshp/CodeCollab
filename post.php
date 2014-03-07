<?php
if(isset($_GET['p'])) {
	$postId = $_GET['p'];
} else {
	$postId = "UNDEFINED";
}
?>

<!DOCTYPE html>
<head>
	<title>Post Page</title>
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
	<h1>Post Title for postId: <?php echo $postId ?></h1>
	<p><em>Need to add relevant information here (author, date, promote)</em></p>
	<hr />
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. <b>Lorem ipsum dolor sit amet, consectetur adipiscing elit</b>. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. </p>

<p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. <i>Lorem ipsum dolor sit amet, consectetur adipiscing elit</i>. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. </p>

<p>Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. <b>Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa</b>. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. <b>Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh</b>. Nunc feugiat mi a tellus consequat imperdiet. <b>Fusce ac turpis quis ligula lacinia aliquet</b>. Vestibulum sapien. Proin quam. Etiam ultrices. <i>Maecenas mattis</i>. Suspendisse in justo eu magna luctus suscipit. </p>

<p>Sed lectus. Integer euismod lacus luctus magna. Quisque cursus, metus vitae pharetra auctor, sem massa mattis sem, at interdum magna augue eget diam. <b>Ut fringilla</b>. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi lacinia molestie dui. Praesent blandit dolor. <b>Etiam ultrices</b>. Sed non quam. In vel mi sit amet augue congue elementum. Morbi in ipsum sit amet pede facilisis laoreet. Donec lacus nunc, viverra nec, blandit vel, egestas et, augue. Vestibulum tincidunt malesuada tellus. Ut ultrices ultrices enim. </p>

<p><b>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi lacinia molestie dui</b>. Curabitur sit amet mauris. Morbi in dui quis est pulvinar ullamcorper. Nulla facilisi. Integer lacinia sollicitudin massa. Cras metus. Sed aliquet risus a tortor. Integer id quam. Morbi mi. Quisque nisl felis, venenatis tristique, dignissim in, ultrices sit amet, augue. Proin sodales libero eget ante. Nulla quam. </p>


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