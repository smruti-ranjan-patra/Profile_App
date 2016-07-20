var page = 1;
var c_name;
var obj;

$(document).ready(function()
{
	response();
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
	});
	$('#gender_column').on('click', function()
	{
		response($("#name").val(),'gender', this);
	});
	$('#dob_column').on('click', function()
	{
		response($("#name").val(),'dob', this);
	});
	$('#prev_button').on('click', function()
	{
		response($("#name").val(), c_name, obj, $(this).text());
	});
	$('#next_button').on('click', function()
	{
		response($("#name").val(), c_name, obj, $(this).text());
	});
}

function response(input_name = "", column_name = "", ob = "", page_no = 1)
{
	var order_type = "";
	var url = window.location.pathname;
	var file_name = url.substring(url.lastIndexOf('/')+1);
	var resource_name = file_name.split('.')[0];

	c_name = column_name;
	obj = ob;

	if($(ob).prop('class') == 'order_asc')
	{
		order_type = 'ASC';
	}
	else
	{
		order_type = 'DESC';
	}

	$.ajax(
	{
		url: './display_helper.php',
		data:
		{
			name: input_name,
			fields: c_name,
			type: order_type,
			page : page_no
		},
		type: 'POST',
		dataType : 'JSON',
		success: function(employee)
		{
			if(employee.err_val == 1)
			{
				alert(employee.err_msg);
				location.reload(true);
				return;
			}

			var display_string = "";
			var page_string = "";

			// If no records found
			if(employee.details == null)
			{
				display_string = '';
				$('h2').html('<h3>No records found</h3>');
				page_string = "";
				$('.page_numbers').html(page_string);
			}
			else
			{
				$('h2').html('<u>Employee Details :-</u>');
				display_string += '<table class="table table-bordered table-hover"><thead><tr><th>Sl</th><th>Prefix</th>';
				var name_order_type = 'order_asc';
				var gender_order_type = 'order_asc';
				var dob_order_type = 'order_asc';

				if(column_name == 'first_name')
				{
					name_order_type = (order_type == 'ASC') ? 'order_desc' : 'order_asc';
				}
				else if(column_name == 'gender')
				{
					gender_order_type = (order_type == 'ASC') ? 'order_desc' : 'order_asc';
				}
				else(column_name == 'dob')
				{
					dob_order_type = (order_type == 'ASC') ? 'order_desc' : 'order_asc';
				}

				display_string += '<th id="name_column" class="' + name_order_type + '">Name</th><th id="gender_column" class="' + gender_order_type + '">Gender</th><th id="dob_column" class="' + dob_order_type + '">DOB</th>';

				display_string += '<th>Marital</th><th>Employment</th><th>Employer</th><th>Residence</th><th>Office</th><th>Communication</th><th>Photo</th><th>Edit</th><th>Delete</th></tr></thead><tbody class="table_data">';

				// Create the records in the table
				for(i in employee.details)
				{
					var serial_num = (list_size * page_no) - list_size + Number(i) + 1;
					display_string += '<tr>' + '<td>' + serial_num + '</td>';
					display_string += '<td>' + employee.details[i].prefix + '</td>';
					display_string += '<td>' + employee.details[i].f_name + ' ' + 
					employee.details[i].m_name + ' ' + employee.details[i].l_name + '</td>';
					display_string += '<td>' + employee.details[i].gender + '</td>';
					display_string += '<td>' + employee.details[i].dob + '</td>';
					display_string += '<td>' + employee.details[i].marital_status + '</td>';
					display_string += '<td>' + employee.details[i].employment + '</td>';
					display_string += '<td>' + employee.details[i].employer + '</td>';
					
					display_string += '<td>' + employee.details[i].r_street + ', ' + employee.details[i].r_city + 
					', ' + employee.details[i].r_state + ', ' + employee.details[i].r_zip + ', ' + 
					employee.details[i].r_phone + ', ' + employee.details[i].r_fax + '</td>';

					display_string += '<td>' + employee.details[i].o_street + ', ' + employee.details[i].o_city + 
					', ' + employee.details[i].o_state + ', ' + employee.details[i].o_zip + ', ' + 
					employee.details[i].o_phone + ', ' + employee.details[i].o_fax + '</td>';

					display_string += '<td>' + employee.details[i].comm + '</td>';

					if(employee.details[i].pic == "")
					{
						display_string += '<td>No image found</td>';
					}
					else
					{
						display_string += '<td><img src="' + employee.details[i].pic + '" width=100 height=100</td>';
					}

					if (employee.permission_info['role'] == 'admin') 
					{
						display_string += '<td><a href="sign_up.php?id=' + employee.details[i].emp_id + '">';
						display_string += '<span class="glyphicon glyphicon-pencil"></span></a></td>';

						display_string += '<td><a href="delete.php?id=' + employee.details[i].emp_id + '">';
						display_string += '<span class="glyphicon glyphicon-remove"></span></a></td>';
					} 
					else if(employee.details[i].session_id == employee.details[i].emp_id)
					{

						if (employee.permission_info['permissions'][resource_name + '-edit']) 
						{
							display_string += '<td><a href="sign_up.php?id=' + employee.details[i].emp_id + '">';
							display_string += '<span class="glyphicon glyphicon-pencil"></span></a></td>';
						}
						else
						{
							display_string += '<td></td>';
						}

						if (employee.permission_info['permissions'][resource_name + '-delete']) 
						{
							display_string += '<td><a href="delete.php?id=' + employee.details[i].emp_id + '">';
							display_string += '<span class="glyphicon glyphicon-remove"></span></a></td>';
						}
						else
						{
							display_string += '<td></td>';
						}

					}
					else
					{
						display_string += '<td></td><td></td>';
					}
					display_string += '</tr>';
				}
				display_string += '</table>';

				// Display Page numbers				
				if(employee.num_of_records != 0)
				{
					page_string += '<ul class="pagination">';
					var page_display;

					if(page_no == 1)
					{
						page_string += '<li class="active"><a id="current_button">' + page_no + '</a></li>';
						page_display = Number(page_no) + 1;

						if(page_display <= Math.ceil(employee.num_of_records / list_size))
						{
							page_string += '<li><a id="next_button">' + page_display + '</a></li>';
						}
					}
					else
					{
						page_display = Number(page_no) - 1;
						page_string += '<li><a id="prev_button">' + page_display + '</a></li>';
						page_string += '<li class="active"><a id="current_button">' + page_no + '</a></li>';
						page_display = Number(page_no) + 1;

						if(page_display <= Math.ceil(employee.num_of_records / list_size))
						{
							page_string += '<li><a id="next_button">' + page_display + '</a></li>';
						}
					}

					page_string += '</ul>';
					$('.page_numbers').html(page_string);
				}
			}

			$('.table-responsive').html(display_string);
			event_handler();
		}
	});
}
