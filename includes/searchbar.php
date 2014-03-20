<form method="get" action="search.php">
	<input type="text" value="<?php echo Input::get('query'); ?>" placeholder="Search" name="query" />
	<span style="font-weight:bold; font-size: 12px;">Sort by: </span>
	<?php
	if(Input::get('order') == "date") {
		echo '<input type="radio" name="order" value="score" /><span class="radio">Score</span>';
		echo '<input type="radio" name="order" value="date" checked /><span class="radio">Date</span>';
	} else {
		echo '<input type="radio" name="order" value="score" checked /><span class="radio">Score</span>';
		echo '<input type="radio" name="order" value="date" /><span class="radio">Date</span>';
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