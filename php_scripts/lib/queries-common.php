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
	// All

	function selURLQueriesAll()
	{
		global $con;
		$selectString =	"SELECT u.entity_id, u.entity_name, u.entity_default FROM urlqueries AS u;";
		selectQuery($con, $selectString, "resURLQueriesAll");
	}
	function resURLQueriesAll($in_result)
	{
		global $urlqueries_table;
		while ($query_row = mysqli_fetch_row($in_result))
		{
			// entity_id, entity_name, entity_default
			$urlqueries_table[] = $query_row;
		}
	}
	function selDefaultsAll()
	{
		global $con, $urlqueries_table;
		// array indices are hardcoded here, should get them from database instead
		$selectString =	"SELECT g.game_name, g.game_seourl, l.layout_name, s.style_name, f.format_name
				FROM games AS g, layouts AS l, styles AS s, formats AS f
				WHERE g.game_id = "	. $urlqueries_table[0][2] . "
				AND l.layout_id = "	. $urlqueries_table[1][2] . "
				AND s.style_id = "	. $urlqueries_table[2][2] . "
				AND f.format_id = "	. $urlqueries_table[3][2] . ";";
		selectQuery($con, $selectString, "resDefaultsAll");
	}
	function resDefaultsAll($in_result)
	{
		global	$urlqueries_table,
			$default_game_id,	$default_game_name,	$default_game_seo,
			$default_layout_id,	$default_layout_name,
			$default_style_id,	$default_style_name,
			$default_format_id,	$default_format_name,
			$default_ten_bool;
		$game_row = mysqli_fetch_row($in_result);
		$default_game_id	= $urlqueries_table[0][2];
		$default_game_name	= $game_row[0];
		$default_game_seo	= $game_row[1];
		$default_layout_id	= $urlqueries_table[1][2];
		$default_layout_name	= $game_row[2];
		$default_style_id	= $urlqueries_table[2][2];
		$default_style_name	= $game_row[3];
		$default_format_id	= $urlqueries_table[3][2];		// starts at 0 instead of 1
		$default_format_name	= $game_row[4];
		$default_ten_bool	= $urlqueries_table[4][2];		// note that in the database this is stored as an integer and not boolean
	}
?>
