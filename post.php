<?php
require_once 'core/init.php';

if(!$id = Input::get('id')) {
	Redirect::to('index.php');
}

$database = Database::getInstance();
$user = new User();
$post = new Post($id);
$promoted = false;

if(!$post->exists()) {
	Session::flash('home', 'Page does not exist.');
	Redirect::to('index.php');
}

$author = $post->getAuthor();
?>

<!DOCTYPE html>
<head>
	<title>Post Page: <?php echo $post->getTitle() ?></title>
	<meta charset="utf-8">
	<link rel="stylesheep" type="text/css" media="all" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>
<?php require_once 'includes/navigation.php'; ?>
<div id="content" class="clearfix">
	<div class="lcol">
	<h1><?php echo $post->getTitle() ?></h1>
	<p><em>
		By <a href="profile.php?user=<?php echo $author->getUsername(); ?>"><?php echo $author->getUsername(); ?></a><br>
		<?php
		$date = new DateTime($post->getPostDate());
		$date = $date->format('F d, Y \a\t h:ia');
		echo '<em style="font-size: 12px;">' . $date . '</em>';
		?>
	</em></p>
	
	Score: 
	<?php
	echo $database->action('SELECT COUNT(id) AS num', 'Promotion', array('post_id', '=', $post->getId()))->first()->num;
	if($user->isLoggedIn()) {
		foreach($database->get('Promotion', array('user_id', '=', $user->getId()))->results() as $promotion) {
			if($promotion->post_id === $post->getId()) {
				$promoted = true;
			}
		}
		
		if(Input::exists()) {
			if(Token::check(Input::get('promote_token'), 'token_name_1')) {
				echo 'HELLO';
				if($promoted) {
					try {
						$promote_id = 0;
						foreach($database->get('Promotion', array('user_id', '=', $user->getId()))->results() as $promotion) {
							if($promotion->post_id === $post->getId()) {
								$promote_id = $promotion->id;
							}
						}
						$database->delete('Promotion', array('id', '=', $promote_id));
						Redirect::to('post.php?id=' . $post->getId());
					} catch(Exception $e) {
						die($e->getMessage());
					}
				} else {
					try {
						$database->insert('Promotion', array(
							'post_id' => $post->getId(),
							'user_id' => $user->getId(),
							'promotion_date' => date('Y-m-d H:i:s')
						));
						
						Redirect::to('post.php?id=' . $post->getId());
					} catch(Exception $e) {
						die($e->getMessage());
					}
				}
			}
		}
		
		if($promoted) {
			echo "<form action='' method='post'>
				<input type='hidden' name='promote_token' value='". Token::generate('token_name_1') ."'>
				<input type='submit' value='Undo Promotion'>
				</form>";
		} else if($user->getId() !== $author->getId()) {
			echo "<form action='' method='post'>
				<input type='hidden' name='promote_token' value='". Token::generate('token_name_1') ."'>
				<input type='submit' value='Promote'>
				</form>";
		}
	}
	
	?>
	
	<hr />
		<?php
		use \Michelf\Markdown;
		echo Markdown::defaultTransform($post->getContent());
		?>
	</div>
	
	<div class="rcol">
		<?php require_once 'includes/sidebar.php'; ?>
	</div>

	<div class="lcol" style="background: #F4F4F4; border-top: 1px solid #DDDDDD;">
	
		<?php
		if($user->isLoggedIn()) {
			if(Input::exists()) {
				if(Token::check(Input::get('comment_token'), 'token_name_2')) {
					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'comment' => array(
							'required' => true
						)
					));
					
					if($validation->passed()) {
						$comment = new Comment();
						try {
							$comment->create(array(
								'post_id' => $post->getId(),
								'user_id' => $user->getId(),
								'content' => Input::get('comment'),
								'comment_date' => date('Y-m-d H:i:s')
							));
							
							Redirect::to('post.php?id=' . $post->getId());
							
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
				<label for="comment">Leave a comment:</label><br>
				<textarea style="font-family: 'Open Sans', sans-serif;" name="comment" cols="40" rows="5"><?php echo escape(Input::get('comment')) ?></textarea>
			</div>
			<input type="hidden" name="comment_token" value="<?php echo Token::generate('token_name_2'); ?>">
			<input type="submit" value="Submit"><br /> 
		</form>
		<hr />
		<?php
		}
		foreach($database->get('Comments', array('post_id', '=', $post->getId()))->results() as $comment) {
			$comment = new Comment($comment->id);
			$date = new DateTime($comment->getCommentDate());
			$date = $date->format('F d, Y \a\t h:ia');
			echo '<a href="profile.php?user=' . $comment->getCommenter()->getUsername() . '">' . $comment->getCommenter()->getUsername() . '</a><br>';
			echo '<em style="font-size: 12px;">' . $date . '</em>';
			echo '<p>' . $comment->getContent() . '</p>
					<hr />';
		}
		?>
	</div>
</div>
</body>
</html>