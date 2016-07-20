<?php

	require_once('class/DatabaseConnection.php');
	require_once('config/database.php');

	$obj = DatabaseConnection::create_connection($db['master']);

	if(isset($_GET['id']))
	{
		$obj->delete_pic($_GET['id']);
		$obj->delete_employee($_GET['id']);
	}
	
	header("Location: details.php");

?>
