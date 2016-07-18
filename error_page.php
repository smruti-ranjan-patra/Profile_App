<?php

	session_unset();
	session_destroy();
	echo '<h1>Sorry for the inconvenience</h1>';
	echo '<h3>Please try again after sometime</h3>';
	echo '<a href="' . "home_default.php" . '">Home</a>';

?>
