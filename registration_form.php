<?php

	session_start();
	//session_destroy();

	$check_pic = 0;
	require_once('config/states.php');
	require_once('config/photo_path.php');
	/**
	* Checks each field of select dropdown and makes the required as selected
	*
	* @param array,string
	* @return void
	*/
	function check_states($st_list, $data)
	{
		foreach($st_list as $value)
		{
			?>
			<option <?php if ($value == $data): ?>selected="selected"<?php endif ?> value ="<?php 
			echo $value ?>" >
			<?php echo $value ?>
			</option>
			<?php
		}
	}
	if(isset($_GET['validation']) && $_GET['validation'] == 1)
	{
		$communcation_array = implode(', ',$_SESSION['error_array']['comm']['val']);
		$com = explode(", ",$communcation_array);
		$length = count($com);
		$check_box1 = $check_box2 = $check_box3 = $check_box4 = FALSE;
		for($i = 0; $i < $length; $i++)
		{
			if($com[$i] == 1)
			{
				$check_box1 = TRUE;
			}
			if($com[$i] == 2)
			{
				$check_box2 = TRUE;
			}
			if($com[$i] == 3)
			{
				$check_box3 = TRUE;
			}
			if($com[$i] == 4)
			{
				$check_box4 = TRUE;
			}
		}
	}

	if (isset($_GET['id']))
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$check_pic = 1;

		require_once('config/db_connection.php');

		$q_fetch = "SELECT emp.first_name AS f_name, emp.middle_name AS m_name, emp.last_name AS 
			l_name, emp.prefix AS prefix, emp.gender AS gender, emp.dob AS dob, emp.marital_status AS 
			marital_status, emp.employment AS employment, emp.employer AS employer, res.street AS 
			r_street, res.city AS r_city, res.state AS r_state, res.zip AS r_zip, res.phone AS 
			r_phone, res.fax AS r_fax, off.street AS o_street, off.city AS o_city, off.state AS 
			o_state, off.zip AS o_zip, off.phone AS o_phone, off.fax AS o_fax, emp.photo AS photo, 
			emp.extra_note AS notes, emp.comm_id AS comm_id 
			from employee AS emp 
			INNER JOIN address AS res ON (emp.id = res.emp_id AND res.address_type = 'residence')
			INNER JOIN address AS off ON (emp.id = off.emp_id AND off.address_type = 'office')
			where emp.id = ".$_GET['id'];

		$result = mysqli_query($conn, $q_fetch);

		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

		$com = explode(", ",$row['comm_id']);
		$length = count($com);
		$check_box1 = $check_box2 = $check_box3 = $check_box4 = FALSE;
		for($i = 0; $i < $length; $i++)
		{
			if(1 == $com[$i])
			{
				$check_box1 = TRUE;
			}
			if(2 == $com[$i])
			{
				$check_box2 = TRUE;
			}
			if(3 == $com[$i])
			{
				$check_box3 = TRUE;
			}
			if(4 == $com[$i])
			{
				$check_box4 = TRUE;
			}
		}
	}
	else
	{
		$_GET['id'] = 0;
		$row['prefix'] = 'Mr';
		$row['gender'] = 'Male';
		$row['marital_status'] = 'Single';
		$row['employment'] = 'Employed';
		$row['photo'] = '';
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Registration Form</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/form.css">
	</head>
	<body class="bg">
	<!-- Navigation bar -->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li class="active"><a href="registration_form.php">Register</a></li>
				<li><a href="display.php">Display details</a></li>
			</ul>
		</div>
	</nav>

	<div style="container">

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<form action="submit.php" method="post" enctype=multipart/form-data>
				<?php
				if($_GET['id'])
					echo "<h1>".$row['prefix'].' '.$row['f_name']."</h1>";
				else
					echo "<h1>Registration Form</h1>";
				?>

				<!-- Hidden Form to get the ID -->
				<input type="text" name="edit_id" hidden value="
				<?php
				if(isset($_GET['id']))
					echo $_GET['id'];
				else
					echo 0;
				?>">

				<fieldset>

				<!-- Names -->
				<div class="well">
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="first_name">First Name:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<input type="text" name="first_name" id="first_name" 
							class="form-control" placeholder="First Name" 
							<?php
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo "value=".$_SESSION['error_array']['first_name']['val'];
							}
							else
							{
								echo "value=".$row['f_name'];
							}
							?> >
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">'.$_SESSION['error_array']
								['first_name']['msg']."</span>";
							}?>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="middle_mail">Middle Name:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<input type="text" name="middle_name" id="middle_name" 
							class="form-control" placeholder="Middle Name" 
							<?php
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo "value=".$_SESSION['error_array']['middle_name']['val'];
							}
							else
							{
								echo "value=".$row['m_name'];
							}
							?> >
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">'.$_SESSION['error_array']
								['middle_name']['msg']."</span>"; 
							}
							?>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="last_name">Last Name:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<input type="text" name="last_name" id="last_name" 
							class="form-control" placeholder="Last Name" 
							<?php
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo "value=".$_SESSION['error_array']['last_name']['val'];
							}
							else
							{
								echo "value=".$row['l_name'];
							}
							?> >
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">'.$_SESSION['error_array']
								['last_name']['msg']."</span>"; 
							}
							?>
						</div>
					</div>

					<!-- Prefix -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label>Prefix:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<div class="radio-inline">
							<label>
								<input type="radio" name="prefix" id="prefix_1" 
								value="Mr" 
								<?php
								if(isset($_GET['validation']) && $_GET['validation'] == 1)
								{
									if( 'Mr' == $_SESSION['error_array']['prefix']['val'])
									{
										echo "checked";
									}
								}
								elseif( 'Mr' == $row['prefix'])
								{
									echo "checked";
								}
								?> >Mr
							</label>
							</div>
							<div class="radio-inline">
							<label>
								<input type="radio" name="prefix" id="prefix_2" 
								value="Ms"
								<?php
								if(isset($_GET['validation']) && $_GET['validation'] == 1)
								{
									if( 'Ms' == $_SESSION['error_array']['prefix']['val'])
									{
										echo "checked";
									}
								}
								elseif( 'Ms' == $row['prefix'])
								{
									echo "checked";
								}
								?> >Ms
							</label>
							</div>
							<div class="radio-inline">
							<label>
								<input type="radio" name="prefix" id="prefix_3" value="Mrs" 
								<?php
								if(isset($_GET['validation']) && $_GET['validation'] == 1)
								{
									if( 'Mrs' == $_SESSION['error_array']['prefix']['val'])
									{
										echo "checked";
									}
								}
								elseif( 'Mrs' == $row['prefix'])
								{
									echo "checked";
								}
								?> >Mrs
							</label>
							</div>
						</div>
					</div>

					<!-- Gender -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label>Gender:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<div class="radio-inline">
							<label>
								<input type="radio" name="gender" id="gender_1" value="Male" 
								<?php
								if(isset($_GET['validation']) && $_GET['validation'] == 1)
								{
									if( 'Male' == $_SESSION['error_array']['gender']['val'])
									{
										echo "checked";
									}
								}
								elseif( 'Male' == $row['gender'])
								{
									echo "checked";
								}
								?> >Male
							</label>	
							</div>
							<div class="radio-inline">
								<label>
								<input type="radio" name="gender" id="gender_2" value="Female" 
								<?php
								if(isset($_GET['validation']) && $_GET['validation'] == 1)
								{
									if( 'Female' == $_SESSION['error_array']['gender']['val'])
									{
										echo "checked";
									}
								}
								elseif( 'Female' == $row['gender'])
								{
									echo "checked";
								}
								?> >Female
								</label>
							</div>
							<div class="radio-inline">
								<label>
								<input type="radio" name="gender" id="gender_3" value="Others" 
								<?php
								if(isset($_GET['validation']) && $_GET['validation'] == 1)
								{
									if( 'Others' == $_SESSION['error_array']['gender']['val'])
									{
										echo "checked";
									}
								}
								elseif( 'Others' == $row['gender'])
								{
									echo "checked";
								}
								?> >Others
								</label>
							</div>
						</div>
					</div>

					<!-- Date of birth -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="dob">DOB:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<input type="date" class="form-control" name="dob" id="dob"
							<?php
							if(isset($_GET['validation']) && $_GET['validation'] == 1)
							{
								echo "value=".$_SESSION['error_array']['dob']['val'];
							}
							else
							{
								echo "value=".$row['dob'];
							}
							?> >
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">'.$_SESSION['error_array']
								['dob']['msg']."</span>"; 
							}
							?>
						</div>
					</div>

					<!-- Marital Status -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							 <label for="marital">Marital Status:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<div class="radio-inline">
								<label>
									<input type="radio" name="marital" id="marital_status_1" 
									value="Single" 
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										if( 'Single' == $_SESSION['error_array']['marital']['val'])
										{
											echo "checked";
										}
									}
									elseif( 'Single' == $row['marital_status'])
									{
										echo "checked";
									}
									?> >Single
								</label>
							</div>
							<div class="radio-inline">
								<label>
									<input type="radio" name="marital" id="marital_status_2" 
									value="Married" 
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										if( 'Married' == $_SESSION['error_array']['marital']['val'])
										{
											echo "checked";
										}
									}
									elseif( 'Married' == $row['marital_status'])
									{
										echo "checked";
									}
									?> >Married
								</label>
							</div>
						</div>
					</div>

					<!-- Employent -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label>Employent:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<div class="radio-inline">
								<label>
								<input type="radio" name="employment" id="employment_status_1" 
								value="Employed" 
								<?php
								if(isset($_GET['validation']) && $_GET['validation'] == 1)
								{
									if( 'Employed' == $_SESSION['error_array']['employment']['val'])
									{
										echo "checked";
									}
								}
								elseif( 'Employed' == $row['employment'])
								{
									echo "checked";
								}
								?> >Employed
								</label>
							</div>
							<div class="radio-inline">
								<label>
								<input type="radio" name="employment" id="employment_status_2" 
								value="Unemployed" 
								<?php
								if(isset($_GET['validation']) && $_GET['validation'] == 1)
								{
									if( 'Unemployed' == $_SESSION['error_array']['employment']['val'])
									{
										echo "checked";
									}
								}
								elseif( 'Unemployed' == $row['employment'])
								{
									echo "checked";
								}
								?> >Unemployed
								</label>
							</div>
						</div>
					</div>

					<!-- Employer -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="employer">Employer:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<input type="text" id="employer" name="employer" class="form-control" 
							placeholder="Organization"
							<?php
							if(isset($_GET['validation']) && $_GET['validation'] == 1)
							{
								echo "value='".$_SESSION['error_array']['employer']['val']."'";
							}
							else
							{
								echo "value=".$row['employer'];
							}
							?> >
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">'.$_SESSION['error_array']
								['employer']['msg']."</span>"; 
							}
							?>
						</div>
					</div>


					<!-- Residence Address :- -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<h4><u>Residence Address :-</u></h4>

							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
									<label for="r_street">Street:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="r_street" name="r_street" 
									class="form-control" placeholder="Street"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value='".$_SESSION['error_array']['r_street']
										['val']."'";
									}
									else
									{
										echo "value=".$row['r_street'];
									}
									?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['r_street']['msg']."</span>"; 
									}
									?>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
									<label for="r_city">City:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="r_city" name="r_city" 
									class="form-control" placeholder="City"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value='".$_SESSION['error_array']['r_city']['val']."'";
									}
									else
									{
										echo "value=".$row['r_city'];
									}
									?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['r_city']['msg']."</span>"; 
									}
									?>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
									<label for="r_state">State:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<select name="r_state" id="r_state" class="form-control">
										<option value="">Select State</option>
										<?php
										if(isset($_GET['validation']) && $_GET['validation'] == 1)
										{
											check_states($state_list, $_SESSION['error_array']
											['r_state']['val']);
										}
										else
										{
											check_states($state_list, $row['r_state']);
										}?>
									</select>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['r_state']['msg']."</span>"; 
									}
									?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
									<label for="r_zip">Zip:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="r_zip" name="r_zip" class="form-control" 
									placeholder="Zip code" 
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=".$_SESSION['error_array']['r_zip']['val'];
									}
									else
									{
										echo "value=".$row['r_zip'];
									}
									?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['r_zip']['msg']."</span>"; 
									}
									?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
									<label for="r_phone">Phone:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="r_phone" name="r_phone" 
									class="form-control" placeholder="09123456789" 
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=".$_SESSION['error_array']['r_phone']['val'];
									}
									else
									{
										echo "value=".$row['r_phone'];
									}
									?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['r_phone']['msg']."</span>";
									}?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
									<label for="r_fax">Fax:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="r_fax" name="r_fax" class="form-control" 
									placeholder="04442544302"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=".$_SESSION['error_array']['r_fax']['val'];
									}
									else
									{
										echo "value=".$row['r_fax'];
									}
									?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['r_fax']['msg']."</span>"; 
									}
									?>
								</div>
							</div>
						</div>


						<!-- Office Address :- -->

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<h4><u>Office Address :-</u></h4>

							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 
								col-md-offset-1 col-lg-offset-1">
									<label for="o_street">Street:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="o_street" name="o_street" 
									class="form-control" placeholder="Street" 
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value='".$_SESSION['error_array']['o_street']
										['val']."'";
									}
									else
									{
										echo "value=".$row['o_street'];
									}?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['o_street']['msg']."</span>";
									}
									?>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 
								col-md-offset-1 col-lg-offset-1">
									<label for="o_city">City:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="o_city" name="o_city" class="form-control" 
									placeholder="City"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value='".$_SESSION['error_array']['o_city']['val']."'";
									}
									else
									{
										echo "value=".$row['o_city'];
									}
									?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['o_city']['msg']."</span>";
									}
									?>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 
								col-md-offset-1 col-lg-offset-1">
									<label for="o_state">State:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<select name="o_state" id="o_state" class="form-control">
										<option value="">Select State</option>
										<?php
										if(isset($_GET['validation']) && $_GET['validation'] == 1)
										{
											check_states($state_list, $_SESSION['error_array']
											['o_state']['val']);
										}
										else
										{
											check_states($state_list, $row['o_state']);
										}
										?>
									</select>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['o_state']['msg']."</span>";
									}?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 
								col-md-offset-1 col-lg-offset-1">
									<label for="o_zip">Zip:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="o_zip" name="o_zip" class="form-control" 
									placeholder="Zip code"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=".$_SESSION['error_array']['o_zip']['val'];
									}
									else
									{
										echo "value=".$row['o_zip'];
									}
									?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['o_zip']['msg']."</span>";
									}
									?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 
								col-md-offset-1 col-lg-offset-1">
									<label for="o_phone">Phone:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="o_phone" name="o_phone" 
									class="form-control" placeholder="09123456789"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=".$_SESSION['error_array']['o_phone']['val'];
									}
									else
									{
										echo "value=".$row['o_phone'];
									}
									?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['o_phone']['msg']."</span>";
									}
									?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 
								col-md-offset-1 col-lg-offset-1">
									<label for="o_fax">Fax:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="o_fax" name="o_fax" class="form-control"
									placeholder="04442544302"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=".$_SESSION['error_array']['o_fax']['val'];
									}
									else
									{
										echo "value=".$row['o_fax'];
									}
									?> >
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">'.$_SESSION['error_array']
										['o_fax']['msg']."</span>";
									}
									?>
								</div>
							</div>
						</div>
					</div>

					<!-- Personal Info :- --> 
					<!-- Photo -->
					<h4><u>Personal Info :-</u></h4>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="pic">Photo:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8"> 
							<input type="file" id="pic" name="pic" value="<?php echo $row['photo'] 
							?>"><span>
							<?php
							if(isset($_GET['validation']) && $_GET['validation'] == 1)
							{
								echo "<img src=".PIC_PATH.$_SESSION['error_array']['photo']
								['val']." width=200 height=200>";
							}
							elseif($check_pic == 1)
							{
							  echo "<img src=".PIC_PATH.$row['photo']." width=200 height=200>";
							}
							echo '<span class="text-danger">'.$_SESSION['error_array']
							['photo']['msg']."</span>";
							?></span>
						</div>
					</div>
					<br>

					<!-- Extra Notes -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"> 
							<label for="notes">Extra Notes:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<?php
							if(isset($_GET['validation']) && $_GET['validation'] == 1)
							{
								$note_value =  $_SESSION['error_array']['notes']['val'];
							}
							else
							{
								$note_value =  $row['notes'];
							}
							?>
							<textarea class="form-control" id="notes" name="notes" rows="10" 
							placeholder="Notes"><?php echo $note_value; ?></textarea>
						</div>
					</div>
					<br>

					<!-- Preferred Communicatio -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label>Preferred communication medium:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<div class="checkbox-inline">
								<input type="checkbox" id="comm_mail" name="comm[]" value="1" 
								<?php
								if($check_box1)
								{
									echo "checked"; 
								}
								?> >
								<label for="comm_mail">Mail</label>
							</div>
							<div class="checkbox-inline">
								<input type="checkbox" id="comm_message" name="comm[]" value="2" 
								<?php
								if($check_box2)
								{
									echo "checked";
								}
								?> >
								<label for="comm_message">Message</label>
							</div>
							<div class="checkbox-inline">
								<input type="checkbox" id="comm_phone" name="comm[]" value="3" 
								<?php
								if($check_box3)
								{
									echo "checked";
								}
								?> >
								<label for="comm_phone">Phone Call</label>
							</div>
							<div class="checkbox-inline">
								<input type="checkbox" id="comm_any" name="comm[]" value="4" 
								<?php
								if($check_box4)
								{
									echo "checked";
								}
								?> >
								<label for="comm_any">Any</label>
							</div>
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">'.$_SESSION['error_array']['comm']
								['msg']."</span>";
							}
							?>							
						</div>
					</div>
				</div>
				</fieldset>

				<!-- Buttons -->
				<div class="row form-group text-center">
					<?php
					if (!empty($_GET['id']))
					{
						echo '<button class="btn btn-primary"  type="submit" name="submit" 
						value="update">Update</button>';
					}
					else
					{
						echo '<button class="btn btn-primary"  type="submit" name="submit" 
						value="submit">Submit</button>';
					}
					?>
					<button class="btn btn-danger" type="reset" name="reset" value="reset">Reset
					</button>
				</div>
			</form>
		</div>
		</div>
	</div>
	</body>
</html>