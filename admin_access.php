<?php
	require_once('config/database.php');
	require_once('class/DatabaseConnection.php');

	$id = $_POST['id'];
	$is_checked = $_POST['is_checked'];
	$id_array = explode("-", $id);
	$obj2 = DatabaseConnection::create_connection($db_master);

	if($is_checked)
	{
		$insert_array = array(
			"fk_role" => $id_array[0], 
			"fk_resource" => $id_array[1], 
			"fk_permission" => $id_array[2]
			);

		$obj2->insert_data('role_resource_permission', $insert_array);
	}
	else
	{
		$query = "DELETE FROM `role_resource_permission`
			WHERE (fk_role = $id_array[0] 
			AND fk_resource = $id_array[1]
			AND fk_permission = d_array[2]";

		DatabaseConnection::db_query($query);
	}


?>