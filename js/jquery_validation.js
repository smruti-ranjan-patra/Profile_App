// jQuery validation
$(document).ready(function()
{
	var errors = 0;
	var s_form = $("#sign_up_form");
	var l_form = $("#login_form");
	var f_name = $("#first_name");
	var m_name = $("#middle_name");
	var l_name = $("#last_name");
	var dob = $("#dob");
	var email = $("#email");
	var password = $("#password");
	var r_street = $("#r_street");
	var o_street = $("#o_street");
	var r_city = $("#r_city");
	var o_city = $("#o_city");
	var r_zip = $("#r_zip");
	var o_zip = $("#o_zip");
	var r_phone = $("#r_phone");
	var o_phone = $("#o_phone");
	var r_fax = $("#r_fax");
	var o_fax = $("#o_fax");

	// f_name.keyup(function()
	// {
	// 	errors += pure_string(f_name.val().trim(), 'err_first_name');
	// });
	// m_name.keyup(function()
	// {
	// 	errors += pure_string_empty(m_name.val().trim());
	// });
	// l_name.keyup(function()
	// {
	// 	errors += pure_string(l_name.val().trim(), 'err_last_name');
	// });
	// dob.keyup(function()
	// {
	// 	errors += dob_validation(dob.val().trim(), 'err_dob');
	// });
	// email.keyup(function()
	// {
	// 	errors += email_validation(email.val().trim(), 'err_email');
	// });
	// password.keyup(function()
	// {
	// 	errors += password_validation(password.val().trim(), 'err_password');
	// });
	// r_street.keyup(function()
	// {
	// 	errors += alpha_numeric(r_street.val().trim(), 'err_r_street');
	// });
	// o_street.keyup(function()
	// {
	// 	errors += alpha_numeric(o_street.val().trim(), 'err_o_street');
	// });
	// r_city.keyup(function()
	// {
	// 	errors += pure_string(r_city.val().trim(), 'err_r_city');
	// });
	// o_city.keyup(function()
	// {
	// 	errors += pure_string(o_city.val().trim(), 'err_o_city');
	// });
	// r_zip.keyup(function()
	// {
	// 	errors += number_validation(r_zip.val().trim(), 'err_r_zip', 'zip');
	// });
	// o_zip.keyup(function()
	// {
	// 	errors += number_validation(o_zip.val().trim(), 'err_o_zip', 'zip');
	// });
	// r_phone.keyup(function()
	// {
	// 	errors += number_validation(r_phone.val().trim(), 'err_r_phone', 'phone');
	// });
	// o_phone.keyup(function()
	// {
	// 	errors += number_validation(o_phone.val().trim(), 'err_o_phone', 'phone');
	// });
	// r_fax.keyup(function()
	// {
	// 	errors += number_validation(r_fax.val().trim(), 'err_r_fax', 'fax');
	// });
	// o_fax.keyup(function()
	// {
	// 	errors += number_validation(o_fax.val().trim(), 'err_o_fax', 'fax');
	// });



	s_form.submit(function()
		{
			// errors = 0;
			//alert(errors);
			errors += pure_string(f_name.val().trim(), 'err_first_name');
			errors += pure_string_empty(m_name.val().trim());
			errors += pure_string(l_name.val().trim(), 'err_last_name');
			errors += dob_validation(dob.val().trim(), 'err_dob');
			errors += email_validation(email.val().trim(), 'err_email');
			errors += password_validation(password.val().trim(), 'err_password');
			errors += alpha_numeric(r_street.val().trim(), 'err_r_street');
			errors += alpha_numeric(o_street.val().trim(), 'err_o_street');
			errors += pure_string(r_city.val().trim(), 'err_r_city');
			errors += pure_string(o_city.val().trim(), 'err_o_city');
			errors += number_validation(r_zip.val().trim(), 'err_r_zip', 'zip');
			errors += number_validation(o_zip.val().trim(), 'err_o_zip', 'zip');
			errors += number_validation(r_phone.val().trim(), 'err_r_phone', 'phone');
			errors += number_validation(o_phone.val().trim(), 'err_o_phone', 'phone');
			errors += number_validation(r_fax.val().trim(), 'err_r_fax', 'fax');
			errors += number_validation(o_fax.val().trim(), 'err_o_fax', 'fax');

			if(errors > 0)
			{
				return true;
			}
			else
			{
				return true;
			}
		});

	l_form.submit(function()
		{
			errors = 0;
			errors += email_validation(email.val().trim(), 'err_email');
			errors += password_validation(password.val().trim(), 'err_password');

			if(errors > 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		});


	function pure_string(name, error_field)
	{
		if(name.length == 0)
		{
			$("#" + error_field).html("*Mandatory Field !");
			return 1;
		}
		else if(!(/^[a-zA-Z ]*$/).test(name))
		{
			$("#" + error_field).html("*Only alphabets allowed !");
			return 1;
		}
		else
		{
			$("#" + error_field).html("");
			return 0;	
		}
	}

	function pure_string_empty(name)
	{
		if(name.length == 0)
		{
			$("#err_middle_name").html("");
			return 0;
		}
		else if(!name.match(/^[a-zA-Z ]*$/))
		{
			$("#err_middle_name").html("*Only alphabets allowed !");
			console.log("*Only alphabets allowed !");
			return 1;
		}
		else
		{
			$("#err_middle_name").html("");
			return 0;
		}
	}

	function dob_validation(dob_value, error_field)
	{
		if(dob_value.length == 0)
		{
			$("#" + error_field).html("*Date of Birth Mandatory !");
			return 1;
		}
		else
		{
			$("#" + error_field).html("");
			return 0;
		}
	}

	function email_validation(email_value, error_field)
	{
		if(email_value.length == 0)
		{
			$("#" + error_field).html("*Mandatory Field !");
			return 1;
		}
		else if(!(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/).
			test(email_value))
		{
			$("#" + error_field).html("*Invalid Email id !");
			return 1;
		}
		else
		{
			$("#" + error_field).html("");
			return 0;	
		}
	}

	function password_validation(password_value, error_field)
	{
		if(password_value.length == 0)
		{
			$("#" + error_field).html("*Mandatory Field !");
			return 1;
		}
		else if(!(password_value.length >= 8 && password_value.length <= 12))
		{
			$("#" + error_field).html("*Password length must be between 8 to 12 !");
			return 1;
		}
		else
		{
			$("#" + error_field).html("");
			return 0;	
		}
	}

	function alpha_numeric(street_name, error_field)
	{
		if(street_name.length == 0)
		{
			$("#" + error_field).html("*Mandatory Field !");
			return 1;
		}
		else if(!street_name.match(/^[a-zA-Z0-9 _-]+$/))
		{
			$("#" + error_field).html("*Only alphabets and numbers allowed !");
			return 1;
		}
		else
		{
			$("#" + error_field).html("");
			return 0;
		}
	}

	function number_validation(number_value, error_field, type)
	{
		if(number_value.length == 0)
		{
			$("#" + error_field).html("*Mandatory Field !");
			return 1;
		}
		if(type == "zip")
		{
			if(!(number_value.match(/^(\d{6})/) && number_value.length == 6))
			{
				$("#" + error_field).html("*Provide a Numeric value of length 6 !");
				return 1;
			}
			else
			{
				$("#" + error_field).html("");
				return 0;
			}
		}
		else if(type == "phone" || type == "fax")
		{
			if(!(number_value.match(/^(\d{7})/) && number_value.length >= 7 && 
				number_value.length <= 12))
			{
				$("#" + error_field).html("*Provide a Numeric value of length between 7 and 12 !");
				return 1;
			}
			else
			{
				$("#" + error_field).html("");
				return 0;
			}
		}
	}

});