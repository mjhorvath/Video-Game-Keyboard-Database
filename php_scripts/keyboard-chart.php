<?php
	// Video Game Keyboard Diagrams
	// Copyright (C) 2018  Michael Horvath
        // 
	// This file is part of Video Game Keyboard Diagrams.
        // 
	// This program is free software: you can redistribute it and/or modify
	// it under the terms of the GNU Lesser General Public License as 
	// published by the Free Software Foundation, either version 3 of the 
	// License, or (at your option) any later version.
        // 
	// This program is distributed in the hope that it will be useful, but 
	// WITHOUT ANY WARRANTY; without even the implied warranty of 
	// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU 
	// Lesser General Public License for more details.
        // 
	// You should have received a copy of the GNU Lesser General Public 
	// License along with this program.  If not, see 
	// <https://www.gnu.org/licenses/>.

	// gather URL queries
	$format_id	= array_key_exists("fmt", $_GET) ? intval(ltrim($_GET["fmt"], "0")) : null;
	$svg_bool	= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0")) : null;

	// check URL queries validity
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
		include("./keyboard-embed.php");
	}
	elseif ($format_id == 1)
	{
		include("./keyboard-svg.php");
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
