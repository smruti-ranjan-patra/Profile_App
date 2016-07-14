<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();

	require_once('class/DatabaseConnection.php');
	require_once('config/database.php');
	require_once('acl.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>User Home</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<script   src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
		<script type="text/javascript" src="js/admin_access.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<li class="active"><a href="dashboard.php">Home</a></li>
					<li><a href="sign_up.php">View Profile</a></li>
					<li><a href="details.php">Details</a></li>
					<li><a href="sign_out.php">Sign out</a></li>
				</ul>
			</div>
		</nav>
		<div>
			<?php
				$obj2 = DatabaseConnection::create_connection($db['master']);
				echo '<h1>Welcome ' . $_SESSION['permission_info']['role'] . '</h1>';
				$resource_qry = DatabaseConnection::db_query("SELECT id, resource_name FROM resource");
				$permission_qry = DatabaseConnection::db_query("SELECT id, permission_name FROM permission");
				$role_qry = DatabaseConnection::db_query("SELECT id, role_name FROM role");
				$role_res_per_qry = DatabaseConnection::db_query("SELECT fk_role, fk_resource, fk_permission FROM role_resource_permission");

				$permission_result = [];

				while($row = DatabaseConnection::db_fetch_array($permission_qry))
				{
					$permission_result[] = $row;
				}

				$resource_result = [];

				while($row = DatabaseConnection::db_fetch_array($resource_qry))
				{
					$resource_result[] = $row;
				}

				$selected_permission = [];

				while ($row = DatabaseConnection::db_fetch_array($role_res_per_qry))
				{
					$selected_permission[$row['fk_role'] . '-' . $row['fk_resource'] . '-' . 
					$row['fk_permission']] = true;
				}

			?>
			<form action="" method="post" id="admin_access">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>Role</th>
								<th>Resource</th>
								<?php

								foreach($permission_result as $permission)
								{
									echo '<th>' . ucfirst($permission['permission_name']) . '</th>';
								}

								?>

							</tr>
						</thead>
						<tbody>
							<?php

								while ($role = DatabaseConnection::db_fetch_array($role_qry))
								{

									if($role['role_name'] == 'admin')
									{
										continue;
									}

									foreach($resource_result as $resource)
									{
										echo '<tr>';
										echo '<td>' . $role['role_name'] . '</td>';
										echo '<td>' . $resource['resource_name'] . '</td>';

										foreach ($permission_result as $permission)
										{
											$checked = '';

											if (isset($selected_permission[$role['id'] . '-' . $resource['id'] . '-' . $permission['id']]))
											{
												$checked = ' checked="checked"';
											}

											echo '<td>';
											// $q = mysqli_query($acl->conn, "SELECT permission_name FROM permission WHERE id={$permission['id']}");
											// $row = mysqli_fetch_assoc($q);
											// $permission_name = $row['permission_name'];

											// echo '<input type="checkbox" name="' . $resource['resource_name'] . '[]"' . $checked . '>';
											echo '<input type="checkbox" class="permission-check" id="' . $role['id'] . '-' . $resource['id'] . '-' . $permission['id'] . '"' . $checked . '>';
											echo '</td>';
										}

										echo '</tr>';
									}
								}

							?>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</body>
</html>

