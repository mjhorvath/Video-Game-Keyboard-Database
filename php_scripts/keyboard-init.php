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
	$format_id	= array_key_exists("fmt", $_GET) ? intval(ltrim($_GET["fmt"], "0"))	: null;

	function extension($path)
	{
		$qpos = strpos($path, "?");
		if ($qpos!==false) $path = substr($path, 0, $qpos);
		$extension = pathinfo($path, PATHINFO_EXTENSION);
		return $extension;
	}

	// check URL queries validity
	if (extension($_SERVER["REQUEST_URI"]) == "svg")
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
			include("./keyboard-embed.php");
		break;
		case 1:
			include("./keyboard-svg.php");
		break;
		case 2:
			include("./keyboard-wiki.php");
		break;
		case 3:
			include("./keyboard-submit.php");
		break;
		case 4:
			echo "PDF format not implemented yet.\n";
		break;
		default:
			echo "Unrecognized output format.\n";
		break;
	}
?>
