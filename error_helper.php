<?php

	require_once('config/constants.php');
	/**
	* To maintain error logs
	*
	* @access public
	* @param  string $msg
	* @return void
	*/
	function log_error($msg)
	{
		$error_time = date("h:i:s a");
		$msg .= ' at ' . $error_time . "\n";
		$date = date("d_M_Y");
		$file_name = ERROR_PATH . $date . '.txt';

		if(! file_exists($file_name))
		{
			$file = fopen($file_name, 'w+'); 
		}
		else
		{
			$file = fopen($file_name, "a+");
		}

		fwrite($file, $msg);
		fclose($file);
	}

?>
