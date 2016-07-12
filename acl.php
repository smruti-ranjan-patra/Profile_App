<?php
require_once('class/DatabaseConnection.php');
require_once('config/database.php');

class Acl
{
	public $conn;
	public function __construct($db_master)
	{
		$obj1 = DatabaseConnection::create_connection($db_master);
		$this->conn = $obj1::$conn;
	}

	public function get_role_resource_permission($user_id)
	{
		$permission_info = array();

		$query = "SELECT  fk_employee, ro.role_name, rs.resource_name, pr.permission_name 
			FROM role_resource_permission AS rrp
			JOIN role AS ro ON rrp.fk_role = ro.id
			JOIN resource AS rs ON rrp.fk_resource = rs.id
			JOIN permission AS pr ON rrp.fk_permission = pr.id
			WHERE fk_employee = $user_id";

		$result = mysqli_query($this->conn, $query);

		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$permission_info['resources'][$row["resource_name"]] = TRUE;
			$permission_info['role'] = $row["role_name"];
			$permission_info['permissions'][$row["resource_name"]."-".$row["permission_name"]] = TRUE;
		}

		return $permission_info;
	}

}

$acl = new Acl($db['master']);
$user_id = $_SESSION['id'];
$_SESSION['permission_info'] = $acl->get_role_resource_permission($user_id);

function is_resource_allowed($resource)
{
	$permissions_available = $_SESSION['permission_info']['resources'];
	
	//Checking if resource access available
	if((isset($permissions_available[$resource]) && ($permissions_available[$resource] == TRUE)))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}	
}

/**
* It is method that will check whether the user has permission
*
* @param string $resource
* @param string $permission
* @return boolean
*/
function is_allowed($resource, $permission = '')
{
	$permissions_available = $_SESSION['permission_info']['permissions'];
	$permission_to_check = $resource . "-" . $permission;

	//Checking if all access permission or current action access permission
    if((isset($permissions_available[$resource . "-" ."all"]) && 
    	($permissions_available[$resource . "-" ."all"] == TRUE)) || 
    	(isset($permissions_available[$permission_to_check]) && ($permissions_available[$permission_to_check] == TRUE)))
    {
    	return TRUE;
    }
    else
    {
    	return FALSE;
    }
}

//Checking access for current page
$path_parts = pathinfo($_SERVER['REQUEST_URI']);
$requestedResource = $path_parts['filename'];

$requestedAction = isset($_GET['action']) ? $_GET['action'] : '';

if(!is_resource_allowed($requestedResource))
{
	header("Location: sign_up.php");
}
?>