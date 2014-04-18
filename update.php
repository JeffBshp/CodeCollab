<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
	Session::flash('home', 'You must be logged in.');
	Redirect::to('index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>CodeCollab: Edit Profile</title>
	<meta charset="utf-8">
	<link rel="stylesheep" type="text/css" media="all" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>
<?php require_once 'includes/navigation.php'; ?>
<div id="content" class="clearfix">
	<div class="lcol">
		<p><a href="profile.php?user=<?php echo $user->getUsername() ?>">Back</a></p><hr />
		<?php
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
					$newImage = $user->getProfilePicture();
					$output = ImageUpload::upload();
					if(!is_null($output) && $output != "error") {
						$newImage = $output;
					}

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

						if($output != "error") {
							$user->update(array('profile_picture' => $newImage));
							Session::flash('home', 'Your information has been updated.');
							Redirect::to('profile.php?user=' . $user->getUsername());
						} else {
							Redirect::to('update.php?e=error');
						}
						
						
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

		<?php
		$visibilities = $user->getVisible();
		?>
		
		<form action="" method="post" enctype="multipart/form-data">
			<div class="field">
				<label for="first_name">First Name</label>
				<input type="text" name="first_name" value="<?php echo $user->getFirstName() ?>">
			</div>
			<div class="field">
				<label for="last_name">Last Name</label>
				<input type="text" name="last_name" value="<?php echo $user->getLastName() ?>">
			</div>
			<div class="field">
				<label for="email">Email Address</label>
				<input type="text" name="email" value="<?php echo $user->getEmail() ?>">
			</div>
			<div class="field">
				<label for="name_visible">
					<input type="checkbox" name="name_visible"<?php echo ($visibilities['name'] ? ' checked="1"' : '') ?>>Name Visible
				</label>
			</div>
			<div class="field">
				<label for="email_visible">
					<input type="checkbox" name="email_visible"<?php echo ($visibilities['email'] ? ' checked="1"' : '') ?>>Email Visible
				</label>
			</div>
			<div class="field">
				<label for="about_visible">
					<input type="checkbox" name="about_visible"<?php echo ($visibilities['about'] ? ' checked="1"' : '') ?>>About Visible
				</label>
			</div>
			<div class="field">
				<label for="posts_visible">
					<input type="checkbox" name="posts_visible"<?php echo ($visibilities['posts'] ? ' checked="1"' : '') ?>>Posts Visible
				</label>
			</div>
			<div class="field">
				<label for="about">About</label><br>
				<textarea name="about" cols="40" rows="10"><?php echo (Input::get('about') ? escape(Input::get('about')) : $user->getAbout()); ?></textarea>
			</div>

			<div class="field">
				<label for="image">Profile Image</label><br />
				<?php
					if($_GET['e'] == "error") {
						echo '<span style="font-size: 12px; color: red;">Upload unsucessful!</span><br />';
					}
				?>
				<input type="file" name="image"><br />
				<span style="font-size: 12px;">Max File Size: 2MB (PNG, JPG, JPEG, GIF accepted)</span>
			</div>
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<input style="float: left;" type="submit" value="Update">
		</form>
	</div>
	<div class="rcol">
		<?php require_once 'includes/sidebar.php'; ?>
	</div>
</div>
</body>
</html>