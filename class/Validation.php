<?php

require_once('config/error_messages.php');
require_once('class/DatabaseConnection.php');
require_once('config/constants.php');
require_once('config/database.php');

/**
* Super Class
*
* @author Smruti Ranjan
*/
class Validation
{
	// Data members
	public static $form_data = array();
	public $count;
	public static $err;

	/**
	* Constructor to initialize data members
	*
	* @access public
	* @param  array $array
	* @return void
	*/
	public function __construct($array)
	{
		global $error_msg;
		static::$err = $error_msg;
		$this->count = 0;
		static::$form_data = $array;
	}

	/**
	* To validate data
	*
	* @access public
	* @param  string $param
	* @return integer $error_count
	*/
	public function validate_form($param)
	{
		if($param == 'submit')
		{
			$this->count += Validation::strict_alphabet('first_name');
			$this->count += Validation::strict_alphabet('middle_name');
			$this->count += Validation::strict_alphabet('last_name');
			$this->count += Validation::email('email');
			$this->count += Validation::password('password');
			$this->count += Validation::radios('prefix');
			$this->count += Validation::radios('gender');
			$this->count += Validation::fields_with_empty('dob');
			$this->count += Validation::radios('marital');
			$this->count += Validation::radios('employment');
			$this->count += Validation::employer('employer');
			$this->count += Validation::alpha_numeric('r_street');
			$this->count += Validation::strict_alphabet('r_city');
			$this->count += Validation::fields_with_empty('r_state');
			$this->count += Validation::number('r_zip');
			$this->count += Validation::number('r_phone', 7, 12);
			$this->count += Validation::number('r_fax', 7, 12);
			$this->count += Validation::alpha_numeric('o_street');
			$this->count += Validation::strict_alphabet('o_city');
			$this->count += Validation::fields_with_empty('o_state');
			$this->count += Validation::number('o_zip');
			$this->count += Validation::number('o_phone', 7, 12);
			$this->count += Validation::number('o_fax', 7, 12);
		}
		elseif($param == 'login')
		{
			$error_count = 0;
			$error_count = Validation::login_validation();
			return $error_count;
		}		
	}

