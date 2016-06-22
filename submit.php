<?php
	session_start();

	require_once('config/db_connection.php');
	require_once('config/photo_path.php');
	require_once('OOPS.php');
	$obj = new Database;

	$pic_update = FALSE;
	
	if(isset($_POST['submit']))
	{
		$error_count = 0;

		// Validating first name
		if(isset($_POST['first_name']) && !empty($_POST['first_name']))
		{
			$f_name = formatted($_POST['first_name']);
			if(preg_match("/^[a-zA-Z ]*$/",$f_name))
			{
				$_SESSION['error_array']['first_name']['val'] = $f_name;
				$_SESSION['error_array']['first_name']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['first_name']['val'] = $f_name;
				$_SESSION['error_array']['first_name']['msg'] = 'Only Alphabets Allowed';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['first_name']['val'] = '';
			$_SESSION['error_array']['first_name']['msg'] = 'Invalid First Name';
			$error_count++;
		}

		// Validating middle name
		if(isset($_POST['middle_name']) && !empty($_POST['middle_name']))
		{
			$m_name = formatted($_POST['middle_name']);
			if(preg_match("/^[a-zA-Z ]*$/",$m_name))
			{
				$_SESSION['error_array']['middle_name']['val'] = $m_name;
				$_SESSION['error_array']['middle_name']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['middle_name']['val'] = $m_name;
				$_SESSION['error_array']['middle_name']['msg'] = 'Only Alphabets Allowed';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['middle_name']['val'] = '';
			$_SESSION['error_array']['middle_name']['msg'] = '';
		}

		// Validating last name
		if(isset($_POST['last_name']) && !empty($_POST['last_name']))
		{
			$l_name = formatted($_POST['last_name']);
			if(preg_match("/^[a-zA-Z ]*$/",$l_name))
			{
				$_SESSION['error_array']['last_name']['val'] = $l_name;
				$_SESSION['error_array']['last_name']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['last_name']['val'] = $l_name;
				$_SESSION['error_array']['last_name']['msg'] = 'Only Alphabets Allowed';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['last_name']['val'] = '';
			$_SESSION['error_array']['last_name']['msg'] = 'Invalid Last Name';
			$error_count++;
		}

		// Validating prefix
		if(isset($_POST['prefix']))
		{
			$prefix = $_POST['prefix'];
			$_SESSION['error_array']['prefix']['val'] = $prefix;
			$_SESSION['error_array']['prefix']['msg'] = '';
		}
		else
		{
			$_SESSION['error_array']['prefix']['val'] = '';
			$_SESSION['error_array']['prefix']['msg'] = 'Invalid Prefix';
			$error_count++;
		}
		// Validating gender
		if(isset($_POST['gender']))
		{
			$gender = $_POST['gender'];
			$_SESSION['error_array']['gender']['val'] = $gender;
			$_SESSION['error_array']['gender']['msg'] = '';
		}
		else
		{
			$_SESSION['error_array']['gender']['val'] = '';
			$_SESSION['error_array']['gender']['msg'] = 'Invalid Gender';
			$error_count++;
		}

		// Validating date of birth
		if(isset($_POST['dob']) && !empty($_POST['dob']))
		{
			$dob = $_POST['dob'];
			$_SESSION['error_array']['dob']['val'] = $dob;
			$_SESSION['error_array']['dob']['msg'] = '';
		}
		else
		{
			$_SESSION['error_array']['dob']['val'] = '';
			$_SESSION['error_array']['dob']['msg'] = 'Invalid DOB';
			$error_count++;
		}

		// Validating marital status
		if(isset($_POST['marital']))
		{
			$marital = $_POST['marital'];
			$_SESSION['error_array']['marital']['val'] = $marital;
			$_SESSION['error_array']['marital']['msg'] = '';
		}
		else
		{
			$_SESSION['error_array']['marital']['val'] = '';
			$_SESSION['error_array']['marital']['msg'] = 'Invalid Marital Status';
			$error_count++;
		}

		// Validating employment status
		if(isset($_POST['employment']))
		{
			$employment = $_POST['employment'];
			$_SESSION['error_array']['employment']['val'] = $employment;
			$_SESSION['error_array']['employment']['msg'] = '';
		}
		else
		{
			$_SESSION['error_array']['employment']['val'] = '';
			$_SESSION['error_array']['employment']['msg'] = 'Invalid Employment Status';
			$error_count++;
		}

		// Validating employer
		if(isset($_POST['employer']) && !empty($_POST['employer']))
		{
			$employer = formatted($_POST['employer']);
			$_SESSION['error_array']['employer']['val'] = $employer;
			$_SESSION['error_array']['employer']['msg'] = '';
		}
		else
		{
			$_SESSION['error_array']['employer']['val'] = ' ';
			$_SESSION['error_array']['employer']['msg'] = '';
		}

		// Validating residence street
		if(isset($_POST['r_street']) && !empty($_POST['r_street']))
		{
			$r_street = formatted($_POST['r_street']);
			if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $r_street))
			{
				$_SESSION['error_array']['r_street']['val'] = $r_street;
				$_SESSION['error_array']['r_street']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['r_street']['val'] = $r_street;
				$_SESSION['error_array']['r_street']['msg'] = 'Only Alphabets and Numeric Allowed';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['r_street']['val'] = '';
			$_SESSION['error_array']['r_street']['msg'] = 'Invalid Street name';
			$error_count++;
		}

		// Validating residence city
		if(isset($_POST['r_city']) && !empty($_POST['r_city']))
		{
			$r_city = formatted($_POST['r_city']);
			if(preg_match("/^[a-zA-Z ]*$/",$r_city))
			{
				$_SESSION['error_array']['r_city']['val'] = $r_city;
				$_SESSION['error_array']['r_city']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['r_city']['val'] = $r_city;
				$_SESSION['error_array']['r_city']['msg'] = 'Only Alphabets Allowed';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['r_city']['val'] = '';
			$_SESSION['error_array']['r_city']['msg'] = 'Invalid City name';
			$error_count++;
		}

		// Validating residence state
		if(isset($_POST['r_state']) && !empty($_POST['r_state']))
		{
			$r_state = $_POST['r_state'];
			$_SESSION['error_array']['r_state']['val'] = $r_state;
			$_SESSION['error_array']['r_state']['msg'] = '';
		}
		else
		{
			$_SESSION['error_array']['r_state']['val'] = '';
			$_SESSION['error_array']['r_state']['msg'] = 'Invalid State name';
			$error_count++;
		}

		// Validating residence zip
		if(isset($_POST['r_zip']) && !empty($_POST['r_zip']))
		{
			$r_zip = formatted($_POST['r_zip']);
			$num_length = strlen((string)$r_zip);
			if(ctype_digit($r_zip) && 6 == $num_length)
			{
				$_SESSION['error_array']['r_zip']['val'] = $r_zip;
				$_SESSION['error_array']['r_zip']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['r_zip']['val'] = $r_zip;
				$_SESSION['error_array']['r_zip']['msg'] = 'Provide a Numeric value of length 6';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['r_zip']['val'] = '';
			$_SESSION['error_array']['r_zip']['msg'] = 'Invalid Zip';
			$error_count++;
		}

		// Validating residence phone
		if(isset($_POST['r_phone']) && !empty($_POST['r_phone']))
		{
			$r_phone = formatted($_POST['r_phone']);
			$num_length = strlen((string)$r_phone);
			if(ctype_digit($r_phone) && $num_length >= 7 && $num_length <= 12)
			{
				$_SESSION['error_array']['r_phone']['val'] = $r_phone;
				$_SESSION['error_array']['r_phone']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['r_phone']['val'] = $r_phone;
				$_SESSION['error_array']['r_phone']['msg'] = 'Provide a Numeric value between 7 to 
				12 digits';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['r_phone']['val'] = '';
			$_SESSION['error_array']['r_phone']['msg'] = 'Invalid Phone no.';
			$error_count++;
		}

		// Validating residence fax
		if(isset($_POST['r_fax']) && !empty($_POST['r_fax']))
		{
			$r_fax = formatted($_POST['r_fax']);
			$num_length = strlen((string)$r_fax);
			if(ctype_digit($r_fax) && $num_length >= 7 && $num_length <= 12)
			{
				$_SESSION['error_array']['r_fax']['val'] = $r_fax;
				$_SESSION['error_array']['r_fax']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['r_fax']['val'] = $r_fax;
				$_SESSION['error_array']['r_fax']['msg'] = 'Provide a Numeric value between 7 to 12 
				digits';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['r_fax']['val'] = '';
			$_SESSION['error_array']['r_fax']['msg'] = 'Invalid Fax no.';
			$error_count++;
		}

		// Validating office street
		if(isset($_POST['o_street']) && !empty($_POST['o_street']))
		{
			$o_street = formatted($_POST['o_street']);
			if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $_POST['o_street']))
			{
				$_SESSION['error_array']['o_street']['val'] = $o_street;
				$_SESSION['error_array']['o_street']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['o_street']['val'] = $o_street;
				$_SESSION['error_array']['o_street']['msg'] = 'Only Alphabets and Numeric Allowed';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['o_street']['val'] = '';
			$_SESSION['error_array']['o_street']['msg'] = 'Invalid Street name';
			$error_count++;
		}

		// Validating office city
		if(isset($_POST['o_city']) && !empty($_POST['o_city']))
		{
			$o_city = formatted($_POST['o_city']);
			if(preg_match("/^[a-zA-Z ]*$/",$_POST['o_city']))
			{
				$_SESSION['error_array']['o_city']['val'] = $o_city;
				$_SESSION['error_array']['o_city']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['o_city']['val'] = $o_city;
				$_SESSION['error_array']['o_city']['msg'] = 'Only Alphabets Allowed';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['o_city']['val'] = '';
			$_SESSION['error_array']['o_city']['msg'] = 'Invalid City name';
			$error_count++;
		}

		// Validating office state
		if(isset($_POST['o_state']) && !empty($_POST['o_state']))
		{
			$o_state = $_POST['o_state'];
			$_SESSION['error_array']['o_state']['val'] = $o_state;
			$_SESSION['error_array']['o_state']['msg'] = '';
		}
		else
		{
			$_SESSION['error_array']['o_state']['val'] = '';
			$_SESSION['error_array']['o_state']['msg'] = 'Invalid State name';
			$error_count++;
		}

		// Validating office zip
		if(isset($_POST['o_zip']) && !empty($_POST['o_zip']))
		{
			$o_zip = formatted($_POST['o_zip']);
			$num_length = strlen((string)$o_zip);
			if(ctype_digit($o_zip) && 6 == $num_length)
			{
				$_SESSION['error_array']['o_zip']['val'] = $o_zip;
				$_SESSION['error_array']['o_zip']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['o_zip']['val'] = $o_zip;
				$_SESSION['error_array']['o_zip']['msg'] = 'Provide a Numeric value of length 6';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['o_zip']['val'] = '';
			$_SESSION['error_array']['o_zip']['msg'] = 'Invalid Zip';
			$error_count++;
		}

		// Validating office phone
		if(isset($_POST['o_phone']) && !empty($_POST['o_phone']))
		{
			$o_phone = formatted($_POST['o_phone']);
			$num_length = strlen((string)$o_phone);
			if(ctype_digit($o_phone) && $num_length >= 7 && $num_length <= 12)
			{
				$_SESSION['error_array']['o_phone']['val'] = $o_phone;
				$_SESSION['error_array']['o_phone']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['o_phone']['val'] = $o_phone;
				$_SESSION['error_array']['o_phone']['msg'] = 'Provide a Numeric value between 7 to 
				12 digits';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['o_phone']['val'] = '';
			$_SESSION['error_array']['o_phone']['msg'] = 'Invalid Phone no.';
			$error_count++;
		}

		// Validating office fax
		if(isset($_POST['o_fax']) && !empty($_POST['o_fax']))
		{
			$o_fax = formatted($_POST['o_fax']);
			$num_length = strlen((string)$r_fax);
			if(ctype_digit($o_fax) && $num_length >= 7 && $num_length <= 12)
			{
				$_SESSION['error_array']['o_fax']['val'] = $o_fax;
				$_SESSION['error_array']['o_fax']['msg'] = '';
			}
			else
			{
				$_SESSION['error_array']['o_fax']['val'] = $o_fax;
				$_SESSION['error_array']['o_fax']['msg'] = 'Provide a Numeric value between 7 to 12 
				digits';
				$error_count++;
			}
		}
		else
		{
			$_SESSION['error_array']['o_fax']['val'] = '';
			$_SESSION['error_array']['o_fax']['msg'] = 'Invalid Fax no.';
			$error_count++;
		}

		// Validating Picture
		if(isset($_FILES['pic']))
		{
			$errors = array();
			$file_name = $_FILES['pic']['name'];
			$file_size = $_FILES['pic']['size'];

			if ($file_size > 0) 
			{
				$pic_update = TRUE;
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
			$_SESSION['error_array']['photo']['val'] = $notes;
			$_SESSION['error_array']['photo']['msg'] = '';
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
				$q_pic = "SELECT emp.photo AS photo FROM employee AS emp
					WHERE emp.id = ".$_POST['edit_id'];

				$result_pic = mysqli_query($conn, $q_pic);
				$row_pic = mysqli_fetch_array($result_pic, MYSQLI_ASSOC);
				$pic_name = PIC_PATH.$row_pic['photo'];
				unlink($pic_name);
			}

			$q_fetch = "SELECT emp.first_name AS f_name, emp.middle_name AS m_name, 
				emp.last_name AS l_name, emp.prefix AS prefix, emp.gender AS gender, 
				emp.dob AS dob, emp.marital_status AS marital, emp.employment AS employment, 
				emp.employer AS employer, res.street AS r_street, res.city AS r_city, 
				res.state AS r_state, res.zip AS r_zip, res.phone AS r_phone, res.fax AS r_fax, 
				off.street AS o_street, off.city AS o_city, off.state AS o_state, 
				off.zip AS o_zip, off.phone AS o_phone, off.fax AS o_fax, emp.photo AS photo, 
				emp.extra_note AS notes, emp.comm_id AS comm_id 
				from employee AS emp 
				INNER JOIN address AS res ON (emp.id = res.emp_id AND res.address_type = 'residence')
				INNER JOIN address AS off ON (emp.id = off.emp_id AND off.address_type = 'office')
				WHERE emp.id = ".$_POST['edit_id'];

			$result = mysqli_query($conn, $q_fetch);

			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);



			$update_add_res = "UPDATE `address` 
				SET `street` = '$r_street', `city` = '$r_city', `state` = '$r_state', `zip` = $r_zip, 
				`phone` = $r_phone, `fax` = $r_fax 
				WHERE (address_type = 'residence' AND emp_id = ".$_POST['edit_id'].")";

			$update_add_off = "UPDATE `address` 
				SET `street` = '$o_street', `city` = '$o_city', `state` = '$o_state', `zip` = $o_zip, 
				`phone` = $o_phone, `fax` = $o_fax 
				WHERE (address_type = 'office' AND emp_id = ".$_POST['edit_id'].")";

			mysqli_query($conn,$update_add_res);
			mysqli_query($conn,$update_add_off);

			if($pic_update)
			{
				$update_emp = "UPDATE `employee` 
					SET `first_name` = '$f_name', `middle_name` = '$m_name', `last_name` = '$l_name', 
					`prefix` = '$prefix', `gender` = '$gender', `dob` = '$dob', 
					`marital_status` = '$marital', `employment` = '$employment', 
					`employer` = '$employer', `photo` = '$file_name', `extra_note` = '$notes', 
					`comm_id` = '$comm' 
					WHERE id = ".$_POST['edit_id'];

				mysqli_query($conn,$update_emp);
			}
			else
			{
				$update_emp = "UPDATE `employee` 
					SET `first_name` = '$f_name', `middle_name` = '$m_name', `last_name` = '$l_name', 
					`prefix` = '$prefix', `gender` = '$gender', `dob` = '$dob', 
					`marital_status` = '$marital', `employment` = '$employment', 
					`employer` = '$employer', `extra_note` = '$notes', `comm_id` = '$comm' 
					WHERE id = ".$_POST['edit_id'];

				mysqli_query($conn,$update_emp);
			}
			$check_edit_id = TRUE;
			header("Location: display.php");
		}

		if($check_edit_id == FALSE)
		{

			$employee_data_array = array(
				"first_name" => "$f_name", 
				"middle_name" => "$m_name", 
				"last_name" => "$l_name", 
				"prefix" => "$prefix", 
				"gender" => "$gender", 
				"dob" => "$dob", 
				"marital_status" => "$marital", 
				"employment" => "$employment", 
				"employer" => "$employer", 
				"photo" => "$file_name", 
				"extra_note" => "$notes", 
				"comm_id" => "$comm" );

			$connection = $obj->insert_full('employee', $employee_data_array);

			$employee_id = mysqli_insert_id($connection);

			$address_data_array = array(
				"emp_id" => "$employee_id", 
				"r_street" => "$r_street", 
				"r_city" => "$r_city", 
				"r_state" => "$r_state", 
				"r_zip" => "$r_zip", 
				"r_phone" => "$r_phone", 
				"r_fax" => "$r_fax", 
				"o_street" => "$o_street", 
				"o_city" => "$o_city", 
				"o_state" => "$o_state", 
				"o_zip" => "$o_zip", 
				"o_phone" => "$o_phone", 
				"o_fax" => "$o_fax", 
				);

			$connection = $obj->insert_full('address', $address_data_array);



			/*$q_employee = "INSERT INTO employee(first_name, middle_name, last_name, prefix, gender, 
				dob, marital_status, employment, employer, photo, extra_note, comm_id) 
				VALUES ('$f_name', '$m_name', '$l_name', '$prefix', '$gender', '$dob', '$marital', 
				'$employment', '$employer', '$file_name', '$notes', '$comm')";

			$result_1 = mysqli_query($conn, $q_employee);

			if ($result_1) 
			{
				$employee_id = mysqli_insert_id($conn);

				$q_address = "INSERT INTO `address`(`emp_id`, `address_type`, `street`, `city`, 
					`state`, `zip`, `phone`, `fax`) 
					VALUES
					($employee_id,'residence','$r_street','$r_city','$r_state','$r_zip','$r_phone',
					'$r_fax'),
					($employee_id,'office','$o_street','$o_city','$o_state','$o_zip','$o_phone',
					'$o_fax')";

				$result_2 = mysqli_query($conn, $q_address);
			}
			else
			{
				echo 'Unable to insert data';
				exit;
			}*/
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