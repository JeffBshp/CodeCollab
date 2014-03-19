<?php
require_once 'core/init.php';

$user = new User();

if($user->isLoggedIn()) {
	Session::flash('home', 'You must log out before you register.');
	Redirect::to('index.php');
}
?>

<!DOCTYPE html>
<head>
	<title>CodeCollab: Register</title>
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
					'username' => array(
						'required' => true,
						'min' => 2,
						'max' => 20,
						'unique' => 'User',
						'non-numeric' => true
					),
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
					),
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
					$salt = Hash::salt(32);
					
					try {
						$user->register(array(
							'username' => Input::get('username'),
							'first_name' => Input::get('first_name'),
							'last_name' => Input::get('last_name'),
							'email' => Input::get('email'),
							'pass_hash' => Hash::make(Input::get('password'), $salt),
							'salt' => $salt,
							'registration_date' => date('Y-m-d H:i:s'),
							'profile_picture' => '',
							'about' => '',
							'name_visible' => 1,
							'email_visible' => 1,
							'about_visible' => 1,
							'posts_visible' => 1
						));
					} catch(Exception $e) {
						die($e->getMessage());
					}
					
					$user->login(Input::get('username'), Input::get('password'));
					Session::flash('home', 'You are now registered.');
					Redirect::to('index.php');
				} else {
					foreach($validation->errors() as $error) {
						echo $error, '<br>';
					}
					echo '<br>';
				}
			}
		}
		?>
		
		<h3>Register</h3><br /><br />
		<form action="" method="post">
			<div class="field">
				<input type="text" name="username" id="username" placeholder="Username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
			</div>
			<div class="field">
				<input type="text" name="first_name" id="first_name" placeholder="First Name" value="<?php echo escape(Input::get('first_name')); ?>" autocomplete="off">
			</div>
			<div class="field">
				<input type="text" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo escape(Input::get('last_name')); ?>" autocomplete="off">
			</div>
			<div class="field">
				<input type="text" name="email" id="email" placeholder="Email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="off">
			</div>
			<div class="field">
				<input type="password" name="password" id="password" placeholder="Password">
			</div>
			<div class="field">
				<input type="password" name="repeat_password" id="repeat_password" placeholder="Repeat Password">
			</div>
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<input type="submit" value="Register">
		</form>
	</div>
	<div class="rcol">
		<?php require_once 'includes/searchbar.php'; ?>
	</div>
</div>
</body>
</html>