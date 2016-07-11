<?php
	session_start();
	$check_pic = 0;
	require_once('config/states.php');
	require_once('config/database.php');
	require_once('class/DatabaseConnection.php');
	require_once('class/Validation.php');
	require_once('config/constants.php');

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
			<option <?php if($value == $data): ?>selected="selected"<?php endif ?> value ="<?php 
			echo $value ?>" >
			<?php echo $value ?>
			</option>
			<?php
		}
	}
	$check_box1 = $check_box2 = $check_box3 = $check_box4 = FALSE;

	if(isset($_GET['id']) && !isset($_SESSION['id']))
	{
		header("Location:sign_up.php");
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

	if(isset($_GET['id']) || isset($_SESSION['id']))
	{
		$check_pic = 1;
		$obj = DatabaseConnection::create_connection($db['master']);

		if(isset($_SESSION['id']))
		{
			$row = $obj->select($_SESSION['id']);
		}
		else
		{
			$row = $obj->select($_GET['id']);
		}

		if(isset($row['comm_id']))
		{
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
		<?php

		if(isset($_SESSION['id']))
		{
			echo "<title>Logged in</title>";
		}
		else
		{
			echo "<title>Sign Up</title>";
		}

		?>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/form.css">
		<script   src="https://code.jquery.com/jquery-3.0.0.min.js"   integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0="   crossorigin="anonymous"></script>
		<script type="text/javascript" src="js/jquery_validation.js"></script>
	</head>
	<body class="bg">

	<!-- Navigation bar -->
	<?php

	if(isset($_SESSION['id']))
	{
		?>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<li class="active"><a href="sign_up.php">Home</a></li>
					<li><a href="details.php">Details</a></li>
					<li><a href="sign_out.php">Sign out</a></li>
				</ul>
			</div>
		</nav>
	<?php
	}
	else
	{
		?>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<li><a href="home_default.php">Home</a></li>
					<li class="active"><a href="sign_up.php">Sign Up</a></li>
					<li><a href="login_form.php">Login</a></li>
				</ul>
			</div>
		</nav>
	<?php
	}?>

	<div class="container">

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<form action="submit.php" method="post" id="sign_up_form" enctype=multipart/form-data>
				<?php
				if($_GET['id'])
				{
					echo "<h1>".$row['prefix'].' '.$row['f_name']."</h1>";
				}
				else
				{
					echo "<h1>Registration Form</h1>";
				}
				?>

				<!-- Hidden Form to get the ID -->
				<input type="text" name="edit_id" hidden value="
				<?php
				if(isset($_GET['id']))
				{
					echo $_GET['id'];
				}
				elseif(isset($_SESSION['id']))
				{
					echo $_SESSION['id'];
				}
				else
				{
					echo 0;
				}
				?>">

				<fieldset>
				<div class="well">

					<!-- Names -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="first_name">First Name:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<input type="text" name="first_name" id="first_name" 
							class="form-control text_field" placeholder="First Name" 
							<?php
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo "value=" . $_SESSION['error_array']['first_name']['val'];
							}
							else
							{
								if(isset($row['f_name']))
								{
									echo "value=" . $row['f_name'];
								}								
							}
							?> >
							<span class="text-danger err_msg" id="err_first_name"></span>
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">' . $_SESSION['error_array']
								['first_name']['msg'] . "</span>";
							}?>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="middle_mail">Middle Name:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<input type="text" name="middle_name" id="middle_name" 
							class="form-control text_field" placeholder="Middle Name" 
							<?php
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo "value=" . $_SESSION['error_array']['middle_name']['val'];
							}
							else
							{
								if(isset($row['m_name']))
								{
									echo "value=" . $row['m_name'];
								}
							}
							?> >
							<span class="text-danger err_msg" id="err_middle_name"></span>
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">' . $_SESSION['error_array']
								['middle_name']['msg'] . "</span>"; 
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
							class="form-control text_field" placeholder="Last Name" 
							<?php
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo "value=" . $_SESSION['error_array']['last_name']['val'];
							}
							else
							{
								if(isset($row['l_name']))
								{
									echo "value=" . $row['l_name'];
								}
							}
							?> >
							<span class="text-danger err_msg" id="err_last_name"></span>
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">' . $_SESSION['error_array']
								['last_name']['msg'] . "</span>"; 
							}
							?>
						</div>
					</div>

					<!-- Email ID -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="email">Email ID:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<input type="text" name="email" id="email" 
							class="form-control email" placeholder="Email ID"
							<?php
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo "value=" . $_SESSION['error_array']['email']['val'];
							}
							else
							{
								if(isset($row['email']))
								{
									echo "value=" . $row['email'];
								}
							}
							?> >
							<span class="text-danger err_msg" id="err_email"></span>
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">' . $_SESSION['error_array']
								['email']['msg'] . "</span>"; 
							}
							?>
						</div>
					</div>

					<!-- Password -->
					<div class="row form-group">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="password">Password:</label>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							<input type="password" name="password" id="password" 
							class="form-control password" placeholder="**********" 
							<?php
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo "value=" . $_SESSION['error_array']['password']['val'];
							}
							else
							{
								if(isset($row['password']))
								{
									echo "value=" . $row['password'];
								}
							}
							?> >
							<span class="text-danger err_msg" id="err_password"></span>
							<?php
							if(isset($_SESSION['error_array']['password']['msg']))
							{
								echo '<span class="text-danger">' . $_SESSION['error_array']
								['password']['msg'] . "</span>"; 
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
						<span class="text-danger err_msg"></span>
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
							<input type="date" class="form-control dob" name="dob" id="dob"
							<?php
							if(isset($_GET['validation']) && $_GET['validation'] == 1)
							{
								echo "value=" . $_SESSION['error_array']['dob']['val'];
							}
							else
							{
								if(isset($row['dob']))
								{
									echo "value=" . $row['dob'];
								}
							}
							?> >
							<span class="text-danger err_msg" id="err_dob"></span>
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">' . $_SESSION['error_array']
								['dob']['msg'] . "</span>"; 
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
									if( 'Unemployed' == $_SESSION['error_array']['employment']
										['val'])
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
								echo "value='" . $_SESSION['error_array']['employer']['val'] . "'";
							}
							else
							{
								if(isset($row['employer']))
								{
									echo "value='" . $row['employer'] . "'";
								}
							}
							?> >
							<?php 
							if(isset($_GET['validation']) && 1 == $_GET['validation'])
							{
								echo '<span class="text-danger">' . $_SESSION['error_array']
								['employer']['msg'] . "</span>"; 
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
									class="form-control alpha_numeric" placeholder="Street"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value='" . $_SESSION['error_array']['r_street']
										['val'] . "'";
									}
									else
									{
										if(isset($row['r_street']))
										{
											echo "value=" . $row['r_street'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_r_street"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['r_street']['msg'] . "</span>"; 
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
									class="form-control text_field" placeholder="City"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value='" . $_SESSION['error_array']['r_city']
										['val'] . "'";
									}
									else
									{
										if(isset($row['r_city']))
										{
											echo "value=" . $row['r_city'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_r_city"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['r_city']['msg'] . "</span>"; 
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
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['r_state']['msg'] . "</span>"; 
									}
									?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
									<label for="r_zip">Zip:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="r_zip" name="r_zip" 
									class="form-control number_field" placeholder="751024" 
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=" . $_SESSION['error_array']['r_zip']['val'];
									}
									else
									{
										if(isset($row['r_zip']))
										{
											echo "value=" . $row['r_zip'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_r_zip"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['r_zip']['msg'] . "</span>"; 
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
									class="form-control number_field" 
									placeholder="9776097760 / 06742552115" 
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=" . $_SESSION['error_array']['r_phone']['val'];
									}
									else
									{
										if(isset($row['r_phone']))
										{
											echo "value=" . $row['r_phone'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_r_phone"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['r_phone']['msg'] . "</span>";
									}?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
									<label for="r_fax">Fax:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="r_fax" name="r_fax" 
									class="form-control number_field" placeholder="04442544302"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=" . $_SESSION['error_array']['r_fax']['val'];
									}
									else
									{
										if(isset($row['r_fax']))
										{
											echo "value=" . $row['r_fax'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_r_fax"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['r_fax']['msg'] . "</span>"; 
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
									class="form-control alpha_numeric" placeholder="Street" 
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value='" . $_SESSION['error_array']['o_street']
										['val'] . "'";
									}
									else
									{
										if(isset($row['o_street']))
										{
											echo "value=".$row['o_street'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_o_street"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['o_street']['msg'] . "</span>";
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
									<input type="text" id="o_city" name="o_city" 
									class="form-control text_field" placeholder="City"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value='" . $_SESSION['error_array']['o_city']
										['val'] . "'";
									}
									else
									{
										if(isset($row['o_city']))
										{
											echo "value=" . $row['o_city'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_o_city"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['o_city']['msg'] . "</span>";
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
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['o_state']['msg'] . "</span>";
									}?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 
								col-md-offset-1 col-lg-offset-1">
									<label for="o_zip">Zip:</label>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="o_zip" name="o_zip" 
									class="form-control number_field" placeholder="751024"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=" . $_SESSION['error_array']['o_zip']['val'];
									}
									else
									{
										if(isset($row['o_zip']))
										{
											echo "value=" . $row['o_zip'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_o_zip"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['o_zip']['msg'] . "</span>";
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
									class="form-control number_field" 
									placeholder="9776097760 / 06742552115"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=" . $_SESSION['error_array']['o_phone']['val'];
									}
									else
									{
										if(isset($row['o_phone']))
										{
											echo "value=" . $row['o_phone'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_o_phone"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['o_phone']['msg'] . "</span>";
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
									<input type="text" id="o_fax" name="o_fax" 
									class="form-control number_field" placeholder="04442544302"
									<?php
									if(isset($_GET['validation']) && $_GET['validation'] == 1)
									{
										echo "value=" . $_SESSION['error_array']['o_fax']['val'];
									}
									else
									{
										if(isset($row['o_fax']))
										{
											echo "value=" . $row['o_fax'];
										}
									}
									?> >
									<span class="text-danger err_msg" id="err_o_fax"></span>
									<?php 
									if(isset($_GET['validation']) && 1 == $_GET['validation'])
									{
										echo '<span class="text-danger">' . $_SESSION['error_array']
										['o_fax']['msg'] . "</span>";
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
								echo "<img src=" . PIC_PATH . $_SESSION['error_array']['photo']
								['val'] . " width=200 height=200>";
							}
							elseif(isset($_SESSION['error_array']['photo']['val']))
							{
								$pic_name = PIC_PATH . $row['photo'];
								if(file_exists($pic_name))
								{
									echo '<td><img src="' . $pic_name . '" width=200 height=200</td>';
								}
							}
							
							if(isset($_SESSION['error_array']['photo']['msg']))
							{
								echo '<span class="text-danger">' . $_SESSION['error_array']
								['photo']['msg'] . "</span>";
							}
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
								if(isset($row['notes']))
								{
									$note_value = $row['notes'];
								}
								else
								{
									$note_value = "";
								}
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
					if(!empty($_GET['id']))
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