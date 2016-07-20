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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script type="text/javascript" src="js/display_helper.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		var list_size = <?php echo RECORDS_PER_PAGE; ?>;
	</script>
</head>
<body>

	<!-- Navigation bar -->
	<?php

		$signup_ref = 'sign_up.php'; 
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

	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Tweets</h4>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>