	/**
	* Validation for only alphabets
	*
	* @access public
	* @param  string $name
	* @return integer $error
	*/
	public static function strict_alphabet($name)
	{
		
		$error = 0;
		$form_data = static::$form_data;

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
				$_SESSION['error_array'][$name]['msg'] = '*Only Alphabets Allowed';
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

	/**
	* Validation for radio buttons
	*
	* @access public
	* @param  string $field
	* @return integer $error
	*/
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

	/**
	* Validation for fileds which can also be empty
	*
	* @access public
	* @param  string $field
	* @return integer $error
	*/
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

	/**
	* Validation for aplha numeric values
	*
	* @access public
	* @param  string $name
	* @return integer $error
	*/
	public static function alpha_numeric($name)
	{
		$error = 0;
		$form_data = static::$form_data;

		if(isset($form_data[$name]) && !empty($form_data[$name]))
		{
			$name_value = Validation::formatted($form_data[$name]);
			if(preg_match('/^[a-zA-Z0-9 _-]+$/', $name_value))
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = '*Only Alphabets and Numeric Allowed';
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

	/**
	* Validation for all Numeric fields
	*
	* @access public
	* @param  string $field
	* @param  integer $min_length
	* @param  integer $max_length
	* @return integer $error
	*/
	public static function number($field, $min_length = '', $max_length = 6)
	{
		$error = 0;
		$form_data = static::$form_data;

		if ($min_length)
		{
			$regex_str = '/^(\d{' . $min_length . ',' . $max_length . '})$/';
			$error_message = '*Provide a Numeric value between 7 to 12 digits';
		}
		else
		{
			$regex_str = '/^(\d{' . $max_length . '})$/';
			$error_message = '*Provide a Numeric value of length 6';
		}

		if(isset($form_data[$field]) && !empty($form_data[$field]))
		{
			$field_value = Validation::formatted($form_data[$field]);

			if(preg_match($regex_str, $field_value))
			{
				$_SESSION['error_array'][$field]['val'] = $field_value;
				$_SESSION['error_array'][$field]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$field]['val'] = $field_value;
				$_SESSION['error_array'][$field]['msg'] = $error_message;
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$field]['val'] = '';
			$_SESSION['error_array'][$field]['msg'] = static::$err[$field];
			$error = 1;
		}
		return $error;
	}

	/**
	* Validation for the employer field
	*
	* @access public
	* @param  string $emp
	* @return integer $error
	*/
	public static function employer($emp)
	{
		$error = 0;
		$form_data = static::$form_data;

		if(isset($form_data[$emp]))
		{
			$emp_value = Validation::formatted($form_data[$emp]);
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

	/**
	* Validation for email id field
	*
	* @access public
	* @param  string $email
	* @return integer $error
	*/
	public static function email($email)
	{
		global $db;
		$error = 0;
		$form_data = static::$form_data;

		if(isset($form_data[$email]) && !empty($form_data[$email]))
		{
			$rnum = 0;

			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			{
				$obj = DatabaseConnection::create_connection($db['master']);
				$rows = $obj->select_email($form_data[$email], $_SESSION['id']);
				$rnum = DatabaseConnection::db_num_rows($rows);

				if($rnum == 0)
				{
					$_SESSION['error_array'][$email]['val'] = $form_data[$email];
					$_SESSION['error_array'][$email]['msg'] = '';
				}
				else
				{	
					$_SESSION['error_array'][$email]['val'] = $form_data[$email];
					$_SESSION['error_array'][$email]['msg'] = '*Email ID already taken';
					$error = 1;
				}
			}
			else
			{
				$_SESSION['error_array'][$email]['val'] = $form_data[$email];
				$_SESSION['error_array'][$email]['msg'] = '*Provide a valid Email ID';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$email]['val'] = "";
			$_SESSION['error_array'][$email]['msg'] = '*Email ID mandatory';
			$error = 1;
		}
		return $error;
	}

	/**
	* Validation for password field
	*
	* @access public
	* @param  string $password
	* @return integer $error
	*/
	public static function password($password)
	{
		$error = 0;
		$form_data = static::$form_data;

		if(isset($form_data[$password]) && !empty($form_data[$password]))
		{
			$length = strlen((string)$form_data[$password]);

			if($length >= 8 && $length <= 12)
			{
				$_SESSION['error_array'][$password]['val'] = $form_data[$password];
				$_SESSION['error_array'][$password]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$password]['val'] = $form_data[$password];;
				$_SESSION['error_array'][$password]['msg'] = '*Password must be of 8 to 12 
				characters';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$password]['val'] = "";
			$_SESSION['error_array'][$password]['msg'] = '*Forgot to provide a Password';
			$error = 1;
		}
		return $error;
	}

	/**
	* Validation for notes field
	*
	* @access public
	* @param  string $notes
	* @return integer $error
	*/
	public function notes_validation($notes)
	{
		if(isset($notes))
		{
			$notes = Validation::formatted($notes);
			$_SESSION['error_array']['notes']['val'] = $notes;
			$_SESSION['error_array']['notes']['msg'] = '';
		}
		else
		{
			$notes = ' ';
			$_SESSION['error_array']['notes']['val'] = $notes;
			$_SESSION['error_array']['notes']['msg'] = '';
		}
		return $notes;
	}

	/**
	* Validation for communication field
	*
	* @access public
	* @param  array $comm_array
	* @return string $comm
	*/
	public function comm_validation($comm_array)
	{
		if(isset($comm_array) && !empty($comm_array))
		{
			$comm = implode(', ', $comm_array);
			$_SESSION['error_array']['comm']['val'] = $comm_array;
			$_SESSION['error_array']['comm']['msg'] = '';
			return $comm;
		}
		else
		{
			$_SESSION['error_array']['comm']['val'] = '';
			$_SESSION['error_array']['comm']['msg'] = '*Select at least one medium';
			$this->count++;
			return 0;
		}
	}

	/**
	* Validation of uploaded photo
	*
	* @access public
	* @param  void
	* @return array $pic_return_data
	*/
	public function photo_validation()
	{
		$pic_return_data = array("pic_update" => FALSE, "name" => "");

		if(isset($_FILES['pic']))
		{
			$errors = array();
			$pic_return_data["name"] = $file_name = $_FILES['pic']['name'];
			$file_size = $_FILES['pic']['size'];

			if($file_size > 0) 
			{
				$file_tmp = $_FILES['pic']['tmp_name'];
				$ext_arr = explode('.',$file_name);
				$file_ext = strtolower(end($ext_arr));
				$extensions = array("jpeg","jpg","png");

				if(in_array($file_ext, $extensions) === false)
				{
					$errors[] = "extension not allowed, please choose a JPEG or PNG file.";
					$_SESSION['error_array']['photo']['val'] = $file_name;
					$_SESSION['error_array']['photo']['msg'] = '*Please choose jpg, jpeg or png 
					file';
					$this->count++;
				}

				if($file_size > 8388608)
				{
					$errors[] = '*File size must be less than 8 MB';
				}

				if(empty($errors) == true)
				{
					move_uploaded_file($file_tmp, PIC_PATH.$file_name);
					$pic_return_data["pic_update"] = TRUE;
				}
			}
			
			$_SESSION['error_array']['photo']['val'] = $file_name;
			$_SESSION['error_array']['photo']['msg'] = '*Please Provide a Photo';
		}
		else
		{
			$_SESSION['error_array']['photo']['val'] = ' ';
			$_SESSION['error_array']['photo']['msg'] = '';
		}
		return $pic_return_data;
	}

	/**
	* Validation of login process
	*
	* @access public
	* @param  void
	* @return array $pic_return_data
	*/
	public static function login_validation()
	{
		global $db;
		$error = 0;
		$rnum = 0;

		if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && 
			!empty($_POST['password']))
		{
			$obj = DatabaseConnection::create_connection($db['master']);
			$rows = $obj->select_email($_POST['email'], 0, $_POST['password']);
			$rnum = DatabaseConnection::db_num_rows($rows);
			$fetch_data = $obj->db_fetch_array($rows);
			
			if($rnum != 0)
			{
				$_SESSION['error_array']['login']['msg'] = '';
				$_SESSION['id'] = $fetch_data['id'];
			}
			else
			{
				$_SESSION['error_array']['login']['msg'] = '*Invalid username or password';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array']['login']['msg'] = '*Please give login credentials';
			$error = 1;
		}
		return $error;
	}

	/**
	* To remove extra spaces, slashes and to convert into special characters
	*
	* @access public
	* @param  string $data
	* @return string $data
	*/
	public static function formatted($data)
	{
		$data = trim($data);
		$data = htmlentities($data, ENT_QUOTES);
		return $data;
	}
}
?>