<?php
session_start();
if(!isset($_SESSION['id']))
{
	header("Location:home_default.php");
}

session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Display Page</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body background="images/home_background.jpg">
	<!-- Navigation bar -->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="home_default.php">Home</a></li>
			</ul>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
			<h1>You have successgully signed out</h1>
			</div>
		</div>
	</div>
</body>
</html>
