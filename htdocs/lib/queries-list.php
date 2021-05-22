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
	// List

	function selGenresList()
	{
		global $con;
		$selectString = "SELECT g.genre_id, g.genre_name FROM genres AS g ORDER BY g.genre_id;";
		selectQuery($con, $selectString, "resGenresList");
	}
	function resGenresList($in_result)
	{
		global $genre_table;
		while ($genre_row = mysqli_fetch_row($in_result))
		{
			// genre_id, genre_name
			$genre_id = $genre_row[0];
			$genre_table[$genre_id-1] = $genre_row;
		}
	}
	function selGamesList()
	{
		global $con;
		$selectString = "SELECT g.genre_id, g.game_id, g.game_name, g.game_seourl FROM games AS g ORDER BY g.game_name;";
		selectQuery($con, $selectString, "resGamesList");
	}
	function resGamesList($in_result)
	{
		global $game_table;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// genre_id, game_id, game_name, game_seourl
			$game_id = $game_row[1];
			$game_table[$game_id-1] = $game_row;
		}
	}
	function selLayoutsList()
	{
		global $con;
		$selectString = "SELECT l.layout_id, l.layout_name, l.platform_id FROM layouts AS l ORDER BY l.layout_name;";
		selectQuery($con, $selectString, "resLayoutsList");
	}
	function resLayoutsList($in_result)
	{
		global $layout_table;
		while ($layout_row = mysqli_fetch_row($in_result))
		{
			// layout_id, layout_name, platform_id
			$layout_id = $layout_row[0];
			$layout_table[$layout_id-1] = $layout_row;
		}
	}
	function selGamesRecordsList()
	{
		global $con;
		$selectString = "SELECT r.record_id, r.game_id, r.layout_id FROM records_games AS r;";
		selectQuery($con, $selectString, "resGamesRecordsList");
	}
	function resGamesRecordsList($in_result)
	{
		global $record_table;
		while ($record_row = mysqli_fetch_row($in_result))
		{
			// record_id, game_id, layout_id
			$record_id = $record_row[0];
			$record_table[$record_id-1] = $record_row;
		}
	}
	function selPlatformsList()
	{
		global $con;
		$selectString = "SELECT p.platform_id, p.platform_name, p.platform_abbv FROM platforms AS p ORDER BY p.platform_name;";
		selectQuery($con, $selectString, "resPlatformsList");
	}
	function resPlatformsList($in_result)
	{
		global $platform_table;
		while ($platform_row = mysqli_fetch_row($in_result))
		{
			// platform_id, platform_name, platform_abbv
			$platform_id = $platform_row[0];
			$platform_table[$platform_id-1] = $platform_row;
		}
	}
?>
