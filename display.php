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
				<li><a href="registration_form.php">Register</a></li>
				<li class="active"><a href="display.php">Display details</a></li>
			</ul>
		</div>
	</nav>
	<?php

		require_once('config/db_connection.php');
		require_once('config/photo_path.php');

		$q_fetch = "SELECT emp.prefix AS prefix, CONCAT(emp.first_name,' ', emp.middle_name,' ', 
			emp.last_name) AS name, emp.gender AS gender, emp.dob AS dob, 
			emp.marital_status AS marital_status, emp.employment AS employment, 
			emp.employer AS employer, 
			CONCAT(res.street, ', ', res.city, ', ', res.state, ', ', res.zip, ', ', 
			res.phone, ', ', res.fax) AS res_add, 
			CONCAT(off.street, ', ', off.city, ', ', off.state, ', ', off.zip, ', ', 
			off.phone, ', ', off.fax) AS off_add, emp.comm_id AS comm_id, emp.id, emp.photo AS photo
			from employee AS emp 
			inner join address AS res on (emp.id = res.emp_id and res.address_type = 'residence')
			inner join address AS off on (emp.id = off.emp_id and off.address_type = 'office')";
		
		$result_3 = mysqli_query($conn, $q_fetch);

		$rnum = 0;
		$rnum = mysqli_num_rows($result_3);
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
					while ($row = mysqli_fetch_array($result_3, MYSQLI_ASSOC))
					{
						echo "<tr>";
						echo "<td>".$sl++."</td>";
						foreach ($row as $key => $value)
						{
							if('comm_id' == $key)
							{
								$q_comm = "SELECT GROUP_CONCAT(type SEPARATOR ', ') FROM 
								`communication_medium` WHERE id IN ($value)";
								$result_4 = mysqli_query($conn, $q_comm);
								while ($row1 = mysqli_fetch_array($result_4, MYSQLI_ASSOC))
								{
									foreach ($row1 as $key => $value) 
									{
										echo "<td>".$value."</td>";
									}
								}
							}
							elseif('dob' == $key)
							{
								echo "<td>".date("d-M-Y", strtotime($value))."</td>";
							}
							elseif('id' != $key && 'photo' != $key)
							{
								echo "<td>".$value."</td>";
							}
						}
						if($row['photo'])
						{
							$pic_name = PIC_PATH.$row['photo'];
							echo '<td><img src="'.$pic_name.'" width=100 height=100</td>';
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
							echo '<td><img src="default_images/default_pic.jpg" width=100 
							height=100</td>';
						}
						?>
						<td><a href="registration_form.php?id=<?php echo $row['id'] ?>">
						<span class="glyphicon glyphicon-pencil" ></span></a></td>
						<td><a href="delete.php?id=<?php echo $row['id']?>">
						<span class="glyphicon glyphicon-remove" ></span></a></td>
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