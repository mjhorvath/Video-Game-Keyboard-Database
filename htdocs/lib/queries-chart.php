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
	// HTML and SVG

	function selContribsGamesChart()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT c.author_id FROM contribs_games AS c WHERE c.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "resContribsGamesChart");
	}
	function resContribsGamesChart($in_result)
	{
		global $gamesrecord_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$gamesrecord_authors[] = getAuthorName($temp_row[0]);
		}
	}
	function selContribsStylesChart()
	{
		global $con, $stylesrecord_id;
		$selectString = "SELECT c.author_id FROM contribs_styles AS c WHERE c.record_id = " . $stylesrecord_id . ";";
		selectQuery($con, $selectString, "resContribsStylesChart");
	}
	function resContribsStylesChart($in_result)
	{
		global $stylesrecord_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$stylesrecord_authors[] = getAuthorName($temp_row[0]);
		}
	}
	function selContribsLayoutsChart()
	{
		global $con, $layout_id;
		$selectString = "SELECT c.author_id FROM contribs_layouts AS c WHERE c.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "resContribsLayoutsChart");
	}
	function resContribsLayoutsChart($in_result)
	{
		global $layout_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$layout_authors[] = getAuthorName($temp_row[0]);
		}
	}
	function selThisGamesIDChart()
	{
		global $con, $game_id;
		$selectString = "SELECT g.game_name, g.game_seourl FROM games AS g WHERE g.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "resThisGamesIDChart");
	}
	function resThisGamesIDChart($in_result)
	{
		global $game_name, $game_seo;
		$game_row = mysqli_fetch_row($in_result);
		if ($game_row)
		{
			// game_name, game_seourl
			$game_name = $game_row[0];
			$game_seo = $game_row[1];
		}
	}
	function selThisGameSEOChart()
	{
		global $con, $game_seo;
		$selectString = "SELECT g.game_name, g.game_id FROM games AS g WHERE g.game_seourl = \"" . $game_seo . "\";";
		selectQuery($con, $selectString, "resThisGameSEOChart");
	}
	function resThisGameSEOChart($in_result)
	{
		global $game_name, $game_id;
		$game_row = mysqli_fetch_row($in_result);
		if ($game_row)
		{
			// game_name, game_id
			$game_name = $game_row[0];
			$game_id = $game_row[1];
		}
	}
	function selAuthorsChart()
	{
		global $con;
		$selectString = "SELECT a.author_id, a.author_name FROM authors AS a;";
		selectQuery($con, $selectString, "resAuthorsChart");
	}
	function resAuthorsChart($in_result)
	{
		global $author_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id, author_name
			$author_table[$temp_row[0]-1] = $temp_row;
		}
	}
	function selStyleGroupsChart()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.stylegroup_name FROM stylegroups AS s;";
		selectQuery($con, $selectString, "resStyleGroupsChart");
	}
	function resStyleGroupsChart($in_result)
	{
		global $style_table, $stylegroup_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// stylegroup_id, stylegroup_name
			$style_table[$temp_row[0]-1] = [];
			$stylegroup_table[$temp_row[0]-1] = $temp_row;
		}
	}
	function selStylesChart()
	{
		global $con;
		$selectString = "SELECT s.style_id, s.style_name, s.style_whiteonblack, s.stylegroup_id FROM styles AS s ORDER BY s.stylegroup_id, s.style_name;";
		selectQuery($con, $selectString, "resStylesChart");
	}
	function resStylesChart($in_result)
	{
		global $style_table, $stylegroup_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// style_id, style_name, style_whiteonblack, stylegroup_id
			$style_group_1 = $temp_row[3];
			foreach ($stylegroup_table as $i => $stylegroup_value)
			{
				// stylegroup_id, stylegroup_name
				$style_group_2 = $stylegroup_value[0];
				if ($style_group_1 == $style_group_2)
				{
					$style_table[$i][] = $temp_row;
					// cannot do the following since "style_id" does not reset to zero for each "style_group"
//					$style_table[$i][$temp_row[0]-1] = $temp_row;
					break;
				}
			}
		}
	}
	function selThisStyleChart()
	{
		global $con, $style_id;
		$selectString = "SELECT s.style_filename, s.style_name, s.stylegroup_id FROM styles AS s WHERE s.style_id = " . $style_id . " ORDER BY s.stylegroup_id, s.style_name;";
		selectQuery($con, $selectString, "resThisStyleChart");
	}
	function resThisStyleChart($in_result)
	{
		global $style_filename, $style_name, $stylegroup_id;
		$style_row = mysqli_fetch_row($in_result);
		if ($style_row)
		{
			// style_filename, style_name, stylegroup_id
			$style_filename	= $style_row[0];
			$style_name	= $style_row[1];
			$stylegroup_id	= $style_row[2];
		}
	}
	function selThisFormatChart()
	{
		global $con, $format_id;
		$selectString = "SELECT f.format_name, f.format_enabled FROM formats AS f WHERE f.format_id = " . ($format_id + 1) . ";";
		selectQuery($con, $selectString, "resThisFormatChart");
	}
	function resThisFormatChart($in_result)
	{
		global $format_name;
		$format_row = mysqli_fetch_row($in_result);
		if ($format_row)
		{
			// format_name
			$format_name = $format_row[0];
		}
	}
	function selPositionsChart()
	{
		global $con, $layout_id;
		$selectString = "SELECT p.position_left, p.position_top, p.position_width, p.position_height, p.symbol_norm_low, p.symbol_norm_cap, p.symbol_altgr_low, p.symbol_altgr_cap, p.key_number, p.lowcap_optional, p.numpad FROM positions AS p WHERE p.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "resPositionsChart");
	}
	function resPositionsChart($in_result)
	{
		global $position_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// position_left, position_top, position_width, position_height, symbol_norm_low, symbol_norm_cap, symbol_altgr_low, symbol_altgr_cap, key_number, lowcap_optional, numpad
			$position_table[$temp_row[8]-1] = $temp_row;
		}
	}
	function selThisLayoutChart()
	{
		global $con, $layout_id;
		$selectString = "SELECT l.platform_id, l.layout_name, l.layout_keysnum, l.layout_fullsize_width, l.layout_fullsize_height, l.layout_tenkeyless_width, l.layout_tenkeyless_height FROM layouts AS l WHERE l.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "resThisLayoutChart");
	}
	function resThisLayoutChart($in_result)
	{
		global $platform_id, $layout_name, $layout_keysnum, $layout_fullsize_width, $layout_fullsize_height, $layout_tenkeyless_width, $layout_tenkeyless_height;
		$layout_row = mysqli_fetch_row($in_result);
		if ($layout_row)
		{
			// platform_id, layout_name, layout_keysnum, layout_fullsize_width, layout_fullsize_height, layout_tenkeyless_width, layout_tenkeyless_height
			$platform_id			= $layout_row[0];
			$layout_name			= $layout_row[1];
			$layout_keysnum			= $layout_row[2];
			$layout_fullsize_width		= $layout_row[3];
			$layout_fullsize_height		= $layout_row[4];
			$layout_tenkeyless_width	= $layout_row[5];
			$layout_tenkeyless_height	= $layout_row[6];
		}
	}
	function selThisPlatformChart()
	{
		global $con, $platform_id;
		$selectString = "SELECT p.platform_name FROM platforms AS p WHERE p.platform_id = " . $platform_id . ";";
		selectQuery($con, $selectString, "resThisPlatformChart");
	}
	function resThisPlatformChart($in_result)
	{
		global $platform_name;
		$platform_row = mysqli_fetch_row($in_result);
		if ($platform_row)
		{
			// platform_name
			$platform_name = $platform_row[0];
		}
	}
	function selThisGamesRecordChart()
	{
		global $con, $layout_id, $game_id;
		$selectString = "SELECT r.record_id FROM records_games AS r WHERE r.layout_id = " . $layout_id . " AND r.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "resThisGamesRecordChart");
	}
	function resThisGamesRecordChart($in_result)
	{
		global $gamesrecord_id;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		if ($gamesrecord_row)
		{
			// record_id
			$gamesrecord_id = $gamesrecord_row[0];
		}
	}
	function selThisStylesRecordChart()
	{
		global $con, $layout_id, $style_id;
		$selectString = "SELECT r.record_id FROM records_styles AS r WHERE r.layout_id = " . $layout_id . " AND r.style_id = " . $style_id . ";";
		selectQuery($con, $selectString, "resThisStylesRecordChart");
	}
	function resThisStylesRecordChart($in_result)
	{
		global $stylesrecord_id;
		$stylesrecord_row = mysqli_fetch_row($in_result);
		if ($stylesrecord_row)
		{
			// record_id
			$stylesrecord_id = $stylesrecord_row[0];
		}
	}
	function selThisGameLayoutChart()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT r.game_id, r.layout_id FROM records_games AS r WHERE r.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "resThisGameLayoutChart");
	}
	function resThisGameLayoutChart($in_result)
	{
		global $game_id, $layout_id;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		if ($gamesrecord_row)
		{
			// game_id, layout_id
			$game_id	= $gamesrecord_row[0];
			$layout_id	= $gamesrecord_row[1];
		}
	}
	// make sure the columns are synced with the TSV page of the submission form
	function selBindingsChart()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT b.normal_group, b.normal_action, b.shift_group, b.shift_action, b.ctrl_group, b.ctrl_action, b.alt_group, b.alt_action, b.altgr_group, b.altgr_action, b.extra_group, b.extra_action, b.image_file, b.image_uri, b.key_number FROM bindings AS b WHERE b.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "resBindingsChart");
	}
	function resBindingsChart($in_result)
	{
		global $binding_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// normal_group, normal_action, shift_group, shift_action, ctrl_group, ctrl_action, alt_group, alt_action, altgr_group, altgr_action, extra_group, extra_action, image_file, image_uri, key_number
			$binding_table[$temp_row[14]-1] = $temp_row;
		}
	}
	// make sure the columns are synced with the TSV page of the submission form
	function selLegendsChart()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT l.keygroup_id, l.legend_description FROM legends AS l WHERE l.record_id = " . $gamesrecord_id . " ORDER BY l.keygroup_id;";
		selectQuery($con, $selectString, "resLegendsChart");
	}
	function resLegendsChart($in_result)
	{
		global $legend_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keygroup_id, legend_description
			$legend_table[$temp_row[0]-1] = $temp_row;
		}
	}
	// make sure the columns are synced with the TSV page of the submission form
	function selCommandsChart()
	{
		global $con, $gamesrecord_id;
		$selectString =	"SELECT c.commandtype_id, c.command_text, c.command_description, c.keygroup_id FROM commands AS c WHERE c.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "resCommandsChart");
	}
	// it might be better to only have one larger table for all of these commands
	// it might make it a little easier to expand the number of command types in the future
	// also not sure if the counters are strictly necessary since it's very easy to get the length of each table
	function resCommandsChart($in_result)
	{
		global	$commandouter_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// commandtype_id, command_text, command_description, keygroup_id
			$commandouter_table[$temp_row[0]-1][] = $temp_row;
		}
	}
	function selCommandLabelsChart()
	{
		global $con, $layout_language;
		$selectString =	"SELECT l.commandtype_id, l.commandlabel_text, t.commandtype_abbrv, t.commandtype_input, t.commandtype_keygroup
				FROM commandlabels AS l, commandtypes AS t
				WHERE l.language_id = " . $layout_language . "
				AND l.commandtype_id = t.commandtype_id;";
		selectQuery($con, $selectString, "resCommandLabelsChart");
	}
	function resCommandLabelsChart($in_result)
	{
		global $commandlabel_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// commandtype_id, commandlabel_text, commandtype_abbrv, commandtype_input, commandtype_keygroup
			$commandlabel_table[$temp_row[0]-1] = $temp_row;
		}
	}
	function selThisLanguageStringsChart()
	{
		global $con, $layout_language;
		$selectString = "SELECT l.language_code, l.language_title, l.language_description, l.language_keywords, l.language_legend FROM languages AS l WHERE l.language_id = " . $layout_language . ";";
		selectQuery($con, $selectString, "resThisLanguageStringsChart");
	}
	function resThisLanguageStringsChart($in_result)
	{
		global $language_code, $language_title, $language_description, $language_keywords, $language_legend;
		$temp_row = mysqli_fetch_row($in_result);
		if ($temp_row)
		{
			// language_code, language_title, language_description, language_keywords, language_legend
			$language_code		= $temp_row[0];
			$language_title		= $temp_row[1];
			$language_description	= $temp_row[2];
			$language_keywords	= $temp_row[3];
			$language_legend	= $temp_row[4];
		}
	}
	// has a "front" counterpart, may not be needed here at all
	function selPlatformsChart()
	{
		global $con;
		$selectString = "SELECT p.platform_id, p.platform_name, p.sortorder_id FROM platforms AS p;";
		selectQuery($con, $selectString, "resPlatformsChart");
	}
	function resPlatformsChart($in_result)
	{
		global $platform_table;
		while ($platform_row = mysqli_fetch_row($in_result))
		{
			// platform_id, platform_name, sortorder_id
			$platform_id = $platform_row[0];
			$platform_table[$platform_id-1] = $platform_row;
		}
	}
	// has a "front" counterpart, may not be needed here at all
	function selLayoutsChart()
	{
		global $con;
		$selectString = "SELECT l.platform_id, l.layout_id, l.layout_name FROM layouts AS l;";
		selectQuery($con, $selectString, "resLayoutsChart");
	}
	function resLayoutsChart($in_result)
	{
		global $layout_table;
		while ($layout_row = mysqli_fetch_row($in_result))
		{
			// platform_id, layout_id, layout_name
			$layout_id = $layout_row[0];
			$layout_table[$layout_id-1] = $layout_row;
		}
	}
	function selKeyStylesChart()
	{
		global $con, $stylesrecord_id;
		$selectString = "SELECT k.keygroup_id, k.key_number FROM keystyles AS k WHERE k.record_id = " . $stylesrecord_id . ";";
		selectQuery($con, $selectString, "resKeyStylesChart");
	}
	function resKeyStylesChart($in_result)
	{
		global $keystyle_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keygroup_id, key_number
			$keystyle_table[$temp_row[1]-1] = $temp_row;
		}
	}
	// non!
	function selKeyStyleClassesChart()
	{
		global $con;
		$selectString = "SELECT k.keygroup_id, k.keygroup_class FROM keygroups_static AS k;";
		selectQuery($con, $selectString, "resKeyStyleClassesChart");
	}
	function resKeyStyleClassesChart($in_result)
	{
		global $binding_class_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keygroup_id, keygroup_class
			$binding_class_table[] = $temp_row;
		}
	}
	// non!
	// order!
	function selLegendColorsChart()
	{
		global $con;
		$selectString = "SELECT k.keygroup_id, k.keygroup_class, k.sortorder_id FROM keygroups_dynamic AS k ORDER BY k.sortorder_id;";
		selectQuery($con, $selectString, "resLegendColorsChart");
	}
	function resLegendColorsChart($in_result)
	{
		global $binding_color_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keygroup_id, keygroup_class, sortorder_id
			$binding_color_table[] = $temp_row;
		}
	}
?>
