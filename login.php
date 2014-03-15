<?php
require_once 'core/init.php';

$user = new User();

if($user->isLoggedIn()) {
	Session::flash('home', 'You are already logged in.');
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array('required' => true),
			'password' => array('required' => true)
		));
		
		if($validation->passed()) {
			
			$login = $user->login(Input::get('username'), Input::get('password'));
			
			if($login) {
				Session::flash('home', 'You are now logged in.');
				Redirect::to('index.php');
			} else {
				echo 'Login failed.<br>';
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
		<label for="username">Username</label>
		<input type="text" name="username" id="username" autocomplete="off">
	</div>
	<div class="field">
		<label for="password">Password</label>
		<input type="password" name="password" id="password" autocomplete="off">
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="Log In">
</form>