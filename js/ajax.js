var page = 1;
var c_name;
var obj;

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
		response($("#name").text(), c_name, obj, $(this).text());
	});
	// $('#current_button').on('click', function()
	// {
	// 	response($("#name").text(),'', obj, $(this).text());
	// });
	$('#next_button').on('click', function()
	{
		response($("#name").text(), c_name, obj, $(this).text());
	});
}

function response(input_name = "", column_name = "", ob = "", page_no = 1)
{
	var order_type = "";
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
		url: './employee_details.php',
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
			var display_string = "";
			var page_string = "";

			if(employee.details == null)
			{
				display_string = 'No records found';
			}
			else
			{
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

				for(i in employee.details)
				{
					var serial_num = Number(i) + 1;
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

					if(employee.details[i].session_id == employee.details[i].emp_id)
					{
						display_string += '<td><a href="sign_up.php?id=' + employee.details[i].emp_id + '">';
						display_string += '<span class="glyphicon glyphicon-pencil"></span></a></td>';

						display_string += '<td><a href="delete.php?id=' + employee.details[i].emp_id + '">';
						display_string += '<span class="glyphicon glyphicon-remove"></span></a></td>';
					}
					else
					{
						display_string += '<td></td><td></td>';
					}
					display_string += '</tr>';
				}
				display_string += '</table>';

				// Display Page numbers
				page_string += '<ul class="pagination">';

				if(page_no == 1)
				{
					page_display = Number(page_no) + 1;
					page_string += '<li class="active"><a id="current_button">' + page_no + '</a></li>';
					page_string += '<li><a id="next_button">' + page_display + '</a></li>';
				}
				else
				{
					page_display = Number(page_no) - 1;
					page_string += '<li><a id="prev_button">' + page_display + '</a></li>';
					page_string += '<li class="active"><a id="current_button">' + page_no + '</a></li>';
					page_display = Number(page_no) + 1;
					page_string += '<li><a id="next_button">' + page_display + '</a></li>';
				}

				$('.page_numbers').html(page_string);
			}

			$('.table-responsive').html(display_string);

			

			event_handler();
		}
	});

}