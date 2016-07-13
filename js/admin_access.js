$(document).ready(function()
{
	$('.permission-check').on('change', function()
	{
		check_action(this);
	});
});

function check_action(obj)
{
	var id;
	var is_checked;

	if($(obj).prop("checked") == true)
	{
		is_checked = true;
	}
	else
	{		
		is_checked = false;
	}

	id = $(obj).attr('id');

	$.ajax(
	{
		url: './admin_access.php',
		data:
		{
			id: id,
			is_checked: is_checked
		},
		type: 'POST',
		success: function(access_details)
		{
			window.alert("Permission changed");
		}
	});
}