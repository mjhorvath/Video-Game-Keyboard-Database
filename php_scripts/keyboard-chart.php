<?php
	// Copyright (C) 2018  Michael Horvath

	// This library is free software; you can redistribute it and/or
	// modify it under the terms of the GNU Lesser General Public
	// License as published by the Free Software Foundation; either
	// version 2.1 of the License, or (at your option) any later version.

	// This library is distributed in the hope that it will be useful,
	// but WITHOUT ANY WARRANTY; without even the implied warranty of
	// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	// Lesser General Public License for more details.

	// You should have received a copy of the GNU Lesser General Public
	// License along with this library; if not, write to the Free Software
	// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 
	// 02110-1301  USA

	$format_id	= array_key_exists("fmt", $_GET) ? intval(ltrim($_GET["fmt"], "0")) : null;
	$svg_bool	= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0")) : null;

	// validity checks
	if ($format_id === null)
	{
		if ($svg_bool !== null)
		{
			$format_id = $svg_bool;
		}
		else
		{
			$format_id == 0;
		}
	}
	if ($format_id == 0)
	{
		include("./keyboard-html.php");
	}
	elseif ($format_id == 1)
	{
		include("./keyboard-embed.php");
	}
	elseif ($format_id == 2)
	{
		include("./keyboard-wiki.php");
	}
	elseif ($format_id == 3)
	{
		include("./keyboard-submit.php");
	}
	elseif ($format_id == 4)
	{
		echo "PDF format not supported yet.\n";
	}
	else
	{
		echo "Unrecognized output format.\n";
	}
?>
