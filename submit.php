<?php

	session_start();
	require_once('class/DatabaseConnection.php');
	require_once('class/Validation.php');
	require_once('config/constants.php');
	require_once('config/database.php');
	$obj = DatabaseConnection::create_connection($db['master']);
	$pic_update = FALSE;
	
	if(isset($_POST['submit']))
	{
		$validate_obj = new Validation($_POST);
		$error_count = 0;
		$validate_obj->validate_form('submit');

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

		$user_id = isset($_POST['edit_id']) ? $_POST['edit_id'] : $_SESSION['id'];

		if($error_count > 0)
		{
			header('Location:sign_up.php?validation=1&id=' . $user_id);
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

			$employee_data_array = array(
				"emp_id" => $_POST['edit_id'], 
				"first_name" => $_POST['first_name'], 
				"middle_name" => $_POST['middle_name'], 
				"last_name" => $_POST['last_name'], 
				"email" => $_POST['email'], 
				"password" => $_POST['password'], 
				"twitter_name" => $_POST['twitter_name'], 
				"prefix" => $_POST['prefix'], 
				"gender" => $_POST['gender'], 
				"dob" => $_POST['dob'], 
				"marital_status" => $_POST['marital'], 
				"employment" => $_POST['employment'], 
				"employer" => $_POST['employer'], 
				"photo" => "$file_name", 
				"extra_note" => "$notes", 
				"comm_id" => "$comm"
				);

			$residence_data_array = array(
				"emp_id" => $_POST['edit_id'], 
				"street" => $_POST['r_street'], 
				"city" => $_POST['r_city'], 
				"state" => $_POST['r_state'], 
				"zip" => $_POST['r_zip'], 
				"phone" => $_POST['r_phone'], 
				"fax" => $_POST['r_fax']
				);

			$office_data_array = array(
				"emp_id" => $_POST['edit_id'], 
				"street" => $_POST['o_street'], 
				"city" => $_POST['o_city'], 
				"state" => $_POST['o_state'], 
				"zip" => $_POST['o_zip'], 
				"phone" => $_POST['o_phone'], 
				"fax" => $_POST['o_fax']
				);

			$obj->update_table('address', $residence_data_array, 'residence');
			$obj->update_table('address', $office_data_array, 'office');
			$obj->update_table('employee', $employee_data_array);

			$check_edit_id = TRUE;
			header('Location: details.php');
		}

		if($check_edit_id == FALSE)
		{

			$employee_data_array = array(
				"first_name" => $_POST['first_name'], 
				"middle_name" => $_POST['middle_name'], 
				"last_name" => $_POST['last_name'], 
				"email" => $_POST['email'], 
				"password" => $_POST['password'], 
				"twitter_name" => $_POST['twitter_name'], 
				"prefix" => $_POST['prefix'], 
				"gender" => $_POST['gender'], 
				"dob" => $_POST['dob'], 
				"marital_status" => $_POST['marital'], 
				"employment" => $_POST['employment'], 
				"employer" => $_POST['employer'], 
				"photo" => "$file_name", 
				"extra_note" => "$notes", 
				"comm_id" => "$comm" );

			$connection = $obj->insert_data('employee', $employee_data_array);
			$employee_id = mysqli_insert_id($connection);

			$residence_data_array = array(
				"emp_id" => "$employee_id", 
				"address_type" => "residence", 
				"street" => $_POST['r_street'], 
				"city" => $_POST['r_city'], 
				"state" => $_POST['r_state'], 
				"zip" => $_POST['r_zip'], 
				"phone" => $_POST['r_phone'], 
				"fax" => $_POST['r_fax']
				);

			$office_data_array = array(
				"emp_id" => "$employee_id", 
				"address_type" => "office", 
				"street" => $_POST['o_street'], 
				"city" => $_POST['o_city'], 
				"state" => $_POST['o_state'], 
				"zip" => $_POST['o_zip'], 
				"phone" => $_POST['o_phone'], 
				"fax" => $_POST['o_fax']
				);

			$connection = $obj->insert_data('address', $residence_data_array);
			$connection = $obj->insert_data('address', $office_data_array);
		}
	}
	else
	{
		exit;
	}
	
	header('Location:details.php');

?>
