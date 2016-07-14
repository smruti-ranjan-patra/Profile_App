<?php
	session_start();

	if(!isset($_SESSION['id']))
	{
		header('Location:home_default.php');
	}

	session_unset();
	session_destroy();

	header("Location:login_form.php");
?>

