<?php

	require_once('header.php');

?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>home_default</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	</head>
	<body background="images/home_background.jpg">

	<!-- Navigation bar -->
	<?php

		$header_array = ['home_default.php' => 'Home', 'sign_up.php' => 'Sign Up', 'login_form.php' => 'Login'];
		display_header($header_array);

	?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
			<h1>Welcome to my Profile App</h1>
			</div>
		</div>
	</div>
	</body>
</html>
