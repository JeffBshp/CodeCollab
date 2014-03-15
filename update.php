<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
	Session::flash('home', 'You must be logged in.');
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'first_name' => array(
				'max' => 35
			),
			'last_name' => array(
				'max' => 35
			),
			'email' => array(
				'required' => true,
				'min' => 2,
				'max' => 255
			)
		));
		
		if($validation->passed()) {
			try {
				$user->update(array(
					'first_name' => Input::get('first_name'),
					'last_name' => Input::get('last_name'),
					'email' => Input::get('email'),
					'about' => Input::get('about'),
					'name_visible' => Input::get('name_visible') === 'on' ? 1 : 0,
					'email_visible' => Input::get('email_visible') === 'on' ? 1 : 0,
					'about_visible' => Input::get('about_visible') === 'on' ? 1 : 0,
					'posts_visible' => Input::get('posts_visible') === 'on' ? 1 : 0
				));
				
				Session::flash('home', 'Your information has been updated.');
				Redirect::to('index.php');
				
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

<p><a href="profile.php?user=<?php echo $user->data()->username ?>">Back</a></p>

<form action="" method="post">
	<div class="field">
		<label for="first_name">First Name</label>
		<input type="text" name="first_name" value="<?php echo escape($user->data()->first_name) ?>">
	</div>
	<div class="field">
		<label for="last_name">Last Name</label>
		<input type="text" name="last_name" value="<?php echo escape($user->data()->last_name) ?>">
	</div>
	<div class="field">
		<label for="email">Email Address</label>
		<input type="text" name="email" value="<?php echo escape($user->data()->email) ?>">
	</div>
	<div class="field">
		<label for="name_visible">
			<input type="checkbox" name="name_visible"<?php echo ($user->data()->name_visible ? ' checked="1"' : '') ?>>Name Visible
		</label>
	</div>
	<div class="field">
		<label for="email_visible">
			<input type="checkbox" name="email_visible"<?php echo ($user->data()->email_visible ? ' checked="1"' : '') ?>>Email Visible
		</label>
	</div>
	<div class="field">
		<label for="about_visible">
			<input type="checkbox" name="about_visible"<?php echo ($user->data()->about_visible ? ' checked="1"' : '') ?>>About Visible
		</label>
	</div>
	<div class="field">
		<label for="posts_visible">
			<input type="checkbox" name="posts_visible"<?php echo ($user->data()->posts_visible ? ' checked="1"' : '') ?>>Posts Visible
		</label>
	</div>
	<div class="field">
		<label for="about">About</label><br>
		<textarea name="about" cols="40" rows="10"><?php echo (Input::get('about') ? Input::get('about') : escape($user->data()->about)) ?></textarea>
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="Submit">
</form>