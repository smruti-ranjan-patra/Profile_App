<?php 
	$host = 'localhost';
	$userName = 'root';
	$password = 'mindfire';
	$dbName = 'registration';
	$conn = mysqli_connect($host,$userName,$password,$dbName);
	if (mysqli_connect_errno($conn))
	{
		die ('Failed to connect to MySQL :' . mysqli_connect_error());
	}
?>
