<?php
require_once 'core/init.php';

$user = new User();

if($user->isLoggedIn()) {
	Session::flash('home', 'You are already logged in.');
	Redirect::to('index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>CodeCollab: Log In</title>
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
					'username' => array('required' => true),
					'password' => array('required' => true)
				));
				
				if($validation->passed()) {
					
					$login = $user->login(Input::get('username'), Input::get('password'));
					
					if($login) {
						Session::flash('home', 'You are now logged in.');
						Redirect::to('index.php');
					} else {
						echo '<span class="error">Login failed</span><br />';
					}
					
				} else {
					foreach($validation->errors() as $error) {
						echo '<span class="error">' . $error . '</span><br />';
					}
					echo '<br>';
				}
			}
		}
		?>
		<h3>Log in</h3><br /><br />
		<form action="" method="post">
			<div class="field">
				<input type="text" name="username" id="username" placeholder="Username" autocomplete="off">
			</div>
			<div class="field">
				<input type="password" name="password" id="password" placeholder="Password" autocomplete="off">
			</div>
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<input type="submit" value="Log In">
		</form>
	</div>
	<div class="rcol">
		<?php require_once 'includes/searchbar.php'; ?>
	</div>
</div>
</body>
</html>