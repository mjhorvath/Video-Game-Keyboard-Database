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
	// JS

	function selThisGameAutoinc()
	{
		global $con;
		$selectString = "SELECT MAX(g.game_id) FROM games AS g;";
		selectQuery($con, $selectString, "resThisGameAutoinc");
	}
	function resThisGameAutoinc($in_result)
	{
		global $games_max;
		$game_row = mysqli_fetch_row($in_result);
		if ($game_row)
		{
			// MAX(g.game_id)
			$games_max = $game_row[0];
		}
	}
	function selThisLayoutAutoinc()
	{
		global $con;
		$selectString = "SELECT MAX(l.layout_id) FROM layouts AS l;";
		selectQuery($con, $selectString, "resThisLayoutAutoinc");
	}
	function resThisLayoutAutoinc($in_result)
	{
		global $layouts_max;
		$layout_row = mysqli_fetch_row($in_result);
		if ($layout_row)
		{
			// MAX(l.layout_id)
			$layouts_max = $layout_row[0];
		}
	}
	function selThisStyleAutoinc()
	{
		global $con;
		$selectString = "SELECT MAX(s.style_id) FROM styles AS s;";
		selectQuery($con, $selectString, "resThisStyleAutoinc");
	}
	function resThisStyleAutoinc($in_result)
	{
		global $styles_max;
		$style_row = mysqli_fetch_row($in_result);
		if ($style_row)
		{
			// MAX(s.style_id)
			$styles_max = $style_row[0];
		}
	}
	function selSeoUrls()
	{
		global $con;
		$selectString = "SELECT g.game_id, g.game_seourl FROM games AS g ORDER BY g.game_id;";
		selectQuery($con, $selectString, "resSeoUrls");
	}
	function resSeoUrls($in_result)
	{
		global $seourl_table;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// game_id, game_seourl
			$game_id = $game_row[0];
			$seourl_table[$game_id-1] = $game_row[1];
		}
	}
	function selGameRecords()
	{
		global $con;
		$selectString = "SELECT r.record_id, r.game_id, r.layout_id FROM records_games AS r;";
		selectQuery($con, $selectString, "resGameRecords");
	}
	function resGameRecords($in_result)
	{
		global $layout_game_table;
		while ($gamesrecord_row = mysqli_fetch_row($in_result))
		{
			// record_id, game_id, layout_id
//			$gamesrecord_id = $gamesrecord_row[0];
			$game_id = $gamesrecord_row[1];
			$layout_id = $gamesrecord_row[2];
			$layout_game_table[$layout_id-1][$game_id-1] = true;
		}
	}
	function selStyleRecords()
	{
		global $con;
		$selectString = "SELECT r.record_id, r.style_id, r.layout_id FROM records_styles AS r;";
		selectQuery($con, $selectString, "resStyleRecords");
	}
	function resStyleRecords($in_result)
	{
		global $layout_style_table;
		while ($stylesrecord_row = mysqli_fetch_row($in_result))
		{
			// record_id, style_id, layout_id
//			$stylesrecord_id = $stylesrecord_row[0];
			$style_id = $stylesrecord_row[1];
			$layout_id = $stylesrecord_row[2];
			$layout_style_table[$layout_id-1][$style_id-1] = true;
		}
	}
?>
