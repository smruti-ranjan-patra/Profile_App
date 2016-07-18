<?php

	require_once('header.php');

?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Login</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
		<script type="text/javascript" src="js/jquery_validation.js"></script>
	</head>
	<body background="images/home_background.jpg">

		<!-- Navigation bar -->
		<?php

			$header_array = ['login_home.php' => 'Home', 'dashboard.php' => 'Change Permissions', 'sign_up.php' => 'View Profile', 'details.php' => 'Details', 'sign_out.php' => 'Sign out'];
			display_header($header_array);
		
		?>

		<h1>Welcome</h1>
	</body>
</html>