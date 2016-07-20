<?php
	
	function display_header($header)
	{
		$header_nav_bar = '<nav class="navbar navbar-inverse"><div class="container-fluid"><ul class="nav navbar-nav">';

		foreach($header as $href => $name)
		{
			$header_nav_bar .= '<li><a href="' . $href . '">' . $name . '</a></li>';
		}

		$header_nav_bar .= '</ul></div></nav>';
		echo $header_nav_bar;
	}
	
?>
