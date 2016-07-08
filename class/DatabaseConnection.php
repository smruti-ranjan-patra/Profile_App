<?php

require_once('config/error_messages.php');
require_once('config/constants.php');
require_once('error_helper.php');


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
	* @param  array $db_param
	* @return void
	*/
	private function __construct($db_param)
	{
		self::$conn = mysqli_connect($db_param['hostname'], $db_param['username'], 
			$db_param['password'], $db_param['database']);

		if(mysqli_connect_errno(self::$conn))
		{
			log_error('Failed to create database connection');
			die ('Failed to connect to Database, Please try later :' . mysqli_connect_error());
		}
	}

	/**
	* To check of exixting connection object
	*
	* @access public
	* @param  array $db_param
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
	* To fetch executed query
	*
	* @access public
	* @param  object $result_object
	* @return object
	*/
	public static function db_fetch_array($result_object)
	{
		$result = mysqli_fetch_array($result_object, MYSQLI_ASSOC);
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
	* @param  string $name
	* @param  string $fields
	* @param  string $order_type
	* @return object
	*/
	public function select($id = 0, $name = "", $fields = "", $order_type = "", $page = "")
	{
		$start_limit = ($page - 1) * RECORDS_PER_PAGE;
		$q_fetch = "SELECT emp.id AS id, emp.first_name AS f_name, emp.middle_name AS m_name, 
			emp.last_name AS l_name, emp.email AS email, emp.prefix AS prefix, emp.gender AS gender, 
			emp.dob AS dob, emp.marital_status AS marital_status, emp.employment AS employment, 
			emp.employer AS employer, res.street AS r_street, res.city AS r_city, 
			res.state AS r_state, res.zip AS r_zip, res.phone AS r_phone, 
			res.fax AS r_fax, off.street AS o_street, off.city AS o_city, off.state AS o_state, 
			off.zip AS o_zip, off.phone AS o_phone, off.fax AS o_fax, emp.photo AS photo, 
			emp.extra_note AS notes, emp.comm_id AS comm_id 
			FROM employee AS emp 
			INNER JOIN address AS res ON (emp.id = res.emp_id AND res.address_type = 'residence')
			INNER JOIN address AS off ON (emp.id = off.emp_id AND off.address_type = 'office')";

		if($id)
		{
			$q_fetch .= " WHERE emp.id = $id";
			$result_select = DatabaseConnection::db_query($q_fetch);
			$row = DatabaseConnection::db_fetch_array($result_select);
			return $row;
		}
		elseif($fields)
		{
			$q_fetch .= " WHERE first_name LIKE '%{$name}%' ORDER BY $fields $order_type" ;
		}
		elseif($name)
		{
			$q_fetch .= " WHERE first_name LIKE '%{$name}%'" ;
		}

		$q_fetch .= " LIMIT $start_limit, " . RECORDS_PER_PAGE;
		$result_select = DatabaseConnection::db_query($q_fetch);
		return $result_select;
	}

	/**
	* To get the number of records
	*
	* @access public
	* @param  string $name
	* @param  string $fields
	* @param  string $order_type
	* @return object
	*/
	public function num_of_records($name = "", $fields = "", $order_type = "")
	{
		$q_records = "SELECT COUNT(*) AS num_records FROM employee AS emp 
			INNER JOIN address AS res ON (emp.id = res.emp_id AND res.address_type = 'residence')
			INNER JOIN address AS off ON (emp.id = off.emp_id AND off.address_type = 'office')";

		if($fields)
		{
			$q_records .= " WHERE first_name LIKE '%{$name}%' ORDER BY $fields $order_type" ;
		}
		elseif($name)
		{
			$q_records .= " WHERE first_name LIKE '%{$name}%'" ;
		}		

		//echo $q_records;
		$result = DatabaseConnection::db_query($q_records);
		$records = DatabaseConnection::db_fetch_array($result);
		return $records['num_records'];
	}

	/**
	* To select the desired eamil from the database
	*
	* @access public
	* @param  string $email
	* @param  integer $emp_id
	* @param  string $password
	* @return object
	*/
	public function select_email($email, $emp_id = 0, $password = '')
	{
		
		$q_fetch = "SELECT * FROM employee WHERE email = '{$email}'";

		if($password != '')
		{
			$q_fetch .= "AND password = '{$password}'";
		}
		elseif($emp_id > 0)
		{
			$q_fetch .= "AND id != {$emp_id}";
		}

		$result = DatabaseConnection::db_query($q_fetch);
		return $result;
	}

	/**
	* To insert data into the database
	*
	* @access public
	* @param  string $table_name
	* @param  array $data_arraye
	* @return object
	*/
	public function insert_data($table_name, $data_array)
	{
		if($table_name == 'employee')
		{
			$q_employee = "INSERT INTO $table_name (first_name, middle_name, last_name, email, 
				password, prefix, gender, dob, marital_status, employment, employer, photo, 
				extra_note, comm_id) 
				VALUES ('{$data_array['first_name']}', '{$data_array['middle_name']}', 
				'{$data_array['last_name']}', '{$data_array['email']}', 
				'{$data_array['password']}', '{$data_array['prefix']}', 
				'{$data_array['gender']}', '{$data_array['dob']}', 
				'{$data_array['marital_status']}', '{$data_array['employment']}', 
				'{$data_array['employer']}', '{$data_array['photo']}', 
				'{$data_array['extra_note']}', '{$data_array['comm_id']}')";

			$result_employee = DatabaseConnection::db_query($q_employee);

			if(!$result_employee)
			{
				log_error('Data insertion in employee table');
				echo "Data insertion into employee table unsuccessful";
				exit;
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
				log_error('Data insertion in address table');
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
	* @param  integer $id
	* @return void
	*/
	public function delete_employee($id)
	{
		$del_query = "DELETE FROM `address` WHERE emp_id = " . $id;
		DatabaseConnection::db_query($del_query);

		$del_query = "DELETE FROM `employee` WHERE id = " . $id;
		DatabaseConnection::db_query($del_query);
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
		$pic_name = PIC_PATH . $row['photo'];

		if(file_exists($pic_name))
		{
			unlink($pic_name);
		}
	}

	/**
	* To select communication medium
	*
	* @access public
	* @param  string $table_name
	* @param  string $value
	* @return object
	*/
	public function select_comm_field($value)
	{
		$q_comm = "SELECT GROUP_CONCAT(type SEPARATOR ', ') AS comm FROM `communication_medium` 
			WHERE id IN ($value)";

		$result = DatabaseConnection::db_query($q_comm);
		return $result;
	}

	/**
	* To display the employee list
	*
	* @access public
	* @param  string $table_name
	* @param  array $data_array
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
				`comm_id` = '{$data_array['comm_id']}'";

			if(isset($data_array['photo']))
			{
				$update_emp .= ", `photo` = '{$data_array['photo']}'";
			}

			$update_emp .= "WHERE id = {$data_array['emp_id']}";
			DatabaseConnection::db_query($update_emp);
		}
	}
}

?>
