<?php

session_start();
require_once('config/photo_path.php');
require_once('class/DatabaseConnection.php');
$obj = DatabaseConnection::create_connection();

if(isset($_GET['id']))
{
	$obj->delete_from_table('address', $_GET['id']);
	$obj->delete_pic($_GET['id']);
	$obj->delete_from_table('employee', $_GET['id']);
}
header("Location: details.php");

?>