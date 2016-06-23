<?php
require_once('config/photo_path.php');
require_once('config/error_messages.php');

class DatabaseConnection
{

	// Member variables
	private $host_name = NULL;
	private $user_name = NULL;
	private $password = NULL;
	private $db_name = NULL;
	public static $conn = NULL;
	public static $connection_obj = NULL;

	/**
	* Constructor to create a database connection
	*
	* @access public
	* @param  void
	* @return void
	*/
	private function __construct()
	{
		$this->host_name = 'localhost';
		$this->user_name = 'root';
		$this->password = 'mindfire';
		$this->db_name = 'registration';
		self::$conn = mysqli_connect($this->host_name, $this->user_name, $this->password, 
			$this->db_name);
		// if (mysqli_connect_errno($this->conn))
		// {
		// 	die ('Failed to connect to MySQL :' . mysqli_connect_error());
		// }
	}

	/**
	* To check of exixting connection object
	*
	* @access public
	* @param  void
	* @return object
	*/
	public static function create_connection()
	{
		if(self::$connection_obj == NULL)
		{
			self::$connection_obj = new DatabaseConnection();
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
			$q_employee = "INSERT INTO $table_name (first_name, middle_name, last_name, prefix, 
				gender, dob, marital_status, employment, employer, photo, extra_note, comm_id) 
				VALUES ('{$data_array['first_name']}', '{$data_array['middle_name']}', 
				'{$data_array['last_name']}', '{$data_array['prefix']}', '{$data_array['gender']}', 
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
			off.phone, ', ', off.fax) AS off_add, emp.comm_id AS comm_id, emp.id, emp.photo AS photo
			from employee AS emp 
			inner join address AS res on (emp.id = res.emp_id and res.address_type = 'residence')
			inner join address AS off on (emp.id = off.emp_id and off.address_type = 'office')";

		$result_list = DatabaseConnection::db_query($q_list);
		return $result_list;
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
		/*$str = "";
		foreach($residence_where_condition as $key => $val)
		{
			$str .=  ($str != "")?" AND ":"" . "$key" . '=' . "$val" ;
		}
		if($str != "")
		{
			$str = "WHERE " . $str;
		}*/
 
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


class Validation
{
	public static $form_data = array();
	public $count;
	public static $err;
	public function __construct($array)
	{
		// print_r($_POST);exit;
		global $error_msg;
		static::$err = $error_msg;
		$this->count = 0;
		static::$form_data = $array;
	}

	public function validate_form()
	{
		$this->count += Validation::pure_string('first_name');
		$this->count += Validation::pure_string('middle_name');
		$this->count += Validation::pure_string('last_name');
		$this->count += Validation::radios('prefix');
		$this->count += Validation::radios('gender');
		$this->count += Validation::fields_with_empty('dob');
		$this->count += Validation::radios('marital');
		$this->count += Validation::radios('employment');
		$this->count += Validation::employer('employer');
		$this->count += Validation::alpha_numeric('r_street');
		$this->count += Validation::pure_string('r_city');
		$this->count += Validation::fields_with_empty('r_state');
		$this->count += Validation::zip_code('r_zip');
		$this->count += Validation::phone_fax('r_phone');
		$this->count += Validation::phone_fax('r_fax');
		$this->count += Validation::alpha_numeric('o_street');
		$this->count += Validation::pure_string('o_city');
		$this->count += Validation::fields_with_empty('o_state');
		$this->count += Validation::zip_code('o_zip');
		$this->count += Validation::phone_fax('o_phone');
		$this->count += Validation::phone_fax('o_fax');
	}

	public static function pure_string($name)
	{
		
		$error = 0;
		$form_data = static::$form_data;
		// echo static::$form_data[$name];exit;
		if(isset($form_data[$name]) && !empty($form_data[$name]))
		{
			$name_value = Validation::formatted($form_data[$name]);
			if(preg_match("/^[a-zA-Z ]*$/",$name_value))
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = 'Only Alphabets Allowed';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$name]['val'] = '';
			$_SESSION['error_array'][$name]['msg'] = static::$err[$name];			
			$error = 1;
		}
		// print_r($_SESSION);exit;
		return $error;
	}

	public static function radios($field)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$field]))
		{
			$field_value = $form_data[$field];
			$_SESSION['error_array'][$field]['val'] = $field_value;
			$_SESSION['error_array'][$field]['msg'] = '';
		}
		else
		{
			$_SESSION['error_array'][$field]['val'] = '';
			$_SESSION['error_array'][$field]['msg'] = static::$err[$field];
			$error = 1;
		}
		return $error;
	}

	public static function fields_with_empty($field)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$field]) && !empty($form_data[$field]))
		{
			$field_value = $form_data[$field];
			$_SESSION['error_array'][$field]['val'] = $field_value;
			$_SESSION['error_array'][$field]['msg'] = '';
		}
		else
		{
			$_SESSION['error_array'][$field]['val'] = '';
			$_SESSION['error_array'][$field]['msg'] = static::$err[$field];
			$error = 1;
		}
		return $error;
	}

	public static function alpha_numeric($name)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$name]) && !empty($form_data[$name]))
		{
			$name_value = Validation::formatted($form_data[$name]);
			if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $name_value))
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = 'Only Alphabets and Numeric Allowed';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$name]['val'] = '';
			$_SESSION['error_array'][$name]['msg'] = static::$err[$name];
			$error = 1;
		}
		return $error;
	}

	public static function zip_code($code)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$code]) && !empty($form_data[$code]))
		{
			$code_value = Validation::formatted($form_data[$code]);
			$num_length = strlen((string)$code_value);
			if(ctype_digit($code_value) && $num_length == 6)
			{
				$_SESSION['error_array'][$code]['val'] = $code_value;
				$_SESSION['error_array'][$code]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$code]['val'] = $code_value;
				$_SESSION['error_array'][$code]['msg'] = 'Provide a Numeric value of length 6';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$code]['val'] = '';
			$_SESSION['error_array'][$code]['msg'] = static::$err[$code];
			$error = 1;
		}
		return $error;
	}

	public static function phone_fax($number)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$number]) && !empty($form_data[$number]))
		{
			$number_value = Validation::formatted($form_data[$number]);
			$num_length = strlen((string)$number_value);
			if(ctype_digit($number_value) && $num_length >= 7 && $num_length <= 12)
			{
				$_SESSION['error_array'][$number]['val'] = $number_value;
				$_SESSION['error_array'][$number]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$number]['val'] = $number_value;
				$_SESSION['error_array'][$number]['msg'] = 'Provide a Numeric value between 7 to 
				12 digits';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$number]['val'] = '';
			$_SESSION['error_array'][$number]['msg'] = static::$err[$number];
			$error = 1;
		}
		return $error;
	}

	public static function employer($emp)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$emp]))
		{
			$emp_value = $form_data[$notes];
			$_SESSION['error_array'][$emp]['val'] = $emp_value;
			$_SESSION['error_array'][$emp]['msg'] = '';
		}
		else
		{
			$_SESSION['error_array'][$emp]['val'] = ' ';
			$_SESSION['error_array'][$emp]['msg'] = static::$err[$emp];
		}
		return $error;
	}

	public static function formatted($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
}



?>