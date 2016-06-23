<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	// session_destroy();

	require_once('config/db_connection.php');
	require_once('config/photo_path.php');
	require_once('class/oops.php');
	$obj = DatabaseConnection::create_connection();

	$pic_update = FALSE;
	
	if(isset($_POST['submit']))
	{
		$validate_obj = new Validation($_POST);
		$error_count = 0;
		$validate_obj->validate_form();

		// Validating Picture
		if(isset($_FILES['pic']))
		{
			$errors = array();
			$file_name = $_FILES['pic']['name'];
			$file_size = $_FILES['pic']['size'];

			if ($file_size > 0) 
			{
				// $pic_update = TRUE;
				$file_tmp = $_FILES['pic']['tmp_name'];
				$file_type= $_FILES['pic']['type'];

				$ext_arr = explode('.',$file_name);
				$file_ext = strtolower(end($ext_arr));
				$extensions = array("jpeg","jpg","png");
				if(in_array($file_ext, $extensions) === false)
				{
					$errors[] = "extension not allowed, please choose a JPEG or PNG file.";
					$_SESSION['error_array']['photo']['val'] = $file_name;
					$_SESSION['error_array']['photo']['msg'] = 'Please choose jpg, jpeg or png file';
					header("Location: registration_form.php?validation=1");
					exit();
				}
				if($file_size > 8388608)
				{
					$errors[] = 'File size must be excately 2 MB';
				}
				if(empty($errors) == true)
				{
					move_uploaded_file($file_tmp, PIC_PATH.$file_name);
					$pic_update = TRUE;
				}
				else
				{
					//print_r($errors);
				}
			}
			$_SESSION['error_array']['photo']['val'] = $file_name;
			$_SESSION['error_array']['photo']['msg'] = 'Please Provide a Photo';	
		}
		else
		{
			$_SESSION['error_array']['photo']['val'] = ' ';
			$_SESSION['error_array']['photo']['msg'] = 'Invalid Photo';
			$error_count++;
		}
		
		// Validating Notes
		if(isset($_POST['notes']))
		{
			$notes = formatted($_POST['notes']);
			$_SESSION['error_array']['notes']['val'] = $notes;
			$_SESSION['error_array']['notes']['msg'] = '';
		}
		else
		{
			$notes = ' ';
			$_SESSION['error_array']['notes']['val'] = $notes;
			$_SESSION['error_array']['notes']['msg'] = '';
		}

		// Validating Communication Medium
		if(isset($_POST['comm']) && !empty($_POST['comm']))
		{
			$comm = implode(', ', $_POST['comm']);
			$_SESSION['error_array']['comm']['val'] = $_POST['comm'];
			$_SESSION['error_array']['comm']['msg'] = '';
		}
		else
		{
			$_SESSION['error_array']['comm']['val'] = '';
			$_SESSION['error_array']['comm']['msg'] = 'Select at least one medium';
			$error_count++;
		}

		$error_count += $validate_obj->count;

		if($error_count > 0)
		{
			header("Location: registration_form.php?validation=1");
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
			header("Location: display.php");
		}

		if($check_edit_id == FALSE)
		{

			$employee_data_array = array(
				"first_name" => $_POST['first_name'], 
				"middle_name" => $_POST['middle_name'], 
				"last_name" => $_POST['last_name'], 
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
	/**
	* Trims extra spaces, deletes slashes, translates the string
	*
	* @param string
	* @return string
	*/
	function formatted($data)
		{
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
	header("Location: display.php");
?>