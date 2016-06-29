<?php

require_once('config/error_messages.php');
require_once('config/constants.php');

/**
* Super Class
*
* @author Smruti Ranjan
*/
class DatabaseConnection
{
	public static $conn = NULL;
	public static $connection_obj = NULL;

	/**
	* Constructor to create a database connection
	*
	* @access public
	* @param  void
	* @return void
	*/
	private function __construct($db_param)
	{
		self::$conn = mysqli_connect($db_param['hostname'], $db_param['username'], 
			$db_param['password'], $db_param['database']);
	}

	/**
	* To check of exixting connection object
	*
	* @access public
	* @param  void
	* @return object
	*/
	public static function create_connection($db_param)
	{
		if(self::$connection_obj == NULL)
		{
			self::$connection_obj = new DatabaseConnection($db_param);
		}
		return self::$connection_obj;
	}

	/**
	* To execute SQL queries
	*
	* @access public
	* @param  string $query
	* @return object
	*/
	public static function db_query($query)
	{
		$result = mysqli_query(self::$conn, $query);
		return $result;
	}

	/**
	* To fetch executed select query
	*
	* @access public
	* @param  object $passed_object
	* @return object
	*/
	public static function db_fetch_array($passed_object)
	{
		$result = mysqli_fetch_array($passed_object, MYSQLI_ASSOC);
		return $result;
	}

	/**
	* To fetch number of rows in a db object
	*
	* @access public
	* @param  object $object
	* @return integer
	*/
	public static function db_num_rows($object)
	{
		$rows = mysqli_num_rows($object);
		return $rows;
	}

	/**
	* To select all data from the database
	*
	* @access public
	* @param  integer $id
	* @return object
	*/
	public function select($id)
	{
		$q_fetch = "SELECT emp.first_name AS f_name, emp.middle_name AS m_name, 
			emp.last_name AS l_name, emp.email AS email, emp.prefix AS prefix, emp.gender AS gender, 
			emp.dob AS dob, emp.marital_status AS marital_status, emp.employment AS employment, 
			emp.employer AS employer, res.street AS r_street, res.city AS r_city, 
			res.state AS r_state, res.zip AS r_zip, res.phone AS r_phone, 
			res.fax AS r_fax, off.street AS o_street, off.city AS o_city, off.state AS o_state, 
			off.zip AS o_zip, off.phone AS o_phone, off.fax AS o_fax, emp.photo AS photo, 
			emp.extra_note AS notes, emp.comm_id AS comm_id 
			from employee AS emp 
			INNER JOIN address AS res ON (emp.id = res.emp_id AND res.address_type = 'residence')
			INNER JOIN address AS off ON (emp.id = off.emp_id AND off.address_type = 'office')
			where emp.id = $id";

		$result_select = DatabaseConnection::db_query($q_fetch);
		$row = DatabaseConnection::db_fetch_array($result_select);
		return $row;
	}

	/**
	* To select the desired eamil from the database
	*
	* @access public
	* @param  integer $id
	* @return object
	*/
	public function select_email($email, $eid = 0)
	{
		
		$q_fetch = "SELECT * from employee where email = '{$email}'";
		if($eid > 0) {
			$q_fetch .= "id != {$eid}";
		}
		$result = DatabaseConnection::db_query($q_fetch);
		return $result;
	}

	/**
	* To select the desired eamil from the database
	*
	* @access public
	* @param  integer $id
	* @return object
	*/
	public function select_login($email, $password)
	{
		$q_fetch = "SELECT * from employee where email = '{$email}' AND password = '{$password}'";
		$result = DatabaseConnection::db_query($q_fetch);
		return $result;
	}

	/**
	* To insert data into the database
	*
	* @access public
	* @param  string $table_name, array $data_array
	* @return object
	*/
	public function insert_full($table_name, $data_array)
	{
		if($table_name == 'employee')
		{
			$q_employee = "INSERT INTO $table_name (first_name, middle_name, last_name, email, 
				password, prefix, gender, dob, marital_status, employment, employer, photo, 
				extra_note, comm_id) 
				VALUES ('{$data_array['first_name']}', '{$data_array['middle_name']}', 
				'{$data_array['last_name']}', '{$data_array['email']}', '{$data_array['password']}', 
				'{$data_array['prefix']}', '{$data_array['gender']}', 
				'{$data_array['dob']}', '{$data_array['marital_status']}', 
				'{$data_array['employment']}', '{$data_array['employer']}', 
				'{$data_array['photo']}', '{$data_array['extra_note']}', 
				'{$data_array['comm_id']}')";

			$result_employee = DatabaseConnection::db_query($q_employee);

			if(!$result_employee)
			{
				echo "Data insertion into employee table unsuccessful";exit;
			}
			return self::$conn;
		}

		elseif($table_name == 'address')
		{
			$q_address = "INSERT INTO $table_name (`emp_id`, `address_type`, `street`, `city`, 
				`state`, `zip`, `phone`, `fax`) 
				VALUES
				({$data_array['emp_id']}, 'residence', '{$data_array['r_street']}', 
				'{$data_array['r_city']}', '{$data_array['r_state']}', '{$data_array['r_zip']}', 
				'{$data_array['r_phone']}', '{$data_array['r_fax']}'),

				({$data_array['emp_id']}, 'office', '{$data_array['o_street']}', 
				'{$data_array['o_city']}', '{$data_array['o_state']}', '{$data_array['o_zip']}', 
				'{$data_array['o_phone']}', '{$data_array['o_fax']}')";

			$result_address = DatabaseConnection::db_query($q_address);

