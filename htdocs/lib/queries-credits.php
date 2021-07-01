<?php
	// Video Game Keyboard Database
	// Copyright (C) 2018  Michael Horvath
        // 
	// This file is part of Video Game Keyboard Database.
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
	//
	// These queries exist here because my web host does not support MySQL 
	// stored procedures.

	// ---------------------------------------------------------------------
	// Credits

	function selContribsGamesList()
	{
		global $con;
		$selectString = "SELECT a.author_name FROM authors AS a, contribs_games AS c WHERE a.author_id = c.author_id GROUP BY a.author_name ORDER BY a.author_name;";
		selectQuery($con, $selectString, "resContribsGamesList");
	}
	function resContribsGamesList($in_result)
	{
		global $contribsgames_table;
		while ($contribsgames_row = mysqli_fetch_row($in_result))
		{
			// genre_id, genre_name
			$contribsgames_table[] = $contribsgames_row[0];
		}
	}
?>
