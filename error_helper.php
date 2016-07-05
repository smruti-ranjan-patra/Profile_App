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
	$msg .= "\n";
	$date = date("d_M_Y");
	$file_name = ERROR_PATH . $date . '.txt';
	$file = fopen($file_name, "a+");
	fwrite($file, $msg);
	fclose($file);
}
?>