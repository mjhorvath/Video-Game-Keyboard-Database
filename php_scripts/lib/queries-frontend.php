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
	//
	// These queries exist here because my web host does not support MySQL 
	// stored procedures.


	// ---------------------------------------------------------------------
	// Frontend

	function selGenresFront()
	{
		global $con;
		$selectString = "SELECT g.genre_id, g.genre_name FROM genres AS g;";
		selectQuery($con, $selectString, "resGenresFront");
	}
	function resGenresFront($in_result)
	{
		global $genre_table, $genre_game_table;
		while ($genre_row = mysqli_fetch_row($in_result))
		{
			// genre_id, genre_name
			$genre_id	= $genre_row[0];
			$genre_name	= $genre_row[1];
			$genre_table[$genre_id-1] = $genre_name;
			$genre_game_table[$genre_id-1] = [];
		}
	}
	function selGamesFront()
	{
		global $con;
		$selectString = "SELECT g.genre_id, g.game_id, g.game_name, g.game_friendlyurl FROM games AS g;";
		selectQuery($con, $selectString, "resGamesFront");
	}
	function resGamesFront($in_result)
	{
		global $genre_game_table, $game_table;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// genre_id, game_id, game_name, game_friendlyurl
			$genre_id	= $game_row[0];
			$game_id	= $game_row[1];
			$game_name	= $game_row[2];
			$game_seourl	= $game_row[3];
			$genre_game_table[$genre_id-1][] = $game_row;
			$game_table[$game_id-1] = 1;
		}
	}
	function selStylegroupsFront()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.stylegroup_name FROM stylegroups AS s;";
		selectQuery($con, $selectString, "resStylegroupsFront");
	}
	function resStylegroupsFront($in_result)
	{
		global $stylegroup_table, $stylegroup_style_table;
		while ($stylegroup_row = mysqli_fetch_row($in_result))
		{
			// stylegroup_id, stylegroup_name
			$stylegroup_id		= $stylegroup_row[0];
			$stylegroup_name	= $stylegroup_row[1];
			$stylegroup_table[$stylegroup_id-1] = $stylegroup_name;
			$stylegroup_style_table[$stylegroup_id-1] = [];
		}
	}
	function selStylesFront()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.style_id, s.style_name FROM styles AS s;";
		selectQuery($con, $selectString, "resStylesFront");
	}
	function resStylesFront($in_result)
	{
		global $stylegroup_style_table, $style_table;
		while ($style_row = mysqli_fetch_row($in_result))
		{
			// stylegroup_id, style_id, style_name
			$stylegroup_id	= $style_row[0];
			$style_id	= $style_row[1];
			$style_name	= $style_row[2];
			$stylegroup_style_table[$stylegroup_id-1][] = $style_row;
			$style_table[$style_id-1] = 1;
		}
	}
	function selPlatformsFront()
	{
		global $con;
		$selectString = "SELECT p.platform_id, p.platform_name, p.platform_displayorder FROM platforms AS p;";
		selectQuery($con, $selectString, "resPlatformsFront");
	}
	function resPlatformsFront($in_result)
	{
		global $platform_table, $platform_layout_table;
		while ($platform_row = mysqli_fetch_row($in_result))
		{
			// platform_id, platform_name, platform_displayorder
			$platform_id	= $platform_row[0];
			$platform_name	= $platform_row[1];
			$platform_order	= $platform_row[2];
			$platform_table[$platform_id-1] = $platform_name;
			$platform_layout_table[$platform_id-1] = [];
			$platform_order_table[$platform_id-1] = $platform_order;
		}
		array_multisort($platform_order_table, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $platform_table, $platform_layout_table);
	}
	function selLayoutsFront()
	{
		global $con;
		$selectString = "SELECT l.platform_id, l.layout_id, l.layout_name FROM layouts AS l;";
		selectQuery($con, $selectString, "resLayoutsFront");
	}
	function resLayoutsFront($in_result)
	{
		global $platform_layout_table, $layout_table;
		while ($layout_row = mysqli_fetch_row($in_result))
		{
			// platform_id, layout_id, layout_name
			$platform_id	= $layout_row[0];
			$layout_id	= $layout_row[1];
			$layout_name	= $layout_row[2];
			$platform_layout_table[$platform_id-1][] = $layout_row;
			$layout_table[$layout_id-1] = 1;
		}
	}
?>
