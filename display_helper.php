<?php
	session_start();
	require_once('config/database.php');
	require_once('config/constants.php');
	require_once('class/DatabaseConnection.php');

	if(!isset($_SESSION['id']))
	{
		echo '{"err_msg" : "Please Login", "err_val" : "1"}';
		exit();
	}

	$obj = DatabaseConnection::create_connection($db['master']);

	$page = $_POST['page'];
	$details_result = $obj->select(0, $_POST['name'], $_POST['fields'], $_POST['type'], $page);
	$record_numbers = $obj->num_of_records($_POST['name'], $_POST['fields'], $_POST['type']);

	$sl = 0;
	while ($details_row = DatabaseConnection::db_fetch_array($details_result))
	{ 
		$date_of_birth = date("d-M-Y", strtotime($details_row['dob']));
		$result_list_comm = $obj->select_comm_field($details_row['comm_id']);
		$row1 = DatabaseConnection::db_fetch_array($result_list_comm);

		if($details_row['photo'])
		{
			$pic_name = PIC_PATH . $details_row['photo'];

			if(!file_exists($pic_name))
			{
				$pic_name = "";
			}
		}
		elseif($details_row['gender'] == 'Male')
		{
			$pic_name = "default_images/default_male_pic.jpg";
		}
		elseif($details_row['gender'] == 'Female')
		{
			$pic_name = "default_images/default_female_pic.jpg";
		}
		else
		{
			$pic_name = "default_images/default_others_pic.jpg";
		}

		$result[$sl++] = array(
			"prefix" => $details_row['prefix'],
			"f_name" => $details_row['f_name'],
			"m_name" => $details_row['m_name'],
			"l_name" => $details_row['l_name'],
			"twitter_name" => $details_row['twitter_name'],
			"gender" => $details_row['gender'],
			"dob" => $date_of_birth,
			"marital_status" => $details_row['marital_status'],
			"employment" => $details_row['employment'],
			"employer" => $details_row['employer'],
			"r_street" => $details_row['r_street'],
			"r_city" => $details_row['r_city'],
			"r_state" => $details_row['r_state'],
			"r_zip" => $details_row['r_zip'],
			"r_phone" => $details_row['r_phone'],
			"r_fax" => $details_row['r_fax'],
			"o_street" => $details_row['o_street'],
			"o_city" => $details_row['o_city'],
			"o_state" => $details_row['o_state'],
			"o_zip" => $details_row['o_zip'],
			"o_phone" => $details_row['o_phone'],
			"o_fax" => $details_row['o_fax'],
			"comm" => $row1['comm'],
			"pic" => $pic_name,
			"session_id" => $_SESSION['id'],
			"emp_id" => $details_row['id']
			);
	}

	$employee = array("num_of_records" => $record_numbers, "details" => $result, "page" => $page, "permission_info" => $_SESSION['permission_info']);
	echo json_encode($employee, true);

?>
