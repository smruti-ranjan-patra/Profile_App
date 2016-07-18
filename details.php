<?php

	require_once('config/constants.php');
	require_once('header.php');
	session_start();

	// File for ACL implementation
	require_once('acl.php');

	if(!isset($_SESSION['id']))
	{
		header('Location:home_default.php');
	}

	if(!is_allowed($requested_resource, 'view'))
	{
		header("Location: login_home.php");
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Display Page</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/form.css">
	<script   src="https://code.jquery.com/jquery-3.0.0.min.js"   integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0="   crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/display_helper.js"></script>
	<script type="text/javascript">
		var list_size = <?php echo RECORDS_PER_PAGE; ?>;
	</script>
</head>
<body>

	<!-- Navigation bar -->
	<?php

		$signup_ref = 'sign_up.php?id="' . $_SESSION['id'] . '"'; 
		$header_array = [$signup_ref => 'Home', 'details.php' => 'Details', 'sign_out.php' => 'Sign out'];
		display_header($header_array);
	
	?>	
	<form role="form row" id="display_submit">
		<div class="col-xs-12 col-sm-2 col-md-2 col-lg-1 col-lg-offset-2">
			<label for="name">First Name:</label>
		</div>
		<div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
			<input type="text" name="name" id="name" class="form-control">
		</div>
		<div>
		<button class="glyphicon glyphicon-search btn"  type="submit" name="submit" 
			value="submit"></button>
		</div>
	</form>
	<br>
	<h2><u>Employee Details :-</u></h2>
	<div class="table-responsive">
	</div>
	<div class="page_numbers">
	</div>
</body>
</html>