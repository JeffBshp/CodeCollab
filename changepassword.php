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
			'current_password' => array('required' => true),
			'password' => array(
				'required' => true,
				'min' => 6,
				'max' => 35,
				'match' => 'repeat_password'
			),
			'repeat_password' => array(
				'required' => true,
				'min' => 6,
				'max' => 35
			)
		));
		
		if($validation->passed()) {
			if($user->data()->pass_hash === Hash::make(Input::get('current_password'), $user->data()->salt)) {
				
				$salt = Hash::salt(32);
				
				try {
					$user->update(array(
						'pass_hash' => Hash::make(Input::get('password'), $salt),
						'salt' => $salt
					));
					
					Session::flash('home', 'Your password has been changed.');
					Redirect::to('profile.php?user=' . $user->data()->username);
					
				} catch(Exception $e) {
					die($e->getMessage());
				}
			} else {
				echo 'Incorrect password.<br><br>';
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
		<label for="current_password">Current Password</label>
		<input type="password" name="current_password" id="current_password">
	</div>
	<div class="field">
		<label for="password">New Password</label>
		<input type="password" name="password" id="password">
	</div>
	<div class="field">
		<label for="repeat_password">Repeat New Password</label>
		<input type="password" name="repeat_password" id="repeat_password">
	</div>
	
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="Change Password">
</form>