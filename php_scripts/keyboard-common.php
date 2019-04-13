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
	// there is an analogous function written in JavaScript in "keyboard-submit-js.php"
	// need to keep the two functions synced
	function seo_url($input)
	{
		$input = mb_convert_case($input, MB_CASE_LOWER, "UTF-8");	//convert to lowercase
		$input = str_replace(array("'", "\""), "", $input);		//remove single and double quotes
		$input = preg_replace("/[^a-zA-Z0-9]+/", "-", $input);		//replace everything non-alphanumeric with dashes
		$input = preg_replace("/\-+/", "-", $input);			//replace multiple dashes with one dash
		$input = trim($input, "-");					//trim dashes from the beginning and end of the string if any
		return $input;
	}
	function getPlatformID($in_layout)
	{
		global $layout_array;
		for ($i = 0; $i < count($layout_array); $i++)
		{
			$layout_id = $layout_array[$i][0];
			$platform_id = $layout_array[$i][2];
			if ($in_layout == $layout_id)
			{
				return $platform_id;
			}
		}
	}
	function getLayoutName($in_layout)
	{
		global $layout_array;
		for ($i = 0; $i < count($layout_array); $i++)
		{
			$layout_id = $layout_array[$i][0];
			$layout_name = $layout_array[$i][1];
			if ($in_layout == $layout_id)
			{
				return $layout_name;
			}
		}
	}
	function print_key_html($in_id, $in_class, $in_color, $in_value)
	{
		echo
"								<div id=\"" . $in_id . "\" class=\"" . $in_class . "\">" . cleantextHTML($in_value) . "</div>\n";
	}
	function cleantextHTML($instring)
	{
		return str_replace("\\t","\t",str_replace("\\r","",str_replace("\\n","<br/>",str_replace("\r","",str_replace("\n","",str_replace("<","&lt;",str_replace(">","&gt;",str_replace("\"","&quot;",str_replace("'","&#39;",str_replace("&","&amp;",$instring))))))))));
	}
	function cleantextSVG($instring)
	{
		return str_replace("\\t","\t",str_replace("\\r","",str_replace("\\n","\n",str_replace("\r","",str_replace("\n","",str_replace("<","&lt;",str_replace(">","&gt;",str_replace("\"","&quot;",str_replace("'","&#39;",str_replace("&","&amp;",$instring))))))))));
	}
	function cleantextJS($instring)
	{
		return str_replace("\"","\\\"",str_replace("\\","\\\\",$instring));
	}
	function cleantextWiki($instring)
	{
		return str_replace("\\t","\t",str_replace("\\n","&lt;br/&gt;",str_replace("&","&amp;",$instring)));
	}
	function splittext($instring)
	{
		return array_filter(explode("\n", $instring));
	}
	function getcolor($group)
	{
		// hardcoded! should fetch from database instead
		$color_array = ["red","yel","grn","cyn","blu","mag","wht","gry","blk","org","olv","brn"];
		return array_key_exists($group-1, $color_array) ? $color_array[$group-1] : "non";
	}
	function getkeyclass($group)
	{
		// hardcoded! should fetch from database instead
		$class_array = ["cssA","cssB","cssC","cssD","cssE","cssF","cssG","cssH","cssI","cssJ","cssK","cssL"];
		return array_key_exists($group-1, $class_array) ? $class_array[$group-1] : "";

	}
	function getAuthorName($in_author_id)
	{
		global $author_table;
		for ($i = 0; $i < count($author_table); $i++)
		{
			$author_id = $author_table[$i][0];
			$author_name = $author_table[$i][1];
			if ($author_id == $in_author_id)
			{
				return $author_name;
			}
		}
	}
	function leadingZeros3($innumber)
	{
		$outstring = strval($innumber);
		if ($innumber < 10)
		{
			$outstring = "0" . $outstring;
		}
		if ($innumber < 100)
		{
			$outstring = "0" . $outstring;
		}
		return $outstring;
	}
	function leadingZeros2($innumber)
	{
		$outstring = strval($innumber);
		if ($innumber < 10)
		{
			$outstring = "0" . $outstring;
		}
		return $outstring;
	}
	function checkStyle($in_style_id)
	{
		global $style_table;
		for ($i = 0; $i < count($style_table); $i++)
		{
			$style_box = $style_table[$i];
			for ($j = 0; $j < count($style_box); $j++)
			{
				if ($style_box[$j][0] == $in_style_id)
				{
					return true;
				}
			}
		}
		return false;
	}
	function checkLayout($in_layout_id)
	{
	}

	// outputs e.g.  somefile.txt was last modified: December 29 2002 22:16:23.
	function getFileTime($in_file)
	{
		if (file_exists($in_file))
		{
			return "Last modified: " . date ("F d Y H:i:s.", filemtime($in_file));
		}
		else
		{
			return "Last modified: File does not exist.";
		}
	}

	function sortGames()
	{
		global $genre_order_array, $genre_array, $game_array;
		array_multisort($genre_order_array, $genre_array, $game_array);
	}

	$word_part1 = ["keyboard","video game","software","printable","graphical","visual"];
	$word_part2 = ["shortcut","binding","control","hotkey","command"];
	$word_part3 = ["diagram","chart","overlay","database","reference","guide","list","map"];


	// REPLACEMENTS FOR STORED PROCEDURES

	function doStyleGroupsHTML($in_result)
	{
		global $style_table, $style_group_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			$style_table[] = [];
			// stylegroup_id, stylegroup_name
			$style_group_table[] = $temp_row;
		}
	}
	function doStylesHTML($in_result)
	{
		global $style_table, $style_group_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// style_id, style_name, style_whiteonblack, stylegroup_id
			$style_group_1 = $temp_row[3];
			for ($i = 0; $i < count($style_group_table); $i++)
			{
				$style_group_2 = $style_group_table[$i][0];
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
			// position_left, position_top, position_width, position_height, symbol_low, symbol_cap, symbol_altgr, key_number, lowcap_optional
			$position_table[$temp_row[7]-1] = $temp_row;
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
	function doGamesHTML($in_result)
	{
		global $game_name, $game_seo;
		$game_row = mysqli_fetch_row($in_result);
		// game_name, game_friendlyurl
		$game_name = cleantextHTML($game_row[0]);
		$game_seo = $game_row[1];
	}
	function doGamesSEOHTML($in_result)
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
		global $gamesrecord_id, $gamesrecord_author;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		// record_id, author_id
		$gamesrecord_id = $gamesrecord_row[0];
		$gamesrecord_author = cleantextHTML(getAuthorName($gamesrecord_row[1]));
	}
	function doStylesRecordsHTML($in_result)
	{
		global $stylesrecord_id, $stylesrecord_author;
		$stylesrecord_row = mysqli_fetch_row($in_result);
		// record_id, author_id
		$stylesrecord_id = $stylesrecord_row[0];
		$stylesrecord_author = cleantextHTML(getAuthorName($stylesrecord_row[1]));
	}
	// need to move the stuff that used to be here to `doLanguages`
	function doLayoutsHTML($in_result)
	{
		global $layout_platform, $layout_name, $layout_author, $layout_keysnum, $layout_language, $layout_description;
		// platform_id, layout_name, author_id, layout_keysnum, layout_language
		$layout_row		= mysqli_fetch_row($in_result);
		$layout_platform	= $layout_row[0];
		$layout_name		= cleantextHTML($layout_row[1]);
		$layout_author		= cleantextHTML(getAuthorName($layout_row[2]));
		$layout_keysnum		= $layout_row[3];
		$layout_language	= $layout_row[4];
		$layout_description	= $layout_row[5];
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
	function doStyleGroupsSVG($in_result)
	{
		global $style_table, $style_group_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			$style_table[] = [];
			// stylegroup_id, stylegroup_name
			$style_group_table[] = $temp_row;
		}
	}
	function doStylesSVG($in_result)
	{
		global $style_table, $style_group_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// style_id, style_name, style_whiteonblack, stylegroup_id
			$style_group_1 = $temp_row[3];
			for ($i = 0; $i < count($style_group_table); $i++)
			{
				$style_group_2 = $style_group_table[$i][0];
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
			// position_left, position_top, position_width, position_height, symbol_low, symbol_cap, symbol_altgr, key_number, lowcap_optional
			$position_table[$temp_row[7]-1] = $temp_row;
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
	function doGamesSVG($in_result)
	{
		global $game_name, $game_seo;
		$game_row = mysqli_fetch_row($in_result);
		// game_name, game_friendlyurl
		$game_name = cleantextSVG($game_row[0]);
		$game_seo = $game_row[1];
	}
	function doGamesSEOSVG($in_result)
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
		global $gamesrecord_id, $gamesrecord_author;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		// record_id, author_id
		$gamesrecord_id = $gamesrecord_row[0];
		$gamesrecord_author = cleantextSVG(getAuthorName($gamesrecord_row[1]));
	}
	function doStylesRecordsSVG($in_result)
	{
		global $stylesrecord_id, $stylesrecord_author;
		$stylesrecord_row = mysqli_fetch_row($in_result);
		// record_id, author_id
		$stylesrecord_id = $stylesrecord_row[0];
		$stylesrecord_author = cleantextSVG(getAuthorName($stylesrecord_row[1]));
	}
	// need to move the stuff that used to be here to `doLanguages`
	function doLayoutsSVG($in_result)
	{
		global $layout_platform, $layout_name, $layout_author, $layout_keysnum;
		// platform_id, layout_name, author_id, layout_keysnum
		$layout_row		= mysqli_fetch_row($in_result);
		$layout_platform	= $layout_row[0];
		$layout_name		= cleantextSVG($layout_row[1]);
		$layout_author		= cleantextSVG(getAuthorName($layout_row[2]));
		$layout_keysnum		= $layout_row[3];
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
	function selGamesHTML()
	{
		global $con, $game_id;
		$selectString = "SELECT g.game_name, g.game_friendlyurl FROM games AS g WHERE g.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "doGamesHTML");
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
		$selectString = "SELECT p.position_left, p.position_top, p.position_width, p.position_height, p.symbol_low, p.symbol_cap, p.symbol_altgr, p.key_number, p.lowcap_optional FROM positions AS p WHERE p.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doPositionsHTML");
	}
	function selLayoutsHTML()
	{
		global $con, $layout_id;
		$selectString = "SELECT l.platform_id, l.layout_name, l.author_id, l.layout_keysnum, l.layout_language, l.layout_description FROM layouts AS l WHERE l.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doLayoutsHTML");
	}
	function selPlatformsHTML()
	{
		global $con, $layout_platform;
		$selectString = "SELECT p.platform_name FROM platforms AS p WHERE p.platform_id = " . $layout_platform . ";";
		selectQuery($con, $selectString, "doPlatformsHTML");
	}
	function selGamesRecordsHTML()
	{
		global $con, $layout_id, $game_id;
		$selectString = "SELECT r.record_id, r.author_id FROM records_games AS r WHERE r.layout_id = " . $layout_id . " AND r.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "doGamesRecordsHTML");
	}
	function selStylesRecordsHTML()
	{
		global $con, $layout_id, $style_id;
		$selectString = "SELECT r.record_id, r.author_id FROM records_styles AS r WHERE r.layout_id = " . $layout_id . " AND r.style_id = " . $style_id . ";";
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
	function selGamesSVG()
	{
		global $con, $game_id;
		$selectString = "SELECT g.game_name, g.game_friendlyurl FROM games AS g WHERE g.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "doGamesSVG");
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
		$selectString = "SELECT p.position_left, p.position_top, p.position_width, p.position_height, p.symbol_low, p.symbol_cap, p.symbol_altgr, p.key_number, p.lowcap_optional FROM positions AS p WHERE p.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doPositionsSVG");
	}
	function selLayoutsSVG()
	{
		global $con, $layout_id;
		$selectString = "SELECT l.platform_id, l.layout_name, l.author_id, l.layout_keysnum FROM layouts AS l WHERE l.layout_id = " . $layout_id . ";";
		selectQuery($con, $selectString, "doLayoutsSVG");
	}
	function selPlatformsSVG()
	{
		global $con, $layout_platform;
		$selectString = "SELECT p.platform_name FROM platforms AS p WHERE p.platform_id = " . $layout_platform . ";";
		selectQuery($con, $selectString, "doPlatformsSVG");
	}
	function selGamesRecordsSVG()
	{
		global $con, $layout_id, $game_id;
		$selectString = "SELECT r.record_id, r.author_id FROM records_games AS r WHERE r.layout_id = " . $layout_id . " AND r.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "doGamesRecordsSVG");
	}
	function selStylesRecordsSVG()
	{
		global $con, $layout_id, $style_id;
		$selectString = "SELECT r.record_id, r.author_id FROM records_styles AS r WHERE r.layout_id = " . $layout_id . " AND r.style_id = " . $style_id . ";";
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
?>
