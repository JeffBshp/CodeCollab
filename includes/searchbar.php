<?php
require_once 'core/init.php';
?>



<form method="get" action="search.php">
	<input class="search" type="text" value="<?php echo Input::get('query'); ?>" placeholder="Search" name="query" /><br />

	<?php
	if(Input::get('order') == "date") {
		echo '<input type="radio" name="order" value="score" />Score';
		echo '<input type="radio" name="order" value="date" checked />Date';
	} else {
		echo '<input type="radio" name="order" value="score" checked />Score';
		echo '<input type="radio" name="order" value="date" />Date';
	}
	
	if($user->isLoggedIn()) {
		?>
		<hr /><p><a href="createpost.php">Create a new post</a></p>
		<?php
	}
	?>
	
</form>

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