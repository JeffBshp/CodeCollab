<?php
require_once 'core/init.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>CodeCollab: Search Results</title>
	<meta charset="utf-8">
	<link rel="stylesheep" type="text/css" media="all" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>
<?php require_once 'includes/navigation.php'; ?>
<div id="content" class="clearfix"  style="min-height: 594px; background: white;">
	<div class="lcol" style="border: white;">
		<?php
		require_once 'includes/searchbar.php';
		
		if(Input::exists('get')) {
			$validate = new Validate();
			$validation = $validate->check($_GET, array(
				'query' => array('required' => true),
				'order' => array()
			));
		
			if($validation->passed()) {
				echo '<br />Currently searching for: <b>' . Input::get('query') . '</b>';
				if(Input::get('order') === "date") {
					$order = "date";
				} else {
					$order = "score";
				}
				echo '<br />Ordering search by: <b>' . $order . '</b>';
				echo '<hr />';

				$searchResults = SearchResult::generateResults(Input::get('query'), $order);

				foreach($searchResults as $result) {
					echo $result;
				}

				
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