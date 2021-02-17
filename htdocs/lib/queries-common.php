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

	// hardcoded!
	function selURLQueriesAll()
	{
		global $con;
		$selectString =	"SELECT u.entity_id, u.entity_name, u.entity_default FROM entities AS u;";
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
	// hardcoded!
	function selDefaultsAll()
	{
		global $con, $urlqueries_table;
		// array indices are hardcoded here, should get them from database instead
		$selectString =	"SELECT g.game_name, g.game_seourl, l.layout_name, s.style_name, f.format_name, p.platform_name, y.stylegroup_name, r.genre_name, a.language_code
				FROM games AS g, layouts AS l, styles AS s, formats AS f, platforms AS p, stylegroups as y, genres as r, languages as a
				WHERE g.game_id = "	. $urlqueries_table[0][2] . "
				AND l.layout_id = "	. $urlqueries_table[1][2] . "
				AND s.style_id = "	. $urlqueries_table[2][2] . "
				AND f.format_id = "	. $urlqueries_table[3][2] . "
				AND p.platform_id = "	. $urlqueries_table[5][2] . "
				AND y.stylegroup_id = "	. $urlqueries_table[6][2] . "
				AND r.genre_id = "	. $urlqueries_table[7][2] . "
				AND a.language_id = "	. $urlqueries_table[8][2] . ";";
		selectQuery($con, $selectString, "resDefaultsAll");
	}
	function resDefaultsAll($in_result)
	{
		global	$urlqueries_table,
			$default_game_id,	$default_game_name,	$default_game_seo,
			$default_layout_id,	$default_layout_name,
			$default_style_id,	$default_style_name,
			$default_format_id,	$default_format_name,
			$default_ten_bool,
			$default_vert_bool,
			$default_platform_id,	$default_platform_name,
			$default_stylegroup_id,	$default_stylegroup_name,
			$default_genre_id,	$default_genre_name,
			$default_language_id,	$default_language_name;

		// game_name, game_seourl, layout_name, style_name, format_name, platform_name, stylegroup_name, genre_name, language_code
		$game_row = mysqli_fetch_row($in_result);
		$default_game_id		= $urlqueries_table[0][2];
		$default_game_name		= $game_row[0];
		$default_game_seo		= $game_row[1];
		$default_layout_id		= $urlqueries_table[1][2];
		$default_layout_name		= $game_row[2];
		$default_style_id		= $urlqueries_table[2][2];
		$default_style_name		= $game_row[3];
		$default_format_id		= $urlqueries_table[3][2];		// starts at 0 instead of 1
		$default_format_name		= $game_row[4];
		$default_ten_bool		= $urlqueries_table[4][2];		// note that in the database this is stored as an integer and not boolean
		$default_platform_id		= $urlqueries_table[5][2];
		$default_platform_name		= $game_row[5];
		$default_stylegroup_id		= $urlqueries_table[6][2];
		$default_stylegroup_name	= $game_row[6];
		$default_genre_id		= $urlqueries_table[7][2];
		$default_genre_name		= $game_row[7];
		$default_language_id		= $urlqueries_table[8][2];
		$default_language_code		= $game_row[8];
		$default_vert_bool		= $urlqueries_table[9][2];		// note that in the database this is stored as an integer and not boolean
	}
?>
