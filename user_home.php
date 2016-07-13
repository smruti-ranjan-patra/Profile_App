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
				echo '<h1>Welcome ' . $_SESSION['permission_info']['role'] . '</h1><hr>';
				$path_parts = pathinfo($_SERVER['REQUEST_URI']);
				$file_name = $path_parts['filename'];

				if(is_allowed($file_name, 'view'))
				{
					echo "<h3>View</h3>";
				}

				if(is_allowed($file_name, 'edit'))
				{
					echo "<h3>Edit</h3>";
				}

				if(is_allowed($file_name, 'delete'))
				{
					echo "<h3>Delete</h3>";
				}

			?>
		</div>
	</body>
</html>

