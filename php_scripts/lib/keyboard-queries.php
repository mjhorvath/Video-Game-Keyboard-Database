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
	// General
	function selectQuery(&$connection, $query_string, $dofunction)
	{
		$first_result = true;
		if (mysqli_multi_query($connection, $query_string))
		{
			do
			{
				$query_result = mysqli_store_result($connection);
				if ($query_result)
				{
					if ($first_result)
					{
						call_user_func($dofunction, $query_result);
						$first_result = false;
					}
					mysqli_free_result($query_result);
				}
//				else
//				{
//					printf("Error: %s<br/>", mysqli_error($connection));
//				}
				$query_result = null;
			} while(mysqli_more_results($connection) && mysqli_next_result($connection));
		}
	}

	// ---------------------------------------------------------------------
	// Frontend
	function selGenresFront()
	{
		global $con;
		$selectString = "SELECT g.genre_id, g.genre_name FROM genres as g;";
		selectQuery($con, $selectString, "doGenresFront");
	}
	function selGamesFront()
	{
		global $con;
		$selectString = "SELECT g.genre_id, g.game_id, g.game_name, g.game_friendlyurl FROM games as g;";
		selectQuery($con, $selectString, "doGamesFront");
	}
	function selStylegroupsFront()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.stylegroup_name FROM stylegroups as s;";
		selectQuery($con, $selectString, "doStylegroupsFront");
	}
	function selStylesFront()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.style_id, s.style_name FROM styles as s ORDER BY s.style_name;";
		selectQuery($con, $selectString, "doStylesFront");
	}
	function selPlatformsFront()
	{
		global $con;
		$selectString = "SELECT p.platform_id, p.platform_name, p.platform_displayorder FROM platforms as p;";
		selectQuery($con, $selectString, "doPlatformsFront");
	}
	function selLayoutsFront()
	{
		global $con;
		$selectString = "SELECT l.platform_id, l.layout_id, l.layout_name FROM layouts as l ORDER BY l.layout_name;";
		selectQuery($con, $selectString, "doLayoutsFront");
	}
	function doGenresFront($in_result)
	{
		global $genre_array, $genre_order_array, $game_array;
		while ($genre_row = mysqli_fetch_row($in_result))
		{
			// genre_id, genre_name
			$genre_id = $genre_row[0];
			$genre_array[$genre_id-1] = $genre_row[1];
			$game_array[$genre_id-1] = [[],[]];
		}
	}
	function doGamesFront($in_result)
	{
		global $game_array;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// genre_id, game_id, game_name, game_friendlyurl
			$genre_id = $game_row[0];
			$game_array[$genre_id-1][0][] = $game_row[1];
			$game_array[$genre_id-1][1][] = $game_row[2];
			$game_array[$genre_id-1][2][] = $game_row[3];
		}
	}
	function doStylegroupsFront($in_result)
	{
		global $stylegroup_array, $style_array;
		while ($stylegroup_row = mysqli_fetch_row($in_result))
		{
			// stylegroup_id, stylegroup_name
			$stylegroup_id = $stylegroup_row[0];
			$stylegroup_array[$stylegroup_id-1] = $stylegroup_row[1];
			$style_array[$stylegroup_id-1] = [[],[]];
		}
	}
	function doStylesFront($in_result)
	{
		global $style_array;
		while ($style_row = mysqli_fetch_row($in_result))
		{
			// stylegroup_id, style_id, style_name
			$stylegroup_id = $style_row[0];
			$style_array[$stylegroup_id-1][0][] = $style_row[1];
			$style_array[$stylegroup_id-1][1][] = $style_row[2];
		}
	}
	function doPlatformsFront($in_result)
	{
		global $platform_array, $layout_array;
		while ($platform_row = mysqli_fetch_row($in_result))
		{
			// platform_id, platform_name, platform_displayorder
			$platform_id = $platform_row[0];
			$platform_name = $platform_row[1];
			$platform_displayorder = $platform_row[2];
			$platform_array[$platform_id-1] = $platform_name;
			$layout_array[$platform_id-1] = [[],[]];
			$platform_order_array[$platform_id-1] = $platform_displayorder;
		}
		array_multisort($platform_order_array, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $platform_array, $layout_array);
	}
	function doLayoutsFront($in_result)
	{
		global $layout_array;
		while ($layout_row = mysqli_fetch_row($in_result))
		{
			// platform_id, layout_id, layout_name
			$platform_id = $layout_row[0];
			$layout_array[$platform_id-1][0][] = $layout_row[1];
			$layout_array[$platform_id-1][1][] = $layout_row[2];
		}
	}

	// ---------------------------------------------------------------------
	// HTML
	function selContribGamesHTML()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT c.author_id FROM contrib_games as c WHERE c.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "doContribGamesHTML");
	}
	function selContribStylesHTML()
	{
		global $con, $stylesrecord_id;
		$selectString = "SELECT c.author_id FROM contrib_styles as c WHERE c.record_id = " . $stylesrecord_id . ";";
		selectQuery($con, $selectString, "doContribStylesHTML");
	}
	function selContribLayoutsHTML()
	{
		global $con, $layout_id;
		$selectString = "SELECT c.author_id FROM contrib_layouts as c WHERE c.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doContribLayoutsHTML");
	}
	function selGamesHTML_ID()
	{
		global $con, $game_id;
		$selectString = "SELECT g.game_name, g.game_friendlyurl FROM games AS g WHERE g.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "doGamesHTML_ID");
	}
	function selGamesHTML_SEO()
	{
		global $con, $game_seo;
		$selectString = "SELECT g.game_name, g.game_id FROM games AS g WHERE g.game_friendlyurl = \"" . $game_seo . "\";";
		selectQuery($con, $selectString, "doGamesHTML_SEO");
	}
	function selAuthorsHTML()
	{
		global $con;
		$selectString = "SELECT a.author_id, a.author_name FROM authors AS a;";
		selectQuery($con, $selectString, "doAuthorsHTML");
	}
	function selStyleGroupsHTML()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.stylegroup_name FROM stylegroups AS s;";
		selectQuery($con, $selectString, "doStyleGroupsHTML");
	}
	function selStylesHTML()
	{
		global $con;
		$selectString = "SELECT s.style_id, s.style_name, s.style_whiteonblack, s.stylegroup_id FROM styles AS s ORDER BY s.stylegroup_id, s.style_name;";
		selectQuery($con, $selectString, "doStylesHTML");
	}
	function selThisStyleHTML()
	{
		global $con, $style_id;
		$selectString = "SELECT s.style_filename, s.style_name, s.stylegroup_id FROM styles AS s WHERE s.style_id = " . $style_id . " ORDER BY s.stylegroup_id, s.style_name;";
		selectQuery($con, $selectString, "doThisStyleHTML");
	}
	function selPositionsHTML()
	{
		global $con, $layout_id;
		$selectString = "SELECT p.position_left, p.position_top, p.position_width, p.position_height, p.symbol_norm_low, p.symbol_norm_cap, p.symbol_altgr_low, p.symbol_altgr_cap, p.key_number, p.lowcap_optional FROM positions AS p WHERE p.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doPositionsHTML");
	}
	function selLayoutsHTML()
	{
		global $con, $layout_id;
		$selectString = "SELECT l.platform_id, l.layout_name, l.layout_keysnum, l.layout_fullsize_width, l.layout_fullsize_height, l.layout_tenkeyless_width, l.layout_tenkeyless_height, l.layout_language, l.layout_description FROM layouts AS l WHERE l.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doLayoutsHTML");
	}
	function selPlatformsHTML()
	{
		global $con, $platform_id;
		$selectString = "SELECT p.platform_name FROM platforms AS p WHERE p.platform_id = " . $platform_id . ";";
		selectQuery($con, $selectString, "doPlatformsHTML");
	}
	function selGamesRecordsHTML()
	{
		global $con, $layout_id, $game_id;
		$selectString = "SELECT r.record_id FROM records_games AS r WHERE r.layout_id = " . $layout_id . " AND r.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "doGamesRecordsHTML");
	}
	function selStylesRecordsHTML()
	{
		global $con, $layout_id, $style_id;
		$selectString = "SELECT r.record_id FROM records_styles AS r WHERE r.layout_id = " . $layout_id . " AND r.style_id = " . $style_id . ";";
		selectQuery($con, $selectString, "doStylesRecordsHTML");
	}
	function selKeystylesHTML()
	{
		global $con, $stylesrecord_id;
		$selectString = "SELECT k.keystyle_group, k.key_number FROM keystyles AS k WHERE k.record_id = " . $stylesrecord_id . ";";
		selectQuery($con, $selectString, "doKeystylesHTML");
	}
	function selBindingsHTML()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT b.normal_group, b.normal_action, b.shift_group, b.shift_action, b.ctrl_group, b.ctrl_action, b.alt_group, b.alt_action, b.altgr_group, b.altgr_action, b.extra_group, b.extra_action, b.image_file, b.image_uri, b.key_number FROM bindings AS b WHERE b.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "doBindingsHTML");
	}
	function selLegendsHTML()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT l.legend_group, l.legend_description FROM legends AS l WHERE l.record_id = " . $gamesrecord_id . " ORDER BY l.legend_group;";
		selectQuery($con, $selectString, "doLegendsHTML");
	}
	function selCommandsHTML()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT c.commandtype_id, c.command_text, c.command_description FROM commands AS c WHERE c.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "doCommandsHTML");
	}
	function doContribGamesHTML($in_result)
	{
		global $gamesrecord_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$gamesrecord_authors[] = cleantextHTML(getAuthorName($temp_row[0]));
		}
	}
	function doContribStylesHTML($in_result)
	{
		global $stylesrecord_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$stylesrecord_authors[] = cleantextHTML(getAuthorName($temp_row[0]));
		}
	}
	function doContribLayoutsHTML($in_result)
	{
		global $layout_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$layout_authors[] = cleantextHTML(getAuthorName($temp_row[0]));
		}
	}
	function doStyleGroupsHTML($in_result)
	{
		global $style_table, $stylegroup_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			$style_table[] = [];
			// stylegroup_id, stylegroup_name
			$stylegroup_table[] = $temp_row;
		}
	}
	function doStylesHTML($in_result)
	{
		global $style_table, $stylegroup_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// style_id, style_name, style_whiteonblack, stylegroup_id
			$style_group_1 = $temp_row[3];
			for ($i = 0; $i < count($stylegroup_table); $i++)
			{
				$style_group_2 = $stylegroup_table[$i][0];
				if ($style_group_1 == $style_group_2)
				{
					$style_table[$i][] = $temp_row;
					break;
				}
			}
		}
	}
	function doThisStyleHTML($in_result)
	{
		global $style_filename, $style_name, $stylegroup_id;
		$style_row = mysqli_fetch_row($in_result);
		// style_filename, style_name, stylegroup_id
		$style_filename = $style_row[0];
		$style_name = cleantextHTML($style_row[1]);
		$stylegroup_id = $style_row[2];
	}
	function doBindingsHTML($in_result)
	{
		global $binding_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// normal_group, normal_action, shift_group, shift_action, ctrl_group, ctrl_action, alt_group, alt_action, altgr_group, altgr_action, extra_group, extra_action, image_file, image_uri, key_number
			$binding_table[$temp_row[14]-1] = $temp_row;
		}
	}
	function doLegendsHTML($in_result)
	{
		global $legend_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// legend_group, legend_description
			$legend_table[] = $temp_row;
		}
	}
	function doCommandsHTML($in_result)
	{
		global $combo_table, $mouse_table, $joystick_table, $note_table, $cheat_table, $console_table, $emote_table, $combo_count, $mouse_count, $joystick_count, $note_count, $cheat_count, $console_count, $emote_count;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// commandtype_id, command_text, command_description
			$temp_array = [$temp_row[1], $temp_row[2]];
			switch ($temp_row[0])
			{
				case (1):
					$combo_table[] = $temp_array;
					$combo_count += 1;
				break;
				case (2):
					$mouse_table[] = $temp_array;
					$mouse_count += 1;
				break;
				case (3):
					$joystick_table[] = $temp_array;
					$joystick_count += 1;
				break;
				case (4):
					$note_table[] = $temp_array;
					$note_count += 1;
				break;
				case (5):
					$cheat_table[] = $temp_array;
					$cheat_count += 1;
				break;
				case (6):
					$console_table[] = $temp_array;
					$console_count += 1;
				break;
				case (7):
					$emote_table[] = $temp_array;
					$emote_count += 1;
				break;
			}
		}
	}
	function doPositionsHTML($in_result)
	{
		global $position_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// position_left, position_top, position_width, position_height, symbol_norm_low, symbol_norm_cap, symbol_altgr_low, symbol_altgr_cap, key_number, lowcap_optional
			$position_table[$temp_row[8]-1] = $temp_row;
		}
	}
	function doKeystylesHTML($in_result)
	{
		global $keystyle_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keystyle_group, key_number
			$keystyle_table[$temp_row[1]-1] = $temp_row;
		}
	}
	function doGamesHTML_ID($in_result)
	{
		global $game_name, $game_seo;
		$game_row = mysqli_fetch_row($in_result);
		// game_name, game_friendlyurl
		$game_name = cleantextHTML($game_row[0]);
		$game_seo = $game_row[1];
	}
	function doGamesHTML_SEO($in_result)
	{
		global $game_name, $game_id;
		$game_row = mysqli_fetch_row($in_result);
		// game_name, game_id
		$game_name = cleantextHTML($game_row[0]);
		$game_id = intval($game_row[1]);
	}
	function doPlatformsHTML($in_result)
	{
		global $platform_name;
		$platform_row = mysqli_fetch_row($in_result);
		// platform_name
		$platform_name = cleantextHTML($platform_row[0]);
	}
	function doGamesRecordsHTML($in_result)
	{
		global $gamesrecord_id;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		// record_id
		$gamesrecord_id = $gamesrecord_row[0];
	}
	function doStylesRecordsHTML($in_result)
	{
		global $stylesrecord_id;
		$stylesrecord_row = mysqli_fetch_row($in_result);
		// record_id
		$stylesrecord_id = $stylesrecord_row[0];
	}
	// need to move the stuff that used to be here to `doLanguages`
	function doLayoutsHTML($in_result)
	{
		global $gamesrecord_id, $platform_id, $layout_name, $layout_keysnum, $layout_fullsize_width, $layout_fullsize_height, $layout_tenkeyless_width, $layout_tenkeyless_height, $layout_language, $layout_description;
		// platform_id, layout_name, layout_keysnum, layout_fullsize_width, layout_fullsize_height, layout_tenkeyless_width, layout_tenkeyless_height, layout_language
		$layout_row		= mysqli_fetch_row($in_result);
		$platform_id		= $layout_row[0];
		$layout_name		= cleantextHTML($layout_row[1]);
		$layout_keysnum		= $layout_row[2];
		if ($gamesrecord_id > 0)
		{
			$layout_fullsize_width		= $layout_row[3];
			$layout_fullsize_height		= $layout_row[4];
			$layout_tenkeyless_width	= $layout_row[5];
			$layout_tenkeyless_height	= $layout_row[6];
		}
		$layout_language	= $layout_row[7];
		$layout_description	= $layout_row[8];
	}
	function doAuthorsHTML($in_result)
	{
		global $author_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id, author_name
			$author_table[] = $temp_row;
		}
	}

	// ---------------------------------------------------------------------
	// SVG
	function selContribGamesSVG()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT c.author_id FROM contrib_games as c WHERE c.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "doContribGamesSVG");
	}
	function selContribStylesSVG()
	{
		global $con, $stylesrecord_id;
		$selectString = "SELECT c.author_id FROM contrib_styles as c WHERE c.record_id = " . $stylesrecord_id . ";";
		selectQuery($con, $selectString, "doContribStylesSVG");
	}
	function selContribLayoutsSVG()
	{
		global $con, $layout_id;
		$selectString = "SELECT c.author_id FROM contrib_layouts as c WHERE c.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doContribLayoutsSVG");
	}
	function selGamesSVG_ID()
	{
		global $con, $game_id;
		$selectString = "SELECT g.game_name, g.game_friendlyurl FROM games AS g WHERE g.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "doGamesSVG_ID");
	}
	function selGamesSVG_SEO()
	{
		global $con, $game_seo;
		$selectString = "SELECT g.game_name, g.game_id FROM games AS g WHERE g.game_friendlyurl = \"" . $game_seo . "\";";
		selectQuery($con, $selectString, "doGamesSVG_SEO");
	}
	function selAuthorsSVG()
	{
		global $con;
		$selectString = "SELECT a.author_id, a.author_name FROM authors AS a;";
		selectQuery($con, $selectString, "doAuthorsSVG");
	}
	function selStyleGroupsSVG()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.stylegroup_name FROM stylegroups AS s;";
		selectQuery($con, $selectString, "doStyleGroupsSVG");
	}
	function selStylesSVG()
	{
		global $con;
		$selectString = "SELECT s.style_id, s.style_name, s.style_whiteonblack, s.stylegroup_id FROM styles AS s ORDER BY s.stylegroup_id, s.style_name;";
		selectQuery($con, $selectString, "doStylesSVG");
	}
	function selThisStyleSVG()
	{
		global $con, $style_id;
		$selectString = "SELECT s.style_filename, s.style_name, s.stylegroup_id FROM styles AS s WHERE s.style_id = " . $style_id . " ORDER BY s.stylegroup_id, s.style_name;";
		selectQuery($con, $selectString, "doThisStyleSVG");
	}
	function selPositionsSVG()
	{
		global $con, $layout_id;
		$selectString = "SELECT p.position_left, p.position_top, p.position_width, p.position_height, p.symbol_norm_low, p.symbol_norm_cap, p.symbol_altgr_low, p.symbol_altgr_cap, p.key_number, p.lowcap_optional, p.numpad FROM positions AS p WHERE p.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doPositionsSVG");
	}
	function selLayoutsSVG()
	{
		global $con, $layout_id;
		$selectString = "SELECT l.platform_id, l.layout_name, l.layout_keysnum, l.layout_fullsize_width, l.layout_fullsize_height, l.layout_tenkeyless_width, l.layout_tenkeyless_height FROM layouts AS l WHERE l.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doLayoutsSVG");
	}
	function selPlatformsSVG()
	{
		global $con, $platform_id;
		$selectString = "SELECT p.platform_name FROM platforms AS p WHERE p.platform_id = " . $platform_id . ";";
		selectQuery($con, $selectString, "doPlatformsSVG");
	}
	function selGamesRecordsSVG()
	{
		global $con, $layout_id, $game_id;
		$selectString = "SELECT r.record_id FROM records_games AS r WHERE r.layout_id = " . $layout_id . " AND r.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "doGamesRecordsSVG");
	}
	function selStylesRecordsSVG()
	{
		global $con, $layout_id, $style_id;
		$selectString = "SELECT r.record_id FROM records_styles AS r WHERE r.layout_id = " . $layout_id . " AND r.style_id = " . $style_id . ";";
		selectQuery($con, $selectString, "doStylesRecordsSVG");
	}
	function selKeystylesSVG()
	{
		global $con, $stylesrecord_id;
		$selectString = "SELECT k.keystyle_group, k.key_number FROM keystyles AS k WHERE k.record_id = " . $stylesrecord_id . ";";
		selectQuery($con, $selectString, "doKeystylesSVG");
	}
	function selBindingsSVG()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT b.normal_group, b.normal_action, b.shift_group, b.shift_action, b.ctrl_group, b.ctrl_action, b.alt_group, b.alt_action, b.altgr_group, b.altgr_action, b.extra_group, b.extra_action, b.image_file, b.image_uri, b.key_number FROM bindings AS b WHERE b.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "doBindingsSVG");
	}
	function selLegendsSVG()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT l.legend_group, l.legend_description FROM legends AS l WHERE l.record_id = " . $gamesrecord_id . " ORDER BY l.legend_group;";
		selectQuery($con, $selectString, "doLegendsSVG");
	}
	function doContribGamesSVG($in_result)
	{
		global $gamesrecord_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$gamesrecord_authors[] = cleantextSVG(getAuthorName($temp_row[0]));
		}
	}
	function doContribStylesSVG($in_result)
	{
		global $stylesrecord_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$stylesrecord_authors[] = cleantextSVG(getAuthorName($temp_row[0]));
		}
	}
	function doContribLayoutsSVG($in_result)
	{
		global $layout_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$layout_authors[] = cleantextSVG(getAuthorName($temp_row[0]));
		}
	}
	function doStyleGroupsSVG($in_result)
	{
		global $style_table, $stylegroup_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			$style_table[] = [];
			// stylegroup_id, stylegroup_name
			$stylegroup_table[] = $temp_row;
		}
	}
	function doStylesSVG($in_result)
	{
		global $style_table, $stylegroup_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// style_id, style_name, style_whiteonblack, stylegroup_id
			$style_group_1 = $temp_row[3];
			for ($i = 0; $i < count($stylegroup_table); $i++)
			{
				$style_group_2 = $stylegroup_table[$i][0];
				if ($style_group_1 == $style_group_2)
				{
					$style_table[$i][] = $temp_row;
					break;
				}
			}
		}
	}
	function doThisStyleSVG($in_result)
	{
		global $style_filename, $style_name, $stylegroup_id;
		$style_row = mysqli_fetch_row($in_result);
		// style_filename, style_name, stylegroup_id
		$style_filename = $style_row[0];
		$style_name = cleantextSVG($style_row[1]);
		$stylegroup_id = $style_row[2];
	}
	function doBindingsSVG($in_result)
	{
		global $binding_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// normal_group, normal_action, shift_group, shift_action, ctrl_group, ctrl_action, alt_group, alt_action, altgr_group, altgr_action, extra_group, extra_action, image_file, image_uri, key_number
			$binding_table[$temp_row[14]-1] = $temp_row;
		}
	}
	function doLegendsSVG($in_result)
	{
		global $legend_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// legend_group, legend_description
			$legend_table[] = $temp_row;
		}
	}
	function doPositionsSVG($in_result)
	{
		global $position_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// position_left, position_top, position_width, position_height, symbol_norm_low, symbol_norm_cap, symbol_altgr_low, symbol_altgr_cap, key_number, lowcap_optional, numpad
			$position_table[$temp_row[8]-1] = $temp_row;
		}
	}
	function doKeystylesSVG($in_result)
	{
		global $keystyle_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keystyle_group, key_number
			$keystyle_table[$temp_row[1]-1] = $temp_row;
		}
	}
	function doGamesSVG_ID($in_result)
	{
		global $game_name, $game_seo;
		$game_row = mysqli_fetch_row($in_result);
		// game_name, game_friendlyurl
		$game_name = cleantextSVG($game_row[0]);
		$game_seo = $game_row[1];
	}
	function doGamesSVG_SEO($in_result)
	{
		global $game_name, $game_id;
		$game_row = mysqli_fetch_row($in_result);
		// game_name, game_id
		$game_name = cleantextSVG($game_row[0]);
		$game_id = intval($game_row[1]);
	}
	function doPlatformsSVG($in_result)
	{
		global $platform_name;
		$platform_row = mysqli_fetch_row($in_result);
		// platform_name
		$platform_name = cleantextSVG($platform_row[0]);
	}
	function doGamesRecordsSVG($in_result)
	{
		global $gamesrecord_id;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		// record_id
		$gamesrecord_id = $gamesrecord_row[0];
	}
	function doStylesRecordsSVG($in_result)
	{
		global $stylesrecord_id;
		$stylesrecord_row = mysqli_fetch_row($in_result);
		// record_id
		$stylesrecord_id = $stylesrecord_row[0];
	}
	function doLayoutsSVG($in_result)
	{
		global $gamesrecord_id, $platform_id, $layout_name, $layout_keysnum, $layout_fullsize_width, $layout_fullsize_height, $layout_tenkeyless_width, $layout_tenkeyless_height;
		// platform_id, layout_name, layout_keysnum, layout_fullsize_width, layout_fullsize_height, layout_tenkeyless_width, layout_tenkeyless_height
		$layout_row		= mysqli_fetch_row($in_result);
		$platform_id		= $layout_row[0];
		$layout_name		= cleantextSVG($layout_row[1]);
		$layout_keysnum		= $layout_row[2];
		if ($gamesrecord_id > 0)
		{
			$layout_fullsize_width		= $layout_row[3];
			$layout_fullsize_height		= $layout_row[4];
			$layout_tenkeyless_width	= $layout_row[5];
			$layout_tenkeyless_height	= $layout_row[6];
		}
	}
	function doAuthorsSVG($in_result)
	{
		global $author_table;
		while ($author_row = mysqli_fetch_row($in_result))
		{
			// author_id, author_name
			$author_table[] = $author_row;
		}
	}

	// ---------------------------------------------------------------------
	// List
	function selGenresList()
	{
		global $con;
		$selectString = "SELECT g.genre_id, g.genre_name FROM genres as g ORDER BY g.genre_id;";
		selectQuery($con, $selectString, "doGenresList");
	}
	function selGamesList()
	{
		global $con;
		$selectString = "SELECT g.genre_id, g.game_id, g.game_name, g.game_friendlyurl FROM games as g ORDER BY g.game_name;";
		selectQuery($con, $selectString, "doGamesList");
	}
	function selLayoutsList()
	{
		global $con;
		$selectString = "SELECT l.layout_id, l.layout_name, l.platform_id FROM layouts as l ORDER BY l.layout_name;";
		selectQuery($con, $selectString, "doLayoutsList");
	}
	function selGamesRecordsList()
	{
		global $con;
		$selectString = "SELECT r.game_id, r.layout_id FROM records_games as r;";
		selectQuery($con, $selectString, "doGamesRecordsList");
	}
	function selPlatformsList()
	{
		global $con;
		$selectString = "SELECT p.platform_id, p.platform_name, p.platform_abbv FROM platforms as p ORDER BY p.platform_name;";
		selectQuery($con, $selectString, "doPlatformsList");
	}
	function doGenresList($in_result)
	{
		global $genre_array;
		while ($genre_row = mysqli_fetch_row($in_result))
		{
			// genre_id, genre_name, genre_displayorder
			$genre_array[] = $genre_row[1];
		}
	}
	function doGamesList($in_result)
	{
		global $game_genre_array, $game_index_array, $game_name_array, $game_seourl_array;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// genre_id, game_id, game_name, game_friendlyurl
			$game_genre_array[] = $game_row[0];
			$game_index_array[] = str_pad($game_row[1], 3, '0', STR_PAD_LEFT);
			$game_name_array[] = $game_row[2];
			$game_seourl_array[] = $game_row[3];
		}
		array_multisort($game_name_array, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $game_genre_array, $game_index_array, $game_seourl_array);
	}
	function doLayoutsList($in_result)
	{
		global $layout_array;
		while ($layout_row = mysqli_fetch_row($in_result))
		{
			// layout_id, layout_name, platform_id
			$layout_array[] = $layout_row;
		}
	}
	function doGamesRecordsList($in_result)
	{
		global $record_array;
		while ($record_row = mysqli_fetch_row($in_result))
		{
			// game_id, layout_id
			$record_array[] = $record_row;
		}
	}
	function doPlatformsList($in_result)
	{
		global $platform_array;
		while ($platform_row = mysqli_fetch_row($in_result))
		{
			// platform_id, platform_name, platform_abbv
			$platform_array[] = $platform_row;
		}
	}

	// ---------------------------------------------------------------------
	// JS
	function selGamesAutoincJS()
	{
		global $con;
		$selectString = "SELECT MAX(g.game_id) FROM games AS g;";
		selectQuery($con, $selectString, "doGamesAutoincJS");
	}
	function selLayoutsAutoincJS()
	{
		global $con;
		$selectString = "SELECT MAX(l.layout_id) FROM layouts AS l;";
		selectQuery($con, $selectString, "doLayoutsAutoincJS");
	}
	function selStylesAutoincJS()
	{
		global $con;
		$selectString = "SELECT MAX(s.style_id) FROM styles AS s;";
		selectQuery($con, $selectString, "doStylesAutoincJS");
	}
	function selSeourlJS()
	{
		global $con;
		$selectString = "SELECT g.game_id, g.game_friendlyurl FROM games AS g;";
		selectQuery($con, $selectString, "doSeourlJS");
	}
	function selGameRecordsJS()
	{
		global $con;
		$selectString = "SELECT r.record_id, r.game_id, r.layout_id FROM records_games AS r;";
		selectQuery($con, $selectString, "doGameRecordsJS");
	}
	function selStyleRecordsJS()
	{
		global $con;
		$selectString = "SELECT r.record_id, r.style_id, r.layout_id FROM records_styles AS r;";
		selectQuery($con, $selectString, "doStyleRecordsJS");
	}
	function doGamesAutoincJS($in_result)
	{
		global $games_max;
		$game_row = mysqli_fetch_row($in_result);
		$games_max = $game_row[0];
	}
	function doLayoutsAutoincJS($in_result)
	{
		global $layouts_max;
		$layout_row = mysqli_fetch_row($in_result);
		$layouts_max = $layout_row[0];
	}
	function doStylesAutoincJS($in_result)
	{
		global $styles_max;
		$style_row = mysqli_fetch_row($in_result);
		$styles_max = $style_row[0];
	}
	function doSeourlJS($in_result)
	{
		global $seourl_table;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// game_id, game_friendlyurl
			$game_id = $game_row[0];
			$game_seo = $game_row[1];
			$seourl_table[$game_id-1] = $game_seo;
		}
	}
	function doGameRecordsJS($in_result)
	{
		global $game_table;
		while ($gamesrecord_row = mysqli_fetch_row($in_result))
		{
			// record_id, game_id, layout_id
//			$gamesrecord_id = $gamesrecord_row[0];
			$game_id = $gamesrecord_row[1];
			$layout_id = $gamesrecord_row[2];
			$game_table[$layout_id-1][$game_id-1] = true;
		}
	}
	function doStyleRecordsJS($in_result)
	{
		global $style_table;
		while ($stylesrecord_row = mysqli_fetch_row($in_result))
		{
			// record_id, style_id, layout_id
//			$stylesrecord_id = $stylesrecord_row[0];
			$style_id = $stylesrecord_row[1];
			$layout_id = $stylesrecord_row[2];
			$style_table[$layout_id-1][$style_id-1] = true;
		}
	}
?>
