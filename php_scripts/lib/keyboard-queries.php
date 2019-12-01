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
				else
				{
					printf("Error: %s<br/>", mysqli_error($connection));
				}
				$query_result = null;
			} while(mysqli_more_results($connection) && mysqli_next_result($connection));
		}
	}


	// ---------------------------------------------------------------------
	// All

	function selURLQueries()
	{
		global $con;
		$selectString =	"SELECT u.entity_id, u.entity_name, u.entity_default FROM urlqueries AS u;";
		selectQuery($con, $selectString, "resURLQueries");
	}
	function resURLQueries($in_result)
	{
		global $urlqueries_array;
		while ($query_row = mysqli_fetch_row($in_result))
		{
			// entity_id, entity_name, entity_default
			$urlqueries_array[] = [$query_row[0], $query_row[1], $query_row[2]];
		}
	}
	function selDefaults()
	{
		global $con, $urlqueries_array;
		// array indices are hardcoded here. should get them from database instead
		$selectString =	"SELECT g.game_name, g.game_friendlyurl, l.layout_name, s.style_name, f.format_name
				FROM games AS g, layouts AS l, styles AS s, formats AS f
				WHERE g.game_id = "	. $urlqueries_array[0][2] . "
				AND l.layout_id = "	. $urlqueries_array[1][2] . "
				AND s.style_id = "	. $urlqueries_array[2][2] . "
				AND f.format_id = "	. $urlqueries_array[3][2] . ";";
		selectQuery($con, $selectString, "resDefaults");
	}
	function resDefaults($in_result)
	{
		global	$urlqueries_array,
			$default_game_id,	$default_game_name,	$default_game_seo,
			$default_layout_id,	$default_layout_name,
			$default_style_id,	$default_style_name,
			$default_format_id,	$default_format_name,
			$default_ten_bool;
		$game_row = mysqli_fetch_row($in_result);
		$default_game_id	= $urlqueries_array[0][2];
		$default_game_name	= $game_row[0];
		$default_game_seo	= $game_row[1];
		$default_layout_id	= $urlqueries_array[1][2];
		$default_layout_name	= $game_row[2];
		$default_style_id	= $urlqueries_array[2][2];
		$default_style_name	= $game_row[3];
		$default_format_id	= $urlqueries_array[3][2];		// starts at 0 instead of 1
		$default_format_name	= $game_row[4];
		$default_ten_bool	= $urlqueries_array[4][2];		// note that in the database this is stored as an integer and not boolean
	}
	function selLegendColors()
	{
		global $con;
		$selectString = "SELECT k.keygroup_id, k.keygroup_class FROM keygroups_dynamic AS k;";
		selectQuery($con, $selectString, "resLegendColors");
	}
	function resLegendColors($in_result)
	{
		global $color_array, $color_count;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keygroup_id, keygroup_class
			$colorid_array[] = $temp_row[0];
			$color_array[] = $temp_row[1];
			$color_count += 1;
		}
		array_multisort($colorid_array, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $color_array);
	}
	function selKeyStyleClasses()
	{
		global $con;
		$selectString = "SELECT k.keygroup_id, k.keygroup_class FROM keygroups_static AS k;";
		selectQuery($con, $selectString, "resKeyStyleClasses");
	}
	function resKeyStyleClasses($in_result)
	{
		global $class_array;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keygroup_id, keygroup_class
			$classid_array[] = $temp_row[0];
			$class_array[] = $temp_row[1];
		}
		array_multisort($classid_array, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $class_array);
	}
	function selKeyStyles()
	{
		global $con, $stylesrecord_id;
		$selectString = "SELECT k.keygroup_id, k.key_number FROM keystyles AS k WHERE k.record_id = " . $stylesrecord_id . ";";
		selectQuery($con, $selectString, "resKeyStyles");
	}
	function resKeyStyles($in_result)
	{
		global $keystyle_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keygroup_id, key_number
			$keystyle_table[$temp_row[1]-1] = $temp_row;
		}
	}


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
		global $genre_array, $genre_order_array, $game_array;
		while ($genre_row = mysqli_fetch_row($in_result))
		{
			// genre_id, genre_name
			$genre_id = $genre_row[0];
			$genre_array[$genre_id-1] = $genre_row[1];
			$game_array[$genre_id-1] = [[],[]];
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
	function selStylegroupsFront()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.stylegroup_name FROM stylegroups AS s;";
		selectQuery($con, $selectString, "resStylegroupsFront");
	}
	function resStylegroupsFront($in_result)
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
	function selStylesFront()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.style_id, s.style_name FROM styles AS s ORDER BY s.style_name;";
		selectQuery($con, $selectString, "resStylesFront");
	}
	function resStylesFront($in_result)
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
	function selPlatformsFront()
	{
		global $con;
		$selectString = "SELECT p.platform_id, p.platform_name, p.platform_displayorder FROM platforms AS p;";
		selectQuery($con, $selectString, "resPlatformsFront");
	}
	function resPlatformsFront($in_result)
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
	function selLayoutsFront()
	{
		global $con;
		$selectString = "SELECT l.platform_id, l.layout_id, l.layout_name FROM layouts AS l ORDER BY l.layout_name;";
		selectQuery($con, $selectString, "resLayoutsFront");
	}
	function resLayoutsFront($in_result)
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
	// HTML and SVG

	function selContribsGames()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT c.author_id FROM contribs_games AS c WHERE c.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "resContribsGames");
	}
	function resContribsGames($in_result)
	{
		global $gamesrecord_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$gamesrecord_authors[] = getAuthorName($temp_row[0]);
		}
	}
	function selContribsStyles()
	{
		global $con, $stylesrecord_id;
		$selectString = "SELECT c.author_id FROM contribs_styles AS c WHERE c.record_id = " . $stylesrecord_id . ";";
		selectQuery($con, $selectString, "resContribsStyles");
	}
	function resContribsStyles($in_result)
	{
		global $stylesrecord_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$stylesrecord_authors[] = getAuthorName($temp_row[0]);
		}
	}
	function selContribsLayouts()
	{
		global $con, $layout_id;
		$selectString = "SELECT c.author_id FROM contribs_layouts AS c WHERE c.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "resContribsLayouts");
	}
	function resContribsLayouts($in_result)
	{
		global $layout_authors;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id
			$layout_authors[] = getAuthorName($temp_row[0]);
		}
	}
	function selThisGames_ID()
	{
		global $con, $game_id;
		$selectString = "SELECT g.game_name, g.game_friendlyurl FROM games AS g WHERE g.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "resThisGames_ID");
	}
	function resThisGames_ID($in_result)
	{
		global $game_name, $game_seo;
		$game_row = mysqli_fetch_row($in_result);
		if ($game_row)
		{
			// game_name, game_friendlyurl
			$game_name = $game_row[0];
			$game_seo = $game_row[1];
		}
	}
	function selThisGame_SEO()
	{
		global $con, $game_seo;
		$selectString = "SELECT g.game_name, g.game_id FROM games AS g WHERE g.game_friendlyurl = \"" . $game_seo . "\";";
		selectQuery($con, $selectString, "resThisGame_SEO");
	}
	function resThisGame_SEO($in_result)
	{
		global $game_name, $game_id;
		$game_row = mysqli_fetch_row($in_result);
		if ($game_row)
		{
			// game_name, game_id
			$game_name = $game_row[0];
			$game_id = intval($game_row[1]);
		}
	}
	function selAuthors()
	{
		global $con;
		$selectString = "SELECT a.author_id, a.author_name FROM authors AS a;";
		selectQuery($con, $selectString, "resAuthors");
	}
	function resAuthors($in_result)
	{
		global $author_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id, author_name
			$author_table[] = [$temp_row[0],$temp_row[1]];
		}
	}
	function selStyleGroups()
	{
		global $con;
		$selectString = "SELECT s.stylegroup_id, s.stylegroup_name FROM stylegroups AS s;";
		selectQuery($con, $selectString, "resStyleGroups");
	}
	function resStyleGroups($in_result)
	{
		global $style_table, $stylegroup_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			$style_table[] = [];
			// stylegroup_id, stylegroup_name
			$stylegroup_table[] = [$temp_row[0],$temp_row[1]];
		}
	}
	function selStyles()
	{
		global $con;
		$selectString = "SELECT s.style_id, s.style_name, s.style_whiteonblack, s.stylegroup_id FROM styles AS s ORDER BY s.stylegroup_id, s.style_name;";
		selectQuery($con, $selectString, "resStyles");
	}
	function resStyles($in_result)
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
					$style_table[$i][] = [$temp_row[0],$temp_row[1],$temp_row[2],$temp_row[3]];
					break;
				}
			}
		}
	}
	function selThisStyle()
	{
		global $con, $style_id;
		$selectString = "SELECT s.style_filename, s.style_name, s.stylegroup_id FROM styles AS s WHERE s.style_id = " . $style_id . " ORDER BY s.stylegroup_id, s.style_name;";
		selectQuery($con, $selectString, "resThisStyle");
	}
	function resThisStyle($in_result)
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
	function selThisFormat()
	{
		global $con, $format_id;
		$selectString = "SELECT f.format_name, f.format_enabled FROM formats AS f WHERE f.format_id = " . ($format_id + 1) . ";";
		selectQuery($con, $selectString, "resThisFormat");
	}
	function resThisFormat($in_result)
	{
		global $format_name;
		$format_row = mysqli_fetch_row($in_result);
		if ($format_row)
		{
			// format_name
			$format_name = $format_row[0];
		}
	}
	function selPositions()
	{
		global $con, $layout_id;
		$selectString = "SELECT p.position_left, p.position_top, p.position_width, p.position_height, p.symbol_norm_low, p.symbol_norm_cap, p.symbol_altgr_low, p.symbol_altgr_cap, p.key_number, p.lowcap_optional, p.numpad FROM positions AS p WHERE p.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "resPositions");
	}
	function resPositions($in_result)
	{
		global $position_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// position_left, position_top, position_width, position_height, symbol_norm_low, symbol_norm_cap, symbol_altgr_low, symbol_altgr_cap, key_number, lowcap_optional, numpad
			$position_table[$temp_row[8]-1] = $temp_row;
		}
	}
	function selThisLayout()
	{
		global $con, $layout_id;
		$selectString = "SELECT l.platform_id, l.layout_name, l.layout_keysnum, l.layout_fullsize_width, l.layout_fullsize_height, l.layout_tenkeyless_width, l.layout_tenkeyless_height FROM layouts AS l WHERE l.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "resThisLayout");
	}
	function resThisLayout($in_result)
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
	function selThisPlatform()
	{
		global $con, $platform_id;
		$selectString = "SELECT p.platform_name FROM platforms AS p WHERE p.platform_id = " . $platform_id . ";";
		selectQuery($con, $selectString, "resThisPlatform");
	}
	function resThisPlatform($in_result)
	{
		global $platform_name;
		$platform_row = mysqli_fetch_row($in_result);
		if ($platform_row)
		{
			// platform_name
			$platform_name = $platform_row[0];
		}
	}
	function selThisGamesRecord()
	{
		global $con, $layout_id, $game_id;
		$selectString = "SELECT r.record_id FROM records_games AS r WHERE r.layout_id = " . $layout_id . " AND r.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "resThisGamesRecord");
	}
	function resThisGamesRecord($in_result)
	{
		global $gamesrecord_id;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		if ($gamesrecord_row)
		{
			// record_id
			$gamesrecord_id = $gamesrecord_row[0];
		}
	}
	function selThisStylesRecord()
	{
		global $con, $layout_id, $style_id;
		$selectString = "SELECT r.record_id FROM records_styles AS r WHERE r.layout_id = " . $layout_id . " AND r.style_id = " . $style_id . ";";
		selectQuery($con, $selectString, "resThisStylesRecord");
	}
	function resThisStylesRecord($in_result)
	{
		global $stylesrecord_id;
		$stylesrecord_row = mysqli_fetch_row($in_result);
		if ($stylesrecord_row)
		{
			// record_id
			$stylesrecord_id = $stylesrecord_row[0];
		}
	}
	// make sure the columns are synced with the TSV page of the submission form
	function selBindings()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT b.normal_group, b.normal_action, b.shift_group, b.shift_action, b.ctrl_group, b.ctrl_action, b.alt_group, b.alt_action, b.altgr_group, b.altgr_action, b.extra_group, b.extra_action, b.image_file, b.image_uri, b.key_number FROM bindings AS b WHERE b.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "resBindings");
	}
	function resBindings($in_result)
	{
		global $binding_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// normal_group, normal_action, shift_group, shift_action, ctrl_group, ctrl_action, alt_group, alt_action, altgr_group, altgr_action, extra_group, extra_action, image_file, image_uri, key_number
			$binding_table[$temp_row[14]-1] = $temp_row;
		}
	}
	// make sure the columns are synced with the TSV page of the submission form
	function selLegends()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT l.keygroup_id, l.legend_description FROM legends AS l WHERE l.record_id = " . $gamesrecord_id . " ORDER BY l.keygroup_id;";
		selectQuery($con, $selectString, "resLegends");
	}
	function resLegends($in_result)
	{
		global $legend_table, $legend_count;
		$legend_count = 0;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keygroup_id, legend_description
			$legend_table[] = [$temp_row[0], $temp_row[1]];
			$legend_count += 1;
		}
	}
	// make sure the columns are synced with the TSV page of the submission form
	function selCommands()
	{
		global $con, $gamesrecord_id;
		$selectString =	"SELECT c.commandtype_id, c.command_text, c.command_description FROM commands AS c WHERE c.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "resCommands");
	}
	// it might be better to only have one larger table for all of these commands
	// it might make it a little easier to expand the number of command types in the future
	// also not sure if the counters are strictly necessary since it's very easy to get the length of each table
	function resCommands($in_result)
	{
		global	$commandouter_table, $commandouter_count;
		$commandouter_count = 0;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// commandtype_id, command_text, command_description
			$commandouter_table[$temp_row[0]-1][] = $temp_row;
			$commandouter_count += 1;
		}
	}
	function selCommandLabels()
	{
		global $con, $layout_language;
		$selectString =	"SELECT l.commandtype_id, l.commandlabel_string, t.commandtype_abbrv
				FROM commandlabels AS l, commandtypes AS t
				WHERE l.language_id = " . $layout_language . "
				AND l.commandtype_id = t.commandtype_id;";
		selectQuery($con, $selectString, "resCommandLabels");
	}
	function resCommandLabels($in_result)
	{
		global $commandlabels_table, $commandlabels_count;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// commandtype_id, commandlabel_string, commandtype_abbrv
			$commandlabels_table[$temp_row[0]-1] = $temp_row;
			$commandlabels_count += 1;
		}
	}
	function selThisLanguageStrings()
	{
		global $con, $layout_language;
		$selectString = "SELECT l.language_code, l.language_title, l.language_description, l.language_keywords, l.language_legend FROM languages AS l WHERE l.language_id = " . $layout_language . ";";
		selectQuery($con, $selectString, "resThisLanguageStrings");
	}
	// the structure of the "languages" table makes it harder to add new command types
	// I could create a bridge table with "commandtype_id" and "language_id" as constraints
	// should I name it "command_language" or "language_commmand"?
	// note that the "commandtypes" table *also* has text descriptions in English
	// there should really only be text descriptions in one place
	function resThisLanguageStrings($in_result)
	{
		global $language_code, $language_title, $language_description, $language_keywords, $language_legend;
		$temp_row = mysqli_fetch_row($in_result);
		if ($temp_row)
		{
			// language_code, language_title, language_description, language_keywords, language_legend
			$language_code		= $temp_row[ 0];
			$language_title		= $temp_row[ 1];
			$language_description	= $temp_row[ 2];
			$language_keywords	= $temp_row[ 3];
			$language_legend	= $temp_row[ 4];
		}
	}


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
		global $genre_array;
		while ($genre_row = mysqli_fetch_row($in_result))
		{
			// genre_id, genre_name, genre_displayorder
			$genre_array[] = $genre_row[1];
		}
	}
	function selGamesList()
	{
		global $con;
		$selectString = "SELECT g.genre_id, g.game_id, g.game_name, g.game_friendlyurl FROM games AS g ORDER BY g.game_name;";
		selectQuery($con, $selectString, "resGamesList");
	}
	function resGamesList($in_result)
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
	function selLayoutsList()
	{
		global $con;
		$selectString = "SELECT l.layout_id, l.layout_name, l.platform_id FROM layouts AS l ORDER BY l.layout_name;";
		selectQuery($con, $selectString, "resLayoutsList");
	}
	function resLayoutsList($in_result)
	{
		global $layout_array;
		while ($layout_row = mysqli_fetch_row($in_result))
		{
			// layout_id, layout_name, platform_id
			$layout_array[] = $layout_row;
		}
	}
	function selGamesRecordsList()
	{
		global $con;
		$selectString = "SELECT r.game_id, r.layout_id FROM records_games AS r;";
		selectQuery($con, $selectString, "resGamesRecordsList");
	}
	function resGamesRecordsList($in_result)
	{
		global $record_array;
		while ($record_row = mysqli_fetch_row($in_result))
		{
			// game_id, layout_id
			$record_array[] = $record_row;
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
		global $platform_array;
		while ($platform_row = mysqli_fetch_row($in_result))
		{
			// platform_id, platform_name, platform_abbv
			$platform_array[] = $platform_row;
		}
	}


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
	function selSeoUrl()
	{
		global $con;
		$selectString = "SELECT g.game_id, g.game_friendlyurl FROM games AS g;";
		selectQuery($con, $selectString, "resSeoUrl");
	}
	function resSeoUrl($in_result)
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
	function selGameRecords()
	{
		global $con;
		$selectString = "SELECT r.record_id, r.game_id, r.layout_id FROM records_games AS r;";
		selectQuery($con, $selectString, "resGameRecords");
	}
	function resGameRecords($in_result)
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
	function selStyleRecords()
	{
		global $con;
		$selectString = "SELECT r.record_id, r.style_id, r.layout_id FROM records_styles AS r;";
		selectQuery($con, $selectString, "resStyleRecords");
	}
	function resStyleRecords($in_result)
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
