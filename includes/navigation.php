<div id="nav">
	<div id="navcontent">
		<div id="logo">
			<a href="./index.php">CodeCollab</a>
		</div>
		<ul>
		<li><a href="index.php">Home</a></li>
		<?php
		$user = new User();
		if($user->isLoggedIn()) {
		
			echo '<li><a href="profile.php?user='. escape($user->getUsername()) .'">'. escape($user->getUsername()) .'</a></li>';
			echo '<li><a href="logout.php">Log Out</a></li>';
		
		} else {
		
			echo '<li><a href="login.php">Log In</a></li>';
			echo '<li><a href="register.php">Register</a></li>';
		
		}
		if(Session::exists('home')) {
			//echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . Session::flash('home');
		}
		?>
		</ul>
	</div>
</div>

<script>
	$(document).ready(function() {
		setContentHeight();
	});

	$(document).scroll(function() {
		var height = $(window).scrollTop();

		if(height > 50) {
			$("#nav").addClass("dim");
		} else {
			$("#nav").removeClass("dim");
		}
	});

	function setContentHeight() {
		var lcol = $(".lcol:nth-of-type(1)");
		var content = $("#content");

		var lcolHeight = lcol.height() + lcol.offset().top + 25;
		var loc = $(location).attr('pathname');
		if(loc.indexOf("post") < 0) {
			if(lcolHeight < $(window).height()) {
				lcol.height($(window).height() - 71);
			} else if(lcolHeight >= content.height()) {
				content.height(lcolHeight);
			} else {
				var divTop = content.offset().top;
				var winHeight = $(window).height();
				var divHeight = winHeight - divTop;
				content.height(divHeight);
			}
		}	
	}
</script>