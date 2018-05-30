<?php
	header("Content-Type: text/html; charset=utf8");
	$path_root = "../";
	$page_title = "Video Game Keyboard Diagrams - MediaWiki Code";
	$foot_array = ["copyright","license_kbd"];
	$analytics	= true;
	$is_short	= true;
	include($path_root . "ssi/normalpage.php");
	echo $page_top;
?>
<?php
	// Copyright (C) 2009  Michael Horvath

	// This library is free software; you can redistribute it and/or
	// modify it under the terms of the GNU Lesser General Public
	// License as published by the Free Software Foundation; either
	// version 2.1 of the License, or (at your option) any later version.

	// This library is distributed in the hope that it will be useful,
	// but WITHOUT ANY WARRANTY; without even the implied warranty of
	// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	// Lesser General Public License for more details.

	// You should have received a copy of the GNU Lesser General Public
	// License along with this library; if not, write to the Free Software
	// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 
	// 02110-1301  USA

	include("./keyboard-connection.php");
	include("./keyboard-common.php");

	$con = mysqli_connect($con_website,$con_username,$con_password,$con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");

	$game_seo		= array_key_exists("seo", $_GET) ? $_GET["seo"] : "";
	$game_id		= array_key_exists("gam", $_GET) ? intval(ltrim($_GET["gam"], "0")) : 1;
	$style_id		= array_key_exists("sty", $_GET) ? intval(ltrim($_GET["sty"], "0")) : 15;
	$layout_id		= array_key_exists("lay", $_GET) ? intval(ltrim($_GET["lay"], "0")) : 1;
	$svg_bool		= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0")) : 0;
	$keys_number		= 118;		// should really be calculated dynamically or stored in the database for each layout
	$actions_number		= 10;
	$legend_number		= 12;
	$position_table		= [];
	$keystyle_table		= [];
	$binding_table		= [];
	$legend_table		= [];
	$command_table		= [];
	$combo_table		= [];
	$joystick_table		= [];
	$mouse_table		= [];
	$note_table		= [];
	$author_table		= [];
	$record_id		= 0;
	$record_author		= "";
	$combo_count		= 0;
	$joystick_count		= 0;
	$mouse_count		= 0;
	$note_count		= 0;
	$style_filename		= "";
	$style_name		= "";
	$style_author		= "";
	$game_name		= "";
	$platform_name		= "";
	$layout_platform	= 0;
	$layout_name		= "";
	$layout_title		= "";
	$layout_mouse		= "";
	$layout_joystick	= "";
	$layout_combos		= "";
	$layout_notes		= "";
	$layout_legend		= "";		// heading was removed from the database
	$layout_author		= "";
	$layout_description	= "";
	$layout_keywords	= "";
	$game_array		= [];

	function doThisStyle($in_result)
	{
		global $style_filename, $style_name, $style_author;
		$style_row = mysqli_fetch_row($in_result);
		$style_filename = $style_row[0];
		$style_name = cleantextHTML($style_row[1]);
		$style_author = cleantextHTML(getAuthorName($style_row[2]));
	}
	function doStyles($in_result)
	{
		global $styles_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			$styles_table[] = $temp_row;
		}
	}
	function doBindings($in_result)
	{
		global $binding_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			$binding_table[$temp_row[13]-1] = $temp_row;
		}
	}
	function doLegends($in_result)
	{
		global $legend_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			$legend_table[] = $temp_row;
		}
	}
	function doCommands($in_result)
	{
		global $combo_table, $mouse_table, $joystick_table, $note_table, $combo_count, $mouse_count, $joystick_count, $note_count;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
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
			}
		}
	}
	function doPositions($in_result)
	{
		global $position_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// position_left, position_top, position_width, position_height, symbol_low, symbol_cap, symbol_altgr, key_number, lowcap_optional
			$position_table[$temp_row[7]-1] = $temp_row;
		}
	}
	function doKeystyles($in_result)
	{
		global $keystyle_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			$keystyle_table[$temp_row[1]-1] = $temp_row;
		}
	}
	function doGames($in_result)
	{
		global $game_name, $game_seo;
		$game_row = mysqli_fetch_row($in_result);
		$game_name = cleantextHTML($game_row[0]);
		$game_seo = $game_row[1];
	}
	function doGamesSEO($in_result)
	{
		global $game_name, $game_id;
		$game_row = mysqli_fetch_row($in_result);
		$game_name = cleantextHTML($game_row[0]);
		$game_id = intval($game_row[1]);
	}
	function doPlatforms($in_result)
	{
		global $platform_name;
		$platform_row = mysqli_fetch_row($in_result);
		$platform_name = cleantextHTML($platform_row[0]);
	}
	function doRecords($in_result)
	{
		global $record_id, $record_author;
		$record_row = mysqli_fetch_row($in_result);
		$record_id = $record_row[0];
		$record_author = cleantextHTML(getAuthorName($record_row[1]));
	}
	function doLayouts($in_result)
	{
		global $layout_platform, $layout_name, $layout_title, $layout_mouse, $layout_joystick, $layout_combos, $layout_notes, $layout_legend, $layout_author, $layout_description, $layout_keywords;
		$layout_row		= mysqli_fetch_row($in_result);
		$layout_platform	= $layout_row[0];
		$layout_name		= cleantextHTML($layout_row[1]);
		$layout_title		= cleantextHTML($layout_row[2]);
		$layout_mouse		= cleantextHTML($layout_row[3]);
		$layout_joystick	= cleantextHTML($layout_row[4]);
		$layout_combos		= cleantextHTML($layout_row[5]);
		$layout_notes		= cleantextHTML($layout_row[6]);
		$layout_legend		= cleantextHTML($layout_row[7]);		// heading no longer exists in the database
		$layout_description	= cleantextHTML($layout_row[8]);
		$layout_keywords	= cleantextHTML($layout_row[9]);
		$layout_author		= cleantextHTML(getAuthorName($layout_row[10]));
	}
	function doAuthors($in_result)
	{
		global $author_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// author_id, author_name
			$author_table[] = $temp_row;
		}
	}

	if (!$game_id)
	{
		if ($game_seo)
		{
//			error_log("get_games_friendly_chart");
			callProcedure1Txt($con, "get_games_friendly_chart", "doGamesSEO", $game_seo);
		}
		else
		{
			$game_id = 1;
		}
	}