			if(!$result_address)
			{
				echo "Data insertion into address table unsuccessful";
				exit;
			}
			return self::$conn;
		}		
	}

	/**
	* To delete data from the database
	*
	* @access public
	* @param  string $table_name, integer $id
	* @return void
	*/
	public function delete_from_table($table_name, $id)
	{
		if($table_name == 'address')
		{
			$del_add = "DELETE FROM $table_name WHERE emp_id = " . $id;
			DatabaseConnection::db_query($del_add);
		}		
		elseif($table_name == 'employee')
		{
			$del_emp = "DELETE FROM $table_name WHERE id = " . $id;
			DatabaseConnection::db_query($del_emp);
		}
	}

	/**
	* To delete the employee profile picture
	*
	* @access public
	* @param  integer $id
	* @return void
	*/
	public function delete_pic($id)
	{
		$sel_pic = "SELECT photo FROM employee WHERE id = " . $id;
		$pic_object = DatabaseConnection::db_query($sel_pic);
		$row = DatabaseConnection::db_fetch_array($pic_object);
		$pic_location = PIC_PATH . $row['photo'];
		unlink($pic_location);
	}

	/**
	* To display the employee list
	*
	* @access public
	* @param  void
	* @return object
	*/
	public function display_list()
	{
		$q_list = "SELECT emp.prefix AS prefix, 
			CONCAT(emp.first_name,' ', emp.middle_name,' ', emp.last_name) AS name, 
			emp.gender AS gender, emp.dob AS dob, 
			emp.marital_status AS marital_status, emp.employment AS employment, 
			emp.employer AS employer, 
			CONCAT(res.street, ', ', res.city, ', ', res.state, ', ', res.zip, ', ', 
			res.phone, ', ', res.fax) AS res_add, 
			CONCAT(off.street, ', ', off.city, ', ', off.state, ', ', off.zip, ', ', 
			off.phone, ', ', off.fax) AS off_add, emp.comm_id AS comm_id, emp.id AS id, 
			emp.photo AS photo from employee AS emp 
			inner join address AS res on (emp.id = res.emp_id and res.address_type = 'residence')
			inner join address AS off on (emp.id = off.emp_id and off.address_type = 'office')";

		$result_list = DatabaseConnection::db_query($q_list);
		return $result_list;
	}

	public function select_comm_field($value)
	{
		$q_comm = "SELECT GROUP_CONCAT(type SEPARATOR ', ') FROM `communication_medium` 
		WHERE id IN ($value)";
		$result = DatabaseConnection::db_query($q_comm);
		return $result;
	}

	/**
	* To display the employee list
	*
	* @access public
	* @param  string $table_name, array $data_array
	* @return void
	*/
	public function update_table($table_name, $data_array)
	{ 
		if($table_name == 'address')
		{
			$update_add_res = "UPDATE $table_name 
				SET `street` = '{$data_array['r_street']}', `city` = '{$data_array['r_city']}', 
				`state` = '{$data_array['r_state']}', `zip` = {$data_array['r_zip']}, 
				`phone` = {$data_array['r_phone']}, `fax` = {$data_array['r_fax']} 
				WHERE (address_type = 'residence' AND emp_id = {$data_array['emp_id']})";

			DatabaseConnection::db_query($update_add_res);

			$update_add_off = "UPDATE $table_name 
				SET `street` = '{$data_array['o_street']}', `city` = '{$data_array['o_city']}', 
				`state` = '{$data_array['o_state']}', `zip` = {$data_array['o_zip']}, 
				`phone` = {$data_array['o_phone']}, `fax` = {$data_array['o_fax']} 
				WHERE (address_type = 'office' AND emp_id = {$data_array['emp_id']})";

			DatabaseConnection::db_query($update_add_off);
		}
		elseif($table_name == 'employee')
		{
			if(isset($data_array['photo']))
			{
				$update_emp = "UPDATE $table_name 
					SET `first_name` = '{$data_array['first_name']}', 
					`middle_name` = '{$data_array['middle_name']}', 
					`last_name` = '{$data_array['last_name']}', 
					`email` = '{$data_array['email']}', 
					`password` = '{$data_array['password']}', 
					`prefix` = '{$data_array['prefix']}', 
					`gender` = '{$data_array['gender']}', 
					`dob` = '{$data_array['dob']}', 
					`marital_status` = '{$data_array['marital_status']}', 
					`employment` = '{$data_array['employment']}', 
					`employer` = '{$data_array['employer']}', 
					`photo` = '{$data_array['photo']}', 
					`extra_note` = '{$data_array['extra_note']}', 
					`comm_id` = '{$data_array['comm_id']}' 
					WHERE id = {$data_array['emp_id']}";
			}
			else
			{
				$update_emp = "UPDATE $table_name 
					SET `first_name` = '{$data_array['first_name']}', 
					`middle_name` = '{$data_array['middle_name']}', 
					`last_name` = '{$data_array['last_name']}', 
					`email` = '{$data_array['email']}', 
					`password` = '{$data_array['password']}', 
					`prefix` = '{$data_array['prefix']}', 
					`gender` = '{$data_array['gender']}', 
					`dob` = '{$data_array['dob']}', 
					`marital_status` = '{$data_array['marital_status']}', 
					`employment` = '{$data_array['employment']}', 
					`employer` = '{$data_array['employer']}', 
					`extra_note` = '{$data_array['extra_note']}', 
					`comm_id` = '{$data_array['comm_id']}' 
					WHERE id = {$data_array['emp_id']}";
			}
			DatabaseConnection::db_query($update_emp);
		}
	}
}

?>
