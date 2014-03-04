<!DOCTYPE html>
<head>
	<title>CodeCollab</title>
	<link rel="stylesheep" type="text/css" media="all" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		$(window).load(function() {
			var col1 = $("#col1");
			var col2 = $("#col2");
			var toHeight = $("#content").height();
			col1.height(toHeight);
			col2.height(toHeight);
		});

		$
	</script>
</head>

<body>
<div id="nav"></div>
<div id="content" class="clearfix">
	<div id="col1">
		content1<br />
		content1<br />
		content1<br />
		content1<br />
		content1<br />
		content1<br />
		content1<br />
		content1<br />
		content1<br />
		content1<br />
	</div>
	<div id="col2">
		content2
	</div>

</div>
</body>
</html>