<?php
require_once 'core/init.php';

if(!$id = Input::get('id')) {
	Redirect::to('index.php');
}

$database = Database::getInstance();
$viewer = new User();
$post = new Post($id);
$promoted = false;
$owned = false;

if($post->exists()) {
	$data = $post->data();
} else {
	Session::flash('home', 'Page does not exist.');
	Redirect::to('index.php');
}

$user = new User($data->user_id);

if($viewer->isLoggedIn()) {

	foreach($database->get('Promotion', array('user_id', '=', $viewer->data()->id))->results() as $promotion) {
		if($promotion->post_id === $data->id) {
			$promoted = true;
		}
	}
	
	if($viewer->data()->id === $data->user_id) {
		$owned = true;
	}
	
	if(Input::exists()) {
		if(Token::check(Input::get('promote_token'), 'token_name_1')) {
			if($promoted) {
				try {
					$promote_id = 0;
					foreach($database->get('Promotion', array('user_id', '=', $viewer->data()->id))->results() as $promotion) {
						if($promotion->post_id === $data->id) {
							$promote_id = $promotion->id;
						}
					}
					$database->delete('Promotion', array('id', '=', $promote_id));
					Redirect::to('post.php?id=' . $data->id);
				} catch(Exception $e) {
					die($e->getMessage());
				}
			} else {
				try {
					$database->insert('Promotion', array(
						'post_id' => $data->id,
						'user_id' => $viewer->data()->id,
						'promotion_date' => date('Y-m-d H:i:s')
					));
					
					Redirect::to('post.php?id=' . $data->id);
				} catch(Exception $e) {
					die($e->getMessage());
				}
			}
		} else if(Token::check(Input::get('comment_token'), 'token_name_2')) {
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
						'post_id' => $data->id,
						'user_id' => $viewer->data()->id,
						'content' => Input::get('comment'),
						'comment_date' => date('Y-m-d H:i:s')
					));
					
					Redirect::to('post.php?id=' . $data->id);
					
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
}
?>

<!DOCTYPE html>
<head>
	<title>Post Page: <?php echo $data->title ?></title>
	<meta charset="utf-8">
	<link rel="stylesheep" type="text/css" media="all" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
   			$('.search').keydown(function(event) {
	        	if (event.keyCode == 13) {
	            this.form.submit();
	            return false;
         		}
    		});
		});
	</script>
</head>

<body>
<?php require_once 'includes/navigation.php'; ?>
<div id="content" class="clearfix">
	<div class="lcol">
	<h1><?php echo $data->title ?></h1>
	<p><em>
		<a href="profile.php?user=<?php echo $user->data()->username; ?>"><?php echo $user->data()->username; ?></a><br>
		<?php echo escape($data->post_date) ?>
	</em></p>
	
	<?php
	if($promoted) {
		echo "<form action='' method='post'>
			<input type='hidden' name='promote_token' value='". Token::generate('token_name_1') ."'>
			<input type='submit' value='Undo Promotion'>
			</form>";
	} else if($viewer->isLoggedIn() && !$owned) {
		echo "<form action='' method='post'>
			<input type='hidden' name='promote_token' value='". Token::generate('token_name_1') ."'>
			<input type='submit' value='Promote'>
			</form>";
	}
	?>
	
	<hr />
		<?php
		use \Michelf\Markdown;
		echo Markdown::defaultTransform($data->content);
		?>
	</div>
	<div class="rcol">
		<?php require_once 'includes/searchbar.php'; ?>
	</div>

	<div class="lcol" style="background: #F4F4F4; border-top: 1px solid #DDDDDD;">
	
		<?php if($viewer->isLoggedIn()) { ?>
		<form action="" method="post">
			<div class="field">
				<label for="comment">Leave a comment:</label><br>
				<textarea name="comment" cols="40" rows="5"><?php echo escape(Input::get('comment')) ?></textarea>
			</div>
			<input type="hidden" name="comment_token" value="<?php echo Token::generate('token_name_2'); ?>">
			<input type="submit" value="Submit">
		</form>
		<hr />
		<?php } ?>
		
		<?php
			foreach($database->get('Comments', array('post_id', '=', $data->id))->results() as $comment) {
				$comment = new Comment($comment->id);
				$commenter = new User($comment->data()->user_id);
				echo "<a href=\"profile.php?user={$commenter->data()->username}\">{$commenter->data()->username}</a><br>";
				echo "<span style=\"font-style: italic; font-size: 12px;\">{$comment->data()->comment_date}</span>";
				echo "<p>{$comment->data()->content}</p>
						<hr />";
			}
		?>
	</div>
</div>
</body>
</html>