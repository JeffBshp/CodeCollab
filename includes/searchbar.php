<?php
require_once 'core/init.php';

if(Session::exists('home')) {
	echo '<p>' . Session::flash('home') . '</p>';
}
?>

<form method="get" action="search.php">
	<input class="search" type="text" placeholder="Search" name="query" />
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