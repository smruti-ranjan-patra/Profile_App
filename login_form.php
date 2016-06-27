<?php
// session_destroy();
session_start();
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Login</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	</head>
	<body background="images/home_background.jpg">
	<!-- Navigation bar -->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="home_default.php">Home</a></li>
				<li><a href="sign_up.php">Sign Up</a></li>
				<li class="active"><a href="login.php">Login</a></li>
			</ul>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
				<form action="login.php" method="post">
					<fieldset>
					<div class="well">

						<!-- Email id field -->
						<div class="row form-group">
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
								<label for="email">Email ID:</label>
							</div>
							<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
								<input type="text" name="email" id="email" 
								class="form-control" placeholder="Email@mail.com">
							</div>
						</div>

						<!-- Password field -->
						<div class="row form-group">
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
								<label for="password">Password:</label>
							</div>
							<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
								<input type="password" name="password" id="password" 
								class="form-control" placeholder="********">
							</div>
						</div>
						<div class="text-center">
						<?php
						if(isset($_SESSION['error_array']['login']['msg']))
						{
							echo '<span class="text-danger">'.$_SESSION['error_array']
							['login']['msg']."</span>";
						}
						?>
						</div>
					</div>
					</fieldset>
					

				<!-- Buttons -->
				<div class="row form-group text-center">
					<button class="btn btn-primary" type="submit" name="login_submit" 
					value="login_submit">Login</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	</body>
</html>
