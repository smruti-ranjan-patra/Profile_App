// jQuery validation
$(document).ready(function()
{
	validation();	
});

/**
* To start validation
*
* @access public
* @param  void
* @return void
*/
function validation()
{
	$('#sign_up_form').submit(function()
	{
		var return_val_name = true;
		var text_elements = $('.text_field');

		$.each(text_elements, function(index, value)
		{
			var check = strict_alphabet($(value))
			if(check == false)
			{
				return_val_name = false;
				return;
			}
		});

		var return_val_num = true;
		var number_elements = $('.number_field');

		$.each(number_elements, function(index, value)
		{
			var check = number_validation($(value))
			if(check == false)
			{
				return_val_num = false;
				return;
			}
		});

		var return_val_alpha_num = true;
		var alpha_num_elements = $('.alpha_numeric');

		$.each(alpha_num_elements, function(index, value)
		{
			var check = alpha_num_validation($(value))
			if(check == false)
			{
				return_val_alpha_num = false;
				return;
			}
		});

		var return_val_dob = dob_validation($('.dob'));
		var reurn_val_email = email_validation($('.email'));
		var reurn_val_pwd = password_validation($('.password'));

		return (return_val_name && return_val_num && return_val_alpha_num && return_val_dob && 
			reurn_val_email && reurn_val_pwd);
	});

	$('#login_form').submit(function()
	{
		var reurn_login_email = email_validation($('.login_email'));
		var reurn_login_pwd = password_validation($('.login_password'));
		return (reurn_login_email && reurn_login_pwd);
	});

	$('#reset').on('click', function()
	{
		$('.err_msg').html("");
	});

	// To check the validation on text field when user types something
	$('.text_field').on('keyup blur change', function()
		{
			strict_alphabet($(this));
		});

	// To check the validation on numeric field when user types something
	$('.number_field').on('keyup blur change', function()
		{
			number_validation($(this));
		});

	// To check the validation on text field which may be number as well as string, when user types something
	$('.alpha_numeric').on('keyup blur change', function()
		{
			alpha_num_validation($(this));
		});

	// To check the validation on date of birth field whether empty or not
	$('.dob').on('keyup blur change', function()
		{
			dob_validation($(this));
		});

	// To check the validation on email field when user types something
	$('.email').on('keyup blur change', function()
		{
			email_validation($(this));
		});

	// To check the validation on password field when user types something
	$('.password').on('keyup blur change', function()
		{
			password_validation($(this));
		});

	// To check the validation on email field when user types something in the login page
	$('.login_email').on('keyup blur change', function()
		{
			email_validation($(this));
		});

	// To check the validation on password field when user types something in the login page
	$('.login_password').on('keyup blur change', function()
		{
			password_validation($(this));
		});
}

/**
* To check string contains alphabets only
*
* @access public
* @param  object elem
* @return boolean
*/
function strict_alphabet(elem)
{
	var is_valid = false;

	if(elem[0].name == 'middle_name' && elem.val().trim().length == 0)
	{
		elem.parent().children('.err_msg').html("");
		is_valid = true;
	}
	else if(elem.val().trim().length == 0)
	{
		elem.parent().children('.err_msg').html("*Mandatory Field !");
	}
	else if(!(/^[a-zA-Z ]*$/).test(elem.val().trim()))
	{
		elem.parent().children('.err_msg').html("*Only alphabets allowed !");
	}
	else
	{
		elem.parent().children('.err_msg').html("");
		is_valid = true;	
	}

	return is_valid;	
}

/**
* To check string contains digits only
*
* @access public
* @param  object elem
* @return boolean
*/
function number_validation(elem)
{
	var is_valid = false;

	if(elem.val().trim().length == 0)
	{
		elem.parent().children('.err_msg').html("*Mandatory Field !");
	}	
	else if(elem[0].name == 'r_zip' || elem[0].name == 'o_zip')
	{
		if(!((/^(\d{6})$/).test(elem.val().trim())))
		{
			elem.parent().children('.err_msg').html("*Provide a Numeric value of length 6");
		}
		else
		{
			elem.parent().children('.err_msg').html("");
			is_valid = true;
		}
	}
	//for residence phone/fax or office phone/fax
	else 
	{
		if(!((/^(\d{7,12})$/).test(elem.val().trim())))
		{
			elem.parent().children('.err_msg').html("*Provide a Numeric value of length \
				between 7 and 12 !");
		}
		else
		{
			elem.parent().children('.err_msg').html("");
			is_valid = true;
		}
	}

	return is_valid;
}

/**
* To check string is alpha-numeric or not
*
* @access public
* @param  object elem
* @return boolean
*/
function alpha_num_validation(elem)
{
	var is_valid = false;

	if(elem.val().trim().length == 0)
	{
		elem.parent().children('.err_msg').html("*Mandatory Field !");
	}
	else if(!(/^[a-zA-Z0-9 _-]*$/).test(elem.val().trim()))
	{
		elem.parent().children('.err_msg').html("*Only alphabets and numbers allowed !");
	}
	else
	{
		elem.parent().children('.err_msg').html("");
		is_valid = true;
	}

	return is_valid;
}

/**
* To check string is null or not
*
* @access public
* @param  object elem
* @return boolean
*/
function dob_validation(elem)
{
	if(elem.val().trim().length == 0)
	{
		elem.parent().children('.err_msg').html("*Mandatory Field !");
		return false;
	}
	else
	{
		elem.parent().children('.err_msg').html("");
		return true;
	}
}

/**
* To check for valid email id
*
* @access public
* @param  object elem
* @return boolean
*/
function email_validation(elem)
{
	var is_valid = false;

	if(elem.val().trim().length == 0)
	{
		elem.parent().children('.err_msg').html("*Mandatory Field !");
	}
	else if(!(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/).test(elem.val().trim()))
	{
		elem.parent().children('.err_msg').html("*Invalid Email ID !");
	}
	else
	{
		elem.parent().children('.err_msg').html("");
		is_valid = true;
	}

	return is_valid;
}

/**
* To check valid password length
*
* @access public
* @param  object elem
* @return boolean
*/
function password_validation(elem)
{
	var is_valid = false;

	if(elem.val().trim().length == 0)
	{
		elem.parent().children('.err_msg').html("*Mandatory Field !");
	}
	else if(!(elem.val().trim().length >= 8 && elem.val().trim().length <= 12))
	{
		elem.parent().children('.err_msg').html("*Password length must be between 8 to 12 !");
	}
	else
	{
		elem.parent().children('.err_msg').html("");
		is_valid =  true;
	}

	return is_valid;
}