function form_validation()
{
	// window.alert();
	// return false;
	var errors = 0;
	var f_name = document.getElementById('first_name').value;
	var m_name = document.getElementById('middle_name').value;
	var l_name = document.getElementById('last_name').value;
	var r_street = document.getElementById('r_street').value;
	var o_street = document.getElementById('o_street').value;
	var r_city = document.getElementById('r_city').value;
	var o_city = document.getElementById('o_city').value;
	var r_zip = document.getElementById('r_zip').value;
	var o_zip = document.getElementById('o_zip').value;
	var r_phone = document.getElementById('r_phone').value;
	var o_phone = document.getElementById('o_phone').value;
	var r_fax = document.getElementById('r_fax').value;
	var o_fax = document.getElementById('o_fax').value;

	
	if(!pure_string(f_name.trim(), 'err_first_name'))
	{
		errors++;
	}
	if(!pure_string_empty(m_name.trim()))
	{
		errors++;
	}
	if(!pure_string(l_name.trim(), 'err_last_name'))
	{
		errors++;
	}
	if(!alpha_numeric(r_street.trim(), 'err_r_street'))
	{
		errors++;
	}
	if(!alpha_numeric(o_street.trim(), 'err_o_street'))
	{
		errors++;
	}
	if(!pure_string(r_city.trim(), 'err_r_city'))
	{
		errors++;
	}
	if(!pure_string(o_city.trim(), 'err_o_city'))
	{
		errors++;
	}
	if(!digit(r_zip.trim(), 'err_r_zip', 'zip'))
	{
		errors++;
	}
	if(!digit(o_zip.trim(), 'err_o_zip', 'zip'))
	{
		errors++;
	}
	if(!digit(r_phone.trim(), 'err_r_phone', 'phone'))
	{
		errors++;
	}
	if(!digit(o_phone.trim(), 'err_o_phone', 'phone'))
	{
		errors++;
	}
	if(!digit(r_fax.trim(), 'err_r_fax', 'fax'))
	{
		errors++;
	}
	if(!digit(o_fax.trim(), 'err_o_fax', 'fax'))
	{
		errors++;
	}

	if(errors > 0)
	{
		console.log(errors);
		return false;
	}
	console.log(errors);
	return false;
}

function pure_string(name, error_field)
{
	if(name.length == 0)
	{
		document.getElementById(error_field).innerHTML = "*Mandatory Field !";
		return false;
	}
	else if(!name.match(/^[a-zA-Z ]*$/))
	{
		document.getElementById(error_field).innerHTML = "*Only alphabets allowed !";
		return false;
	}
	else
	{
		document.getElementById(error_field).innerHTML = "";
		return true;	
	}
}

function pure_string_empty(name)
{
	if(name.length == 0)
	{
		return true;
		document.getElementById('err_middle_name').innerHTML = "";
	}
	else if(!name.match(/^[a-zA-Z ]*$/))
	{
		document.getElementById('err_middle_name').innerHTML = "*Only alphabets allowed !";
		return false;
	}
	else
	{
		document.getElementById('err_middle_name').innerHTML = "";
		return true;
	}
}

function alpha_numeric(street, error_field)
{
	if(street.length == 0)
	{
		document.getElementById(error_field).innerHTML = "*Mandatory Field !";
		return false;
	}
	else if(!street.match(/^[a-zA-Z0-9 _-]+$/))
	{
		document.getElementById(error_field).innerHTML = "*Only alph-numeric allowed !";
		return false;
	}
	else
	{
		document.getElementById(error_field).innerHTML = "";
		return true;
	}
}

function digit(number, error_field, type)
{
	if(type == 'zip')
	{
		if(number.length == 0)
		{
			document.getElementById(error_field).innerHTML = "*Mandatory Field !";
			return false;
		}
		else if(!(number.match(/^(\d{6})/) && number.length == 6))
		{
			document.getElementById(error_field).innerHTML = "*Provide a Numeric value of length 6 !";
			return false;
		}
		else
		{
			document.getElementById(error_field).innerHTML = "";
			return true;
		}
	}
	else if(type == 'phone' || type == 'fax')
	{
		if(number.length == 0)
		{
			document.getElementById(error_field).innerHTML = "*Mandatory Field !";
			return false;
		}
		else if(!(number.match(/^(\d{7})/) && number.length >= 7 && number.length <= 12))
		{
			document.getElementById(error_field).innerHTML = "*Provide a Numeric value of length" + 
			"between 7 and 12 !";
			return false;
		}
		else
		{
			document.getElementById(error_field).innerHTML = "";
			return true;
		}
	}

}