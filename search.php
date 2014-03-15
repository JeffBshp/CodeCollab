<?php
require_once 'core/init.php';

if(Session::exists('home')) {
	echo '<p>' . Session::flash('home') . '</p>';
}
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
<div id="content" class="clearfix"  style="min-height: 594px; background: white;">
	<div class="lcol" style="border: white;">
		<?php require_once 'includes/searchbar.php'; ?>

		<?php
		if(Input::exists('get')) {
			$validate = new Validate();
			$validation = $validate->check($_GET, array(
				'query' => array('required' => true),
			));
		
			if($validation->passed()) {
				echo '<br />Currently searching for: <b>' . Input::get('query') . '<b>';
				echo '<hr />';

				
				
			} else {
				foreach($validation->errors() as $error) {
					echo $error, '<br>';
				}
				echo '<br>';
			}
		} else {
			echo "<br /><b>No search query<br>";
		}
		?>
	</div>
	<div class="rcol" style="background: white;">

	</div>
</div>
</body>
</html>