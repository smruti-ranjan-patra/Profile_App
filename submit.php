<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	// session_destroy();

	//require_once('config/db_connection.php');
	require_once('config/photo_path.php');
	require_once('class/DatabaseConnection.php');
	require_once('class/Validation.php');
	require_once('config/constants.php');
	$obj = DatabaseConnection::create_connection();

	$pic_update = FALSE;
	
	if(isset($_POST['submit']))
	{
		$validate_obj = new Validation($_POST);
		$error_count = 0;
		$validate_obj->validate_form('submit');

		// Validating Email ID
		// $validate_obj->email($_POST['email']);

		// Validating Picture
		$pic_info = $validate_obj->photo_validation();
		$pic_update = $pic_info['pic_update'];
		$file_name = $pic_info['name'];
		
		// Validating Notes
		$notes = $validate_obj->notes_validation($_POST['notes']);

		// Validating Communication Medium
		$comm = $validate_obj->comm_validation($_POST['comm']);

		// Fetching no. of errors from the validation object
		$error_count += $validate_obj->count;

		if($error_count > 0)
		{
			header("Location:sign_up.php?validation=1");
			exit();
		}
		$check_edit_id = FALSE;
		if(isset($_POST['edit_id']) && $_POST['edit_id'] != 0)
		{
			if($pic_update)
			{
				$obj->delete_pic($_POST['edit_id']);
			}
			$comm = implode(', ', $_POST['comm']);

			// echo "111111"; exit;
			$employee_data_array = array(
				"emp_id" => $_POST['edit_id'], 
				"first_name" => $_POST['first_name'], 
				"middle_name" => $_POST['middle_name'], 
				"last_name" => $_POST['last_name'], 
				"email" => $_POST['email'], 
				"password" => $_POST['password'], 
				"prefix" => $_POST['prefix'], 
				"gender" => $_POST['gender'], 
				"dob" => $_POST['dob'], 
				"marital_status" => $_POST['marital'], 
				"employment" => $_POST['employment'], 
				"employer" => $_POST['employer'], 
				"photo" => "$file_name", 
				"extra_note" => "$notes", 
				"comm_id" => "$comm", 
				"r_street" => $_POST['r_street'], 
				"r_city" => $_POST['r_city'], 
				"r_state" => $_POST['r_state'], 
				"r_zip" => $_POST['r_zip'], 
				"r_phone" => $_POST['r_phone'], 
				"r_fax" => $_POST['r_fax'], 
				"o_street" => $_POST['o_street'], 
				"o_city" => $_POST['o_city'], 
				"o_state" => $_POST['o_state'], 
				"o_zip" => $_POST['o_zip'], 
				"o_phone" => $_POST['o_phone'], 
				"o_fax" => $_POST['o_fax'], 
				);

			$obj->update_table('address', $employee_data_array);

			if($pic_update)
			{
				$employee_data_array["photo"] = $file_name;
				$obj->update_table('employee', $employee_data_array);
			}
			else
			{
				$obj->update_table('employee', $employee_data_array);
			}
			$check_edit_id = TRUE;
			header("Location: details.php");
		}

		if($check_edit_id == FALSE)
		{

			$employee_data_array = array(
				"first_name" => $_POST['first_name'], 
				"middle_name" => $_POST['middle_name'], 
				"last_name" => $_POST['last_name'], 
				"email" => $_POST['email'], 
				"password" => $_POST['password'], 
				"prefix" => $_POST['prefix'], 
				"gender" => $_POST['gender'], 
				"dob" => $_POST['dob'], 
				"marital_status" => $_POST['marital'], 
				"employment" => $_POST['employment'], 
				"employer" => $_POST['employer'], 
				"photo" => "$file_name", 
				"extra_note" => "$notes", 
				"comm_id" => "$comm" );

			$connection = $obj->insert_full('employee', $employee_data_array);

			$employee_id = mysqli_insert_id($connection);

			$address_data_array = array(
				"emp_id" => "$employee_id", 
				"r_street" => $_POST['r_street'], 
				"r_city" => $_POST['r_city'], 
				"r_state" => $_POST['r_state'], 
				"r_zip" => $_POST['r_zip'], 
				"r_phone" => $_POST['r_phone'], 
				"r_fax" => $_POST['r_fax'], 
				"o_street" => $_POST['o_street'], 
				"o_city" => $_POST['o_city'], 
				"o_state" => $_POST['o_state'], 
				"o_zip" => $_POST['o_zip'], 
				"o_phone" => $_POST['o_phone'], 
				"o_fax" => $_POST['o_fax'], 
				);

			$connection = $obj->insert_full('address', $address_data_array);
		}
	}
	else
	{
		exit;
	}
	header("Location:details.php");
?>