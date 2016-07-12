<?php
session_start();
require_once('acl.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>User Home</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	</head>
	<body background="images/home_background.jpg">
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<li class="active"><a href="user_home.php">Home</a></li>
					<li><a href="sign_up.php">View Profile</a></li>
				</ul>
			</div>
		</nav>
		<div>
			<?php
				echo 'Welcome ' . $_SESSION['permission_info']['role']; 
			?>
		</div>
	</body>
</html>

