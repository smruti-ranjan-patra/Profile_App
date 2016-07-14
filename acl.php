<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('class/DatabaseConnection.php');
require_once('config/database.php');

class Acl
{
	public $obj1;
	public function __construct($db_master)
	{
		$this->obj1 = DatabaseConnection::create_connection($db_master);
	}

	public function get_role_resource_permission($user_id)
	{
		$permission_info = array();

		$query = "SELECT emp.id, ro.role_name, rs.resource_name, pr.permission_name 
			FROM role_resource_permission AS rrp
			JOIN role AS ro ON rrp.fk_role = ro.id
			JOIN resource AS rs ON rrp.fk_resource = rs.id
			JOIN permission AS pr ON rrp.fk_permission = pr.id
			JOIN employee AS emp ON emp.role_id = rrp.fk_role
			WHERE emp.id = $user_id";

		$query_result = DatabaseConnection::db_query($query);

		while($row = DatabaseConnection::db_fetch_array($query_result))
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

/**
* It is method that will check whether the user has permission for specified resource
*
* @param string $resource
* @return boolean
*/
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
    	(isset($permissions_available[$permission_to_check]) && ($permissions_available
    		[$permission_to_check] == TRUE)))
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
$requested_resource = $path_parts['filename'];

if(!is_resource_allowed($requested_resource))
{
	header("Location: login_home.php");
}
?>