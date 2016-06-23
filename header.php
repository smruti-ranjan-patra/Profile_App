
<?php
if($_SESSION['logged_in'] == FALSE)
{
	?>
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="default_home.php">Home</a></li>
				<li><a href="registration_form.php">Sign Up</a></li>
				<li><a href="login.php">Login</a></li>
			</ul>
		</div>
	</nav>
	<?php
}
elseif($_SESSION['logged_in'] == TRUE)
{
	?>
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="user_home.php">Home</a></li>
				<li><a href="details.php">Details</a></li>
				<li><a href="sign_out.php">Sign out</a></li>
			</ul>
		</div>
	</nav>
	<?php
}

?>