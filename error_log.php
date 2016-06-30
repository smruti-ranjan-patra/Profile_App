<?php

function error_log($msg)
{
	$msg .= \n;
	$date = date("d_M_Y");
	$file_name = 'log/log_' . $date . '.txt';
	$file = fopen($file_name, "a+");
	fwrite($file, $msg);
	fclose($file);
}

?>