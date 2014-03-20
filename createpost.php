<?php
require_once 'core/init.php';

$database = Database::getInstance();
$user = new User();

if(!$user->isLoggedIn()) {
	Session::flash('home', 'You must be logged in.');
	Redirect::to('index.php');
}
?>

<!DOCTYPE html>
<head>
	<title>CodeCollab: Create Post</title>
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
		if(Input::exists()) {
			if(Token::check(Input::get('token'))) {
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'title' => array(
						'required' => true,
						'min' => 2,
						'max' => 100
					),
					'tags' => array(
						'max' => 255
					),
					'content' => array(
						'required' => true,
					)
				));
				
				if($validation->passed()) {
					$post = new Post();
					$language = null;
					if(Input::get('language') !== '0') {
						$language = Input::get('language');
					}
					try {
						$post->create(array(
							'user_id' => $user->getId(),
							'title' => Input::get('title'),
							'language_id' => $language,
							'tags' => Input::get('tags'),
							'content' => Input::get('content'),
							'post_date' => date('Y-m-d H:i:s')
						));
						
						Session::flash('home', 'Post created.');
						Redirect::to('post.php?id=' . $database->pdo()->lastInsertId());
						
					} catch(Exception $e) {
						die($e->getMessage());
					}
				} else {
					foreach($validation->errors() as $error) {
						echo $error, '<br>';
					}
					echo '<br>';
				}
			}
		}
		?>
		
		<form class="postform" action="" method="post">
			<div class="field">
				<input type="text" placeholder="Title" name="title" value="<?php echo escape(Input::get('title')) ?>">
			</div>
			<div class="field">
				<input type="text" placeholder="Tags" name="tags" value="<?php echo escape(Input::get('tags')) ?>">
			</div>
			<div class="field">
					<select name="language">
						<option value="" disabled selected>Language</option>
						<option value="0">None</option>
						<?php
						foreach($database->get('Languages', array())->results() as $language) {
							if($language->id == Input::get('language')) {
								echo "<option selected value=\"{$language->id}\">{$language->language_name}</option>";
							} else {
								echo "<option value=\"{$language->id}\">{$language->language_name}</option>";
							}
							
						}
						?>
					</select>
			</div>
			<div class="field">
				<textarea placeholder="Content" name="content" cols="60" rows="20"><?php echo escape(Input::get('content')) ?></textarea>
			</div>
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<input type="submit" value="Submit">
		</form>
	</div>
	<div class="rcol">
		<?php require_once 'includes/sidebar.php'; ?>
	</div>
</div>
</body>
</html>