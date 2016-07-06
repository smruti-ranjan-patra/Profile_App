<?php
session_start();
require_once('config/database.php');
require_once('config/constants.php');
require_once('class/DatabaseConnection.php');
$obj = DatabaseConnection::create_connection($db['master']);

$details_result = $obj->select(0, $_POST['name'], $_POST['fields'], $_POST['type']);



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


	// echo "<tr>";
	// echo "<td>" . $sl++ . "</td>";
	// echo "<td>" . $details_row['prefix'] . "</td>";

	// echo "<td>" . $details_row['f_name'] . " " . $details_row['m_name'] . " " . 
	// $details_row['l_name']. "</td>";

	// echo "<td>" . $details_row['gender'] . "</td>";
	// echo "<td>" . date("d-M-Y", strtotime($details_row['dob'])) . "</td>";
	// echo "<td>" . $details_row['marital_status'] . "</td>";
	// echo "<td>" . $details_row['employment'] . "</td>";
	// echo "<td>" . $details_row['employer'] . "</td>";

	// echo "<td>" . $details_row['r_street'] . ", " . $details_row['r_city'] . ", " . 
	// $details_row['r_state'] . ", " . $details_row['r_zip'] . ", " . 
	// $details_row['r_phone'] . ", " . $details_row['r_fax'] . "</td>";

	// echo "<td>" . $details_row['o_street'] . ", " . $details_row['o_city'] . ", " . 
	// $details_row['o_state'] . ", " . $details_row['o_zip'] . ", " . 
	// $details_row['o_phone'] . ", " . $details_row['o_fax'] . "</td>";

	// $result_list_comm = $obj->select_comm_field($details_row['comm_id']);
	// $row1 = DatabaseConnection::db_fetch_array($result_list_comm);
	// echo "<td>" . $row1['comm'] . "</td>";

	// if($details_row['photo'])
	// {
	// 	$pic_name = PIC_PATH . $details_row['photo'];

	// 	if(file_exists($pic_name))
	// 	{
	// 		echo '<td><img src="' . $pic_name . '" width=100 height=100</td>';
	// 	}
	// 	else
	// 	{
	// 		echo "<td>No image found</td>";
	// 	}
	// }
	// elseif($details_row['gender'] == 'Male')
	// {
	// 	echo '<td><img src="default_images/default_male_pic.jpg" width=100 
	// 	height=100</td>';
	// }
	// elseif($details_row['gender'] == 'Female')
	// {
	// 	echo '<td><img src="default_images/default_female_pic.jpg" width=100 
	// 	height=100</td>';
	// }
	// else
	// {
	// 	echo '<td><img src="default_images/default_others_pic.jpg" width=100 
	// 	height=100</td>';
	// }

	// echo "<td>";

	// if($details_row['id'] == $_SESSION['id'])
	// {
	// 	echo '<a href="sign_up.php?id=' . $details_row['id'] . '">';
	// 	echo '<span class="glyphicon glyphicon-pencil" ></span></a>';
	// }						
	// echo "</td>";
	// echo "<td>";
	
	// if($details_row['id'] == $_SESSION['id'])
	// {
	// 	echo '<a href="delete.php?id=' . $details_row['id'] . '">';
	// 	echo '<span class="glyphicon glyphicon-remove" ></span></a>';
	// }
	// echo "</td>";
	// echo "</tr>";
}
echo json_encode($result, true);
?>