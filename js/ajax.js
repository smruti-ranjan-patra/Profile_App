$(document).ready(function()
{
	response();
	// event_handler();
});
	
/**
* To handle all events
*
* @access public
* @param  void
* @return void
*/
function event_handler()
{
	$('#display_submit').submit(function()
	{
		response($("#name").val());
		return false;
	});

	$('#name_column').on('click', function()
	{
		response($("#name").val(),'first_name', this);
		changeClass(this);
	});
	$('#gender_column').on('click', function()
	{
		response($("#name").val(),'gender', this);
		changeClass(this);
	});
	$('#dob_column').on('click', function()
	{
		response($("#name").val(),'dob', this);
		changeClass(this);
	});
}

function changeClass(obj)
{
	var currentClass = $(obj).prop('class');
	if(currentClass == 'order_asc')
	{
		$(obj).removeClass('order_asc').addClass('order_desc');
	}
	else
	{
		$(obj).removeClass('order_desc').addClass('order_asc');
	}
}

function response(input_name = "", column_name = "", obj = "")
{
	var order_type = "";
	if($(obj).prop('class') == 'order_asc')
	{
		order_type = 'ASC';
	}
	else
	{
		order_type = 'DESC';
	}

	$.ajax(
	{
		url: './employee_details.php',
		data:
		{
			name: input_name,
			fields: column_name,
			type: order_type
		},
		type: 'POST',
		dataType : 'JSON',
		success: function(employee)
		{
			var display_string = "";

			if(employee == null)
			{
				display_string = 'No records found';
			}
			else
			{
				display_string += '<table class="table table-bordered table-hover"><thead><tr><th>Sl</th><th>Prefix</th><th id="name_column" class="order_asc">Name</th><th id="gender_column" class="order_asc">Gender</th><th id="dob_column" class="order_asc">DOB</th><th>Marital</th><th>Employment</th><th>Employer</th><th>Residence</th><th>Office</th><th>Communication</th><th>Photo</th><th>Edit</th><th>Delete</th></tr></thead><tbody class="table_data">';

				for(i in employee)
				{
					var serial_num = Number(i) + 1;
					display_string += '<tr>' + '<td>' + serial_num + '</td>';
					display_string += '<td>' + employee[i].prefix + '</td>';
					display_string += '<td>' + employee[i].f_name + ' ' + employee[i].m_name + ' ' + employee[i].l_name + '</td>';
					display_string += '<td>' + employee[i].gender + '</td>';
					display_string += '<td>' + employee[i].dob + '</td>';
					display_string += '<td>' + employee[i].marital_status + '</td>';
					display_string += '<td>' + employee[i].employment + '</td>';
					display_string += '<td>' + employee[i].employer + '</td>';
					display_string += '<td>' + employee[i].r_street + ', ' + employee[i].r_city + ', ' + employee[i].r_state + ', ' + employee[i].r_zip + ', ' + employee[i].r_phone + ', ' + employee[i].r_fax + '</td>';
					display_string += '<td>' + employee[i].o_street + ', ' + employee[i].o_city + ', ' + employee[i].o_state + ', ' + employee[i].o_zip + ', ' + employee[i].o_phone + ', ' + employee[i].o_fax + '</td>';
					display_string += '<td>' + employee[i].comm + '</td>';

					if(employee[i].pic == "")
					{
						display_string += '<td>No image found</td>';
					}
					else
					{
						display_string += '<td><img src="' + employee[i].pic + '" width=100 height=100</td>';
					}

					if(employee[i].session_id == employee[i].emp_id)
					{
						display_string += '<td><a href="sign_up.php?id=' + employee[i].emp_id + '">';
						display_string += '<span class="glyphicon glyphicon-pencil"></span></a></td>';

						display_string += '<td><a href="delete.php?id=' + employee[i].emp_id + '">';
						display_string += '<span class="glyphicon glyphicon-remove"></span></a></td>';
					}
					else
					{
						display_string += '<td></td><td></td>';
					}
					display_string += '</tr>';
				}
				display_string += '</table>';
			}

			$('.table-responsive').html(display_string);
			//console.log(employee);
			event_handler();
		}
	});

}