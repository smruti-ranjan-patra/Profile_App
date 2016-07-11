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
	* @access private
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
		$email = mysqli_real_escape_string(self::$conn, $email);
		$q_fetch = "SELECT * FROM employee WHERE email = '{$email}'";

		if($password != '')
		{
			$password = mysqli_real_escape_string(self::$conn, $password);
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
		$data_array = DatabaseConnection::sanitize($data_array);
		$query = "INSERT INTO " . $table_name . " (";

		foreach($data_array as $key => $value)
		{
			$query .= "$key, ";
		}

		$query = chop($query, ", ");
		$query .= " ) VALUES ( ";

		foreach($data_array as $key => $value)
		{
			$query .= "'" . $value . "', ";
		}

		$query = chop($query, ", ");
		$query .= " ) ";
		$result_employee = DatabaseConnection::db_query($query);

		if(!$result_employee)
		{
			$err = "Error while inserting data into $table_name table";
			log_error($err);
			header('Location: error_page.php');
			exit;
		}
		return self::$conn;
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
		$id = mysqli_real_escape_string(self::$conn, $id);

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
		$id = mysqli_real_escape_string(self::$conn, $id);
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
	public function update_table($table_name, $data_array, $type)
	{
		$data_array = DatabaseConnection::sanitize($data_array);

		if($type)
		{
			$where_cond_arr = ['address_type' => $type, 'emp_id' => $data_array['emp_id']];
			$where_clause = DatabaseConnection::where_generation($where_cond_arr);
		}
		else
		{
			$where_cond_arr = ['id' => $data_array['emp_id']];
			$where_clause = DatabaseConnection::where_generation($where_cond_arr);
		}

		$q_update = "UPDATE $table_name SET ";

		foreach($data_array as $key => $value)
		{
			if($key != 'emp_id')
			{
				$q_update .= $key . " = " . "'" . $value . "'" . ", ";
			}
		}

		$q_update = chop($q_update, ", ");
		$q_update .= ' ' . $where_clause;			
		$result = DatabaseConnection::db_query($q_update);

		if(!$result)
		{
			$err = "Error while updating data into $table_name table";
			log_error($err);
			header('Location: error_page.php');
			exit;
		}
	}

	/**
	* To generate where condition for update query
	*
	* @access public
	* @param  array $arr
	* @return string $where_clause
	*/
	public static function where_generation($arr)
	{
		$count = 0;
		$where_clause = ' WHERE ';

		foreach($arr as $key => $value)
		{
			if($count > 0)
			{
				$where_clause .= ' AND ';
			}

			$where_clause .= $key . " = " . $value . ' ';
			++$count;
		}

		return $where_clause;
	}

	/**
	* To sanitize data before inserting into database
	*
	* @access public
	* @param  array $array
	* @return object
	*/
	public static function sanitize($arr)
	{
		foreach($arr as $key => $value)
		{
			$arr["$key"] = trim($value);
			$arr["$key"] = stripslashes($value);
			$arr["$key"] = htmlentities($value, ENT_QUOTES);
			$arr["$key"] = mysqli_real_escape_string(self::$conn, $value);
		}

		return $arr;
	}
}

?>
