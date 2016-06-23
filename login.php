<?php
// session_destroy();
session_start();
require_once('class/Validation.php');
$errors = 0;
$validate_obj = new Validation($_POST);
$errors = $validate_obj->validate_form('login');

if($errors == 0)
{
	header("Location: user_home.php");
}
else
{
	header("Location: login_form.php");
}
?>