<?php
require_once 'core/init.php';

$database = Database::getInstance();
$user = new User();

if(!$user->isLoggedIn()) {
	Session::flash('home', 'You must be logged in.');
	Redirect::to('index.php');
}

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
					'user_id' => $user->data()->id,
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

<p><a href="index.php">Home</a></p>

<form action="" method="post">
	<div class="field">
		<label for="title">Title</label>
		<input type="text" name="title" value="<?php echo escape(Input::get('title')) ?>">
	</div>
	<div class="field">
		<label for="tags">Tags</label>
		<input type="text" name="tags" value="<?php echo escape(Input::get('tags')) ?>">
	</div>
	<div class="field">
		<label for="language">Language</label>
			<select name="language">
				<option selected value="0">None</option>
				<?php
				foreach($database->get('Languages', array())->results() as $result) {
					echo "<option value=\"{$result->id}\">{$result->language_name}</option>";
				}
				?>
			</select>
	</div>
	<div class="field">
		<label for="content">Content</label><br>
		<textarea name="content" cols="60" rows="20"><?php echo escape(Input::get('content')) ?></textarea>
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="Submit">
</form>