//	error_log("get_games_chart");
	callProcedure1($con, "get_games_chart", "doGames", $game_id);
//	error_log("get_authors_chart");
	callProcedure0($con, "get_authors_chart", "doAuthors");
//	error_log("get_styles_dropdown");
	callProcedure0($con, "get_styles_dropdown", "doStyles");
//	error_log("get_styles_chart");
	callProcedure1($con, "get_styles_chart", "doThisStyle", $style_id);
//	error_log("get_positions_chart");
	callProcedure1($con, "get_positions_chart", "doPositions", $layout_id);
//	error_log("get_keystyles_chart");
	callProcedure1($con, "get_keystyles_chart", "doKeystyles", $style_id);
//	error_log("get_layouts_chart");
	callProcedure1($con, "get_layouts_chart", "doLayouts", $layout_id);
//	error_log("get_platforms_chart");
	callProcedure1($con, "get_platforms_chart", "doPlatforms", $layout_platform);
//	error_log("get_records_games_chart");
	callProcedure2($con, "get_records_games_chart", "doRecords", $layout_id, $game_id);
//	error_log("get_bindings_chart");
	callProcedure1($con, "get_bindings_chart", "doBindings", $record_id);
//	error_log("get_legends_chart");
	callProcedure1($con, "get_legends_chart", "doLegends", $record_id);
//	error_log("get_commands_chart");
	callProcedure1($con, "get_commands_chart", "doCommands", $record_id);

	$thispage_title	= $game_name ? $game_name : "Unrecognized Game";

	mysqli_close($con);
?>
<h2>Game Title: <?php echo $thispage_title; ?></h2>
<p>I have created a template for MediaWiki that does basically the same thing as the charts on this site. You can find the template as well as instructions on how to use it on StrategyWiki and Wikia, <a target="_blank" href="http://strategywiki.org/wiki/Template:Kbdchart">here</a> and <a target="_blank" href="http://templates.wikia.com/wiki/Template:Kbdchart">here</a>. You can generate the MediaWiki code for each game using this printout. Just remember to change the number after "?gam=" portion of the page URL to the correct game ID. (For now, you can look up the game ID in the <a href="keyboard-list.php">master list</a>.)</p>
<textarea readonly="readonly" wrap="off" style="width:100%;height:30em;">
{{kbdchart
<?php
	// keys
	for ($i = 0; $i < $keys_number; $i++)
	{
		$leadZ		= leadingZeros3($i);
		$binding_row	= $binding_table[$i];
		$bkg_nor	= getcolor($binding_row[0]);
		$key_nor	= cleantextcode($binding_row[1]);
		$bkg_shf	= getcolor($binding_row[2]);
		$key_shf	= cleantextcode($binding_row[3]);
		$bkg_ctl	= getcolor($binding_row[4]);
		$key_ctl	= cleantextcode($binding_row[5]);
		$bkg_alt	= getcolor($binding_row[6]);
		$key_alt	= cleantextcode($binding_row[7]);
		$bkg_agr	= getcolor($binding_row[8]);
		$key_agr	= cleantextcode($binding_row[9]);
		$bkg_xtr	= getcolor($binding_row[10]);
		$key_xtr	= cleantextcode($binding_row[11]);
		$img_fil	= $binding_row[12];
		echo
"|" . $leadZ . "b=" . $bkg_nor . "|". $leadZ . "t=" . $key_nor . "|" . $leadZ . "s=" . $key_shf . "|" . $leadZ . "c=" . $key_ctl . "|" . $leadZ . "a=" . $key_alt . "\n";
	}
?>
}}

{{kbdlegend
<?php
	// legend
	for ($i = 0; $i < 12; $i++)
	{
		$leadZ = leadingZeros2($i);
		$legend_row = $legend_table[$i];
		if ($legend_row[0] != null)
		{
			$leg_grp = getcolor($legend_row[0]);
			$leg_dsc = cleantextcode($legend_row[1]);
			echo
"|" . $leadZ. "lgb=" . $leg_grp . "|" . $leadZ . "lgt=" . $leg_dsc . "\n";
		}
	}
?>
}}
</textarea>
<?php echo $page_bot; ?>
