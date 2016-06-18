<?php

	include('config/db_connection.php');
	include('config/photo_path.php');

	if(isset($_GET['id']))
	{
		$del_add = "DELETE FROM address WHERE emp_id = ". $_GET['id'];
		mysqli_query($conn,$del_add);

		$sel_pic = "SELECT photo FROM employee WHERE id = ".$_GET['id'];
		$pic_object = mysqli_query($conn,$sel_pic);
		$row = mysqli_fetch_array($pic_object, MYSQLI_ASSOC);
		$var_pic = PIC_PATH.$row['photo'];
		unlink($var_pic);

		$del_emp = "DELETE FROM employee WHERE id = ".$_GET['id'];
		mysqli_query($conn,$del_emp);
	}
	header("Location: display.php");
?>