<?php
require_once('config/photo_path.php');

class Database
{
	public $host;
	public $userName;
	public $password;
	public $dbName;
	public $conn;

	/**
	* Constructor to create a database connection
	*
	* @access public
	* @param  void
	* @return void
	*/
	public function __construct()
	{
		$this->host = 'localhost';
		$this->userName = 'root';
		$this->password = 'mindfire';
		$this->dbName = 'registration';
		$this->conn = mysqli_connect($this->host, $this->userName, $this->password, $this->dbName);

		if (mysqli_connect_errno($this->conn))
		{
			die ('Failed to connect to MySQL :' . mysqli_connect_error());
		}
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
				'{$data_array['dob']}', '{$data_array['marital_status']}', '{$data_array['employment']}', 
				'{$data_array['employer']}', '{$data_array['photo']}', '{$data_array['extra_note']}', 
				'{$data_array['comm_id']}')";

			$result_employee = mysqli_query($this->conn, $q_employee);

			if(!$result_employee)
			{
				echo "Data insertion into employee table unsuccessful";exit;
			}
			return $this->conn;
		}

		elseif($table_name == 'address')
		{
			//$employee_id = mysqli_insert_id($this->conn);

			$q_address = "INSERT INTO $table_name (`emp_id`, `address_type`, `street`, `city`, 
				`state`, `zip`, `phone`, `fax`) 
				VALUES
				({$data_array['emp_id']}, 'residence', '{$data_array['r_street']}', 
				'{$data_array['r_city']}', '{$data_array['r_state']}', '{$data_array['r_zip']}', 
				'{$data_array['r_phone']}', '{$data_array['r_fax']}'),

				({$data_array['emp_id']}, 'office', '{$data_array['o_street']}', 
				'{$data_array['o_city']}', '{$data_array['o_state']}', '{$data_array['o_zip']}', 
				'{$data_array['o_phone']}', '{$data_array['o_fax']}')";

			$result_address = mysqli_query($this->conn, $q_address);

			if(!$result_address)
			{
				echo "Data insertion into address table unsuccessful";exit;
			}
			return $this->conn;
		}		
	}

	public function delete_from_table($table_name, $id)
	{
		if($table_name == 'address')
		{
			$del_add = "DELETE FROM $table_name WHERE emp_id = " . $id;
			mysqli_query($this->conn, $del_add);
		}
		
		elseif($table_name == 'employee')
		{
			$del_emp = "DELETE FROM $table_name WHERE id = " . $id;
			mysqli_query($this->conn, $del_emp);
		}
	}

	public function delete_pic($id)
	{
		$sel_pic = "SELECT photo FROM employee WHERE id = " . $id;
		$pic_object = mysqli_query($conn, $sel_pic);
		$row = mysqli_fetch_array($pic_object, MYSQLI_ASSOC);
		$pic_location = PIC_PATH . $row['photo'];
		unlink($pic_location);
	}

	public function update_
}




?>