<?php
// session_destroy();
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('class/Validation.php');
$errors = 0;
$validate_obj = new Validation($_POST);
$errors = $validate_obj->validate_form('login');

if($errors == 0)
{	
	header("Location:sign_up.php?id=".$_SESSION['id']);
}
else
{
	header("Location:login_form.php");
}
?>