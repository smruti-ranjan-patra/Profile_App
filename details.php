<?php
session_start();

if(!isset($_SESSION['id']))
{
	header('Location:home_default.php');
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Display Page</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/form.css">
</head>
<body>
	<!-- Navigation bar -->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="sign_up.php?id=<?php echo $_SESSION['id'];?>">Home</a></li>
				<li class="active"><a href="details.php">Details</a></li>
				<li><a href="sign_out.php">Sign out</a></li>
			</ul>
		</div>
	</nav>
	<?php

		require_once('config/database.php');
		require_once('config/constants.php');
		require_once('class/DatabaseConnection.php');

		$obj = DatabaseConnection::create_connection($db['master']);
		$result_list = $obj->employee_list();
		$rnum = 0;
		$rnum = DatabaseConnection::db_num_rows($result_list);
		if($rnum == 0)
		{
			echo "<h3>No Record Present</h3>";
		}
		else
		{?>
			<h2><u>Employee Details :-</u></h2>
			<div class="table-responsive">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Sl</th>
						<th>Prefix</th>
						<th>Name</th>
						<th>Gender</th>
						<th>DOB</th>
						<th>Marital</th>
						<th>Employment</th>
						<th>Employer</th>
						<th>Residence</th>
						<th>Office</th>
						<th>Communication</th>
						<th>Photo</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sl = 1;
					while ($row = DatabaseConnection::db_fetch_array($result_list))
					{
						echo "<tr>";
						echo "<td>".$sl++."</td>";
						foreach ($row as $key => $value)
						{
							if($key == 'comm_id')
							{
								$result_list_comm = $obj->select_comm_field($value);

								while ($row1 = DatabaseConnection::db_fetch_array($result_list_comm))
								{
									foreach ($row1 as $key => $value) 
									{
										echo "<td>".$value."</td>";
									}
								}
							}
							elseif($key == 'dob')
							{
								echo "<td>".date("d-M-Y", strtotime($value))."</td>";
							}
							elseif($key != 'id' && $key != 'photo')
							{
								echo "<td>".$value."</td>";
							}
						}
						if($row['photo'])
						{
							$pic_name = PIC_PATH.$row['photo'];

							if(file_exists($pic_name))
							{
								echo '<td><img src="'.$pic_name.'" width=100 height=100</td>';
							}
							else
							{
								echo "<td>No image found</td>";
							}
						}
						elseif($row['gender'] == 'Male')
						{
							echo '<td><img src="default_images/default_male_pic.jpg" width=100 
							height=100</td>';
						}
						elseif($row['gender'] == 'Female')
						{
							echo '<td><img src="default_images/default_female_pic.jpg" width=100 
							height=100</td>';
						}
						else
						{
							echo '<td><img src="default_images/default_others_pic.jpg" width=100 
							height=100</td>';
						}
						?>
						<td>
						<?php
						if($row['id'] == $_SESSION['id'])
						{
							?>
							<a href="sign_up.php?id=<?php echo $row['id'] ?>">
							<span class="glyphicon glyphicon-pencil" ></span></a>
							<?php
						}?>						
						</td>
						<td>
						<?php
						if($row['id'] == $_SESSION['id'])
						{
							?>
							<a href="delete.php?id=<?php echo $row['id']?>">
							<span class="glyphicon glyphicon-remove" ></span></a>
							<?php
						}?>
						</td>
						<?php
						echo "</tr>";
					}
				?>
				</tbody>
			</table>
			</div>
		<?php
		}
		?>
</body>
</html>