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

	$path_file		= "./keyboard-init.php";	// possibly unneeded
	$path_root		= "../../";	// for PHP files
	$path_lib1		= "./lib/";	// for HTML and JS files
	$path_lib2		= "./";		// for PHP files

	include($path_root	. "ssi/analyticstracking.php");
	include($path_root	. "ssi/keyboard-connection.php");
	include($path_lib2	. "keyboard-common.php");
	include($path_lib2	. "keyboard-queries.php");

	// gather URL queries
	$format_id	= array_key_exists("fmt", $_GET) ? intval(ltrim($_GET["fmt"], "0"))	: null;
	$svg_bool	= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0"))	: null;
	$url_ext	= extension($_SERVER["REQUEST_URI"]);

	// check URL queries validity
	if (($url_ext == "svg") || ($svg_bool == 1))
	{
		$format_id = 1;
	}
	else if ($format_id === null)
	{
		$format_id = 0;
	}

	switch ($format_id)
	{
		case 0:
			include($path_lib2 . "keyboard-embed.php");
		break;
		case 1:
			include($path_lib2 . "keyboard-svg.php");
		break;
		case 2:
			include($path_lib2 . "keyboard-wiki.php");
		break;
		case 3:
			include($path_lib2 . "keyboard-submit.php");
		break;
		case 4:
			echo "PDF format not implemented yet.\n";
		break;
		default:
			echo "Unrecognized output format.\n";
		break;
	}
?>
