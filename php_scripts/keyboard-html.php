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

	header("Content-Type: text/html; charset=utf8");

	include("./keyboard-connection.php");
	include("./keyboard-common.php");

	$con = mysqli_connect($con_website,$con_username,$con_password,$con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");

	$path_root		= "../";
	$game_seo		= array_key_exists("seo", $_GET) ? $_GET["seo"] : "";
	$game_id		= array_key_exists("gam", $_GET) ? intval(ltrim($_GET["gam"], "0")) : null;
	$style_id		= array_key_exists("sty", $_GET) ? intval(ltrim($_GET["sty"], "0")) : 15;
	$layout_id		= array_key_exists("lay", $_GET) ? intval(ltrim($_GET["lay"], "0")) : 1;
	$format_id		= array_key_exists("frm", $_GET) ? intval(ltrim($_GET["frm"], "0")) : 0;
	$svg_bool		= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0")) : null;
	$fix_url		= false;
	$php_url		= "";
	$svg_url		= "";
	$stylegroup_id		= 0;
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
	$style_table		= [];
	$gamesrecord_id		= 0;
	$gamesrecord_author	= "";
	$stylesrecord_id	= 0;
	$stylesrecord_author	= "";
	$combo_count		= 0;
	$joystick_count		= 0;
	$mouse_count		= 0;
	$note_count		= 0;
	$style_filename		= "";
	$style_name		= "";
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
	$layout_keysnum		= 0;
	$layout_keygap		= 4;

	// validity checks
	if (!$game_id)
	{
		callProcedure1Txt($con, "get_games_friendly_chart", "doGamesSEO", $game_seo);
//		echo "game_seo = " . $game_seo . "\n";
//		echo "game_id = " . $game_id . "\n";
	}
	else
	{
		$fix_url = true;
	}
	if ($svg_bool)
	{
		$format_id = $svg_bool;
	}

	function doThisStyle($in_result)
	{
		global $style_filename, $style_name, $stylegroup_id;
		$style_row = mysqli_fetch_row($in_result);
		$style_filename = $style_row[0];
		$style_name = cleantextHTML($style_row[1]);
		$stylegroup_id = $style_row[2];
	}
	function doStyles($in_result)
	{
		global $style_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// style_id, style_name, style_whiteonblack
			$style_table[] = $temp_row;
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
//		$legend_table = mysqli_fetch_array($in_result);
//		print_r($legend_table);
//		error_log("woot " . count($legend_table));
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
			// keystyle_group, key_number
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
	function doGamesRecords($in_result)
	{
		global $gamesrecord_id, $gamesrecord_author;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		$gamesrecord_id = $gamesrecord_row[0];
		$gamesrecord_author = cleantextHTML(getAuthorName($gamesrecord_row[1]));
	}
	function doStylesRecords($in_result)
	{
		// record_id, author_id
		global $stylesrecord_id, $stylesrecord_author;
		$stylesrecord_row = mysqli_fetch_row($in_result);
		$stylesrecord_id = $stylesrecord_row[0];
		$stylesrecord_author = cleantextHTML(getAuthorName($stylesrecord_row[1]));
	}
	function doLayouts($in_result)
	{
		global $layout_platform, $layout_name, $layout_title, $layout_mouse, $layout_joystick, $layout_combos, $layout_notes, $layout_legend, $layout_author, $layout_description, $layout_keywords, $layout_keysnum;
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
		$layout_keysnum		= $layout_row[11];
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
//	error_log("get_layouts_chart");
	callProcedure1($con, "get_layouts_chart", "doLayouts", $layout_id);
//	error_log("get_platforms_chart");
	callProcedure1($con, "get_platforms_chart", "doPlatforms", $layout_platform);
//	error_log("get_records_games_chart");
	callProcedure2($con, "get_records_games_chart", "doGamesRecords", $layout_id, $game_id);
//	error_log("get_records_styles_chart");
	callProcedure2($con, "get_records_styles_chart", "doStylesRecords", $layout_id, $style_id);
//	error_log("get_keystyles_chart");
	callProcedure1($con, "get_keystyles_chart", "doKeystyles", $stylesrecord_id);
//	error_log("get_bindings_chart");
	callProcedure1($con, "get_bindings_chart", "doBindings", $gamesrecord_id);
//	error_log("get_legends_chart");
	callProcedure1($con, "get_legends_chart", "doLegends", $gamesrecord_id);
//	error_log("get_commands_chart");
	callProcedure1($con, "get_commands_chart", "doCommands", $gamesrecord_id);

	mysqli_close($con);

	// validity checks
	$game_seo		= $game_seo ? $game_seo : "unrecognized-game";
	$temp_game_name		= $game_name ? $game_name : "Unrecognized Game";
	$temp_layout_name	= $layout_name ? $layout_name : "Unrecognized Layout";
	$temp_style_name	= $style_name ? $style_name : "Unrecognized Style";
	$temp_platform_name	= $platform_name ? $platform_name : "Unrecognized Platform";
	$thispage_title_a	= $temp_game_name;
	$thispage_title_b	= " - " . $layout_title . " - " . $temp_platform_name . " " . $temp_layout_name . " - " . $temp_style_name;

	// validity checks (should check the layout here too... but)
	if (!checkStyle($style_id))
	{
		$style_id = 15;
		$fix_url = true;
	}

	$php_url = "http://isometricland.net/keyboard/keyboard-diagram-" . $game_seo . ".php?sty=" . $style_id . "&lay=" . $layout_id . "&frm=" . $format_id;
	$svg_url = "http://isometricland.net/keyboard/keyboard-diagram-" . $game_seo . ".svg?sty=" . $style_id . "&lay=" . $layout_id . "&frm=" . $format_id;

	// fix URL
	if ($fix_url)
	{
		header("Location: " . $php_url);
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
		<title><?php echo $thispage_title_a; ?><?php echo $thispage_title_b; ?></title>
<?php
	echo
"		<link rel=\"canonical\" href=\"" . $php_url . "\"/>\n" .
"		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_root . "favicon.png\"/>\n" .
"		<link rel=\"stylesheet\" type=\"text/css\" href=\"./style_normalize.css\"/>\n" .
"		<link rel=\"stylesheet\" type=\"text/css\" href=\"./style_common.css\"/>\n" .
"		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>\n" .
"		<meta name=\"description\" content=\"" . $layout_description . $temp_game_name . ".\"></meta>\n" .
"		<meta name=\"keywords\" content=\"visual,keyboard,keys,diagrams,charts,overlay,shortcuts,bindings,mapping,maps,controls,hotkeys,database,print,printable,video game,software,guide,reference," . $layout_keywords . "," . $temp_game_name . "\"></meta>\n";
	include($path_root . "ssi/analyticstracking.php");
?>
		<script src="keyboard-js.php"></script>
		<style type="text/css">
<?php	include("./html_" . $style_filename . ".css"); ?>
		</style>
	</head>
	<body>
		<header>
			<div class="bodiv"><h2><?php echo $thispage_title_a; ?><small><?php echo $thispage_title_b; ?></small></h2></div>
		</header>
		<main style="">
			<div id="keybd" class="bodiv" style="width:1660px;height:480px;">
<?php
	// validity checks
	if (!$gamesrecord_id)
	{
		echo
				"<h3>No bindings found for game \"" . $temp_game_name . "\" on layout \"" . $temp_platform_name . " " . $temp_layout_name . "\".</h3>";
	}
	// validity checks
	if (!$stylesrecord_id)
	{
		echo
				"<h3>No configurations found for style \"" . $temp_style_name . "\" on layout \"" . $temp_platform_name . " " . $temp_layout_name . "\".</h3>";
	}
	// keys
	if ($gamesrecord_id && $stylesrecord_id)
	{
		for ($i = 0; $i < $layout_keysnum; $i++)
		{
			if (array_key_exists($i, $position_table))
			{
				// position_left, position_top, position_width, position_height, symbol_low, symbol_cap, symbol_altgr, key_number, lowcap_optional
				$key_sty	= array_key_exists($i, $keystyle_table) ? getkeyclass($keystyle_table[$i][0]) : "";
				$position_row	= $position_table[$i];
				$pos_lft	= $position_row[0] + $layout_keygap/2;
				$pos_top	= $position_row[1] + $layout_keygap/2;
				$pos_wid	= $position_row[2] - $layout_keygap;		// 4
				$pos_hgh	= $position_row[3] - $layout_keygap;
				$cap_low	= cleantextHTML($position_row[4]);
				$cap_hgh	= cleantextHTML($position_row[5]);
				$cap_rgt	= cleantextHTML($position_row[6]);
				$cap_opt	= $position_row[8];
				$img_wid	= 48;
				$img_hgh	= 48;
				$img_pos_x	= $pos_wid/2 - $img_wid/2;
				$img_pos_y	= $pos_hgh/2 - $img_hgh/2;
				if (array_key_exists($i, $binding_table))
				{
					$binding_row	= $binding_table[$i];
					$bkg_nor = getcolor($binding_row[0]);
					$key_nor = cleantextHTML($binding_row[1]);
					$bkg_shf = getcolor($binding_row[2]);
					$key_shf = cleantextHTML($binding_row[3]);
					$bkg_ctl = getcolor($binding_row[4]);
					$key_ctl = cleantextHTML($binding_row[5]);
					$bkg_alt = getcolor($binding_row[6]);
					$key_alt = cleantextHTML($binding_row[7]);
					$bkg_agr = getcolor($binding_row[8]);
					$key_agr = cleantextHTML($binding_row[9]);
					$bkg_xtr = getcolor($binding_row[10]);
					$key_xtr = cleantextHTML($binding_row[11]);
					$img_fil = $binding_row[12];
					$img_uri = $binding_row[14];
				}
				else
				{
					$bkg_nor = "non";
					$key_nor = "";
					$bkg_shf = "non";
					$key_shf = "";
					$bkg_ctl = "non";
					$key_ctl = "";
					$bkg_alt = "non";
					$key_alt = "";
					$bkg_agr = "non";
					$key_agr = "";
					$bkg_xtr = "non";
					$key_xtr = "";
					$img_fil = "";
					$img_uri = "";
				}

				echo
"				<div class=\"key " . $bkg_nor . " " . $key_sty . "\" style=\"left:" . $pos_lft . "px;top:" . $pos_top . "px;width:" . $pos_wid . "px;height:" . $pos_hgh . "px;background-size:auto;\">\n";
				if ($img_fil)
				{
					echo
"					<img class=\"keyimg\" style=\"left:" . $img_pos_x . "px;top:" . $img_pos_y . "px;width:" . $img_wid . "px;height:" . $img_hgh . "px;\" src=\"" . $img_uri . "\"/>\n";
				}
				if ($cap_hgh != "")
				{
					echo
"					<div class=\"caphgh\">" . $cap_hgh . "</div>\n";
				}
				if (($cap_low != "") && ($cap_opt == false))
				{
					echo
"					<div class=\"caplow\">" . $cap_low . "</div>\n";
				}
				if ($cap_rgt != "")
				{
					echo
"					<div class=\"caprgt\">" . $cap_rgt . "</div>\n";
				}
				if ($key_nor != "")
				{
					if ($cap_opt == false)
					{
						echo
"					<div class=\"keyopf\">" . $key_nor . "</div>\n";
					}
					else
					{
						echo
"					<div class=\"keyopt\">" . $key_nor . "</div>\n";
					}
				}
				if ($key_shf != "")
				{
					echo
"					<div class=\"keyshf\">" . $key_shf . "</div>\n";
				}
				if ($key_ctl != "")
				{
					echo
"					<div class=\"keyctl\">" . $key_ctl . "</div>\n";
				}
				if ($key_alt != "")
				{
					echo
"					<div class=\"keyalt\">" . $key_alt . "</div>\n";
				}
				if ($key_agr != "")
				{
					echo
"					<div class=\"keyagr\">" . $key_agr . "</div>\n";
				}
				if ($key_xtr != "")
				{
					echo
"					<div class=\"keyxtr\">" . $key_xtr . "</div>\n";
				}
				echo
"				</div>\n";
			}
		}
	}
?>
			</div>
			<div class="bodiv" style="clear:both;">
				<div style="float:left;">
					<div class="key non" style="position:relative;width:69px;height:69px;">
						<div class="caphgh">Upkey</div>
						<div class="caplow">Lowkey</div>
						<div class="keyopf">Caption</div>
						<div class="keyshf">Shift</div>
						<div class="keyctl">Ctrl</div>
						<div class="keyalt">Alt</div>
					</div>
				</div>
				<div style="float:left;margin-left:1em;">
<?php	// legend
	if ($stylegroup_id == 1)
	{
		for ($i = 0; $i < count($legend_table); $i++)
		{
			$legend_row = $legend_table[$i];
			if ($legend_row[0] != null)
			{
				$leg_grp = getcolor($legend_row[0]);
				$leg_dsc = cleantextHTML($legend_row[1]);
				echo
"					<div class=\"leggrp\"><span class=\"legcll " . $leg_grp . "\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;" . $leg_dsc . "</div>\n";
			}
		}
	}
?>
				</div>
				<div style="float:left;margin-left:10em;margin-top:1em;">
<?php /*include($path_root . "ssi/adsense_horz_large.php");*/ ?>
				</div>
			</div>
			<div class="bodiv" style="float:left;clear:left;">
<?php	// combos
	if ($combo_count > 0)
	{
		echo
"				<h3>" . $layout_combos . "</h3>
				<p>\n";
		for ($i = 0; $i < $combo_count; $i++)
		{
			$combo_row = $combo_table[$i];
			if ($combo_row[0] || $combo_row[1])
			{
				$combo_com = cleantextHTML($combo_row[0]);
				$combo_des = cleantextHTML($combo_row[1]);
				echo
"					" . $combo_com . " = " . $combo_des . "<br/>\n";
			}
		}
		echo
"				</p>\n";
	}
?>
			</div>
			<div class="bodiv" style="float:left;">
<?php	// mice
	if ($mouse_count > 0)
	{
		echo
"				<h3>" . $layout_mouse . "</h3>
				<p>\n";
		for ($i = 0; $i < $mouse_count; $i++)
		{
			$mouse_row = $mouse_table[$i];
			if ($mouse_row[0] || $mouse_row[1])
			{
				$mouse_com = cleantextHTML($mouse_row[0]);
				$mouse_des = cleantextHTML($mouse_row[1]);
				echo
"					" . $mouse_com . " = " . $mouse_des . "<br/>\n";
			}
		}
		echo
"				</p>\n";
	}
?>
			</div>
			<div class="bodiv" style="float:left;">
<?php	// joysticks
	if ($joystick_count > 0)
	{
		echo
"				<h3>" . $layout_joystick . "</h3>
				<p>\n";
		for ($i = 0; $i < $joystick_count; $i++)
		{
			$joystick_row = $joystick_table[$i];
			if ($joystick_row[0] || $joystick_row[1])
			{
				$joystick_com = cleantextHTML($joystick_row[0]);
				$joystick_des = cleantextHTML($joystick_row[1]);
				echo
"					" . $joystick_com . " = " . $joystick_des . "<br/>\n";
			}
		}
		echo
"				</p>\n";
	}
?>
			</div>
			<div class="bodiv" style="float:left;">
<?php	// notes
	if ($note_count > 0)
	{
		echo
"				<h3>" . $layout_notes . "</h3>
				<p>\n";
		for ($i = 0; $i < $note_count; $i++)
		{
			$note_row = $note_table[$i];
			if ($note_row[0] || $note_row[1])
			{
				$note_com = cleantextHTML($note_row[0]);
				$note_des = cleantextHTML($note_row[1]);
				echo
"					" . $note_des . "<br/>\n";
			}
		}
		echo
"				</p>\n";
	}
?>
			</div>
		</main>
		<footer>
			<div class="bodiv" style="clear:both;">
				<p><a target="_blank" rel="license" href="http://creativecommons.org/licenses/LGPL/2.1/"><img alt="Creative Commons License" src="<?php echo $path_root; ?>images/license_cc-lgpl_88x31.png" /></a><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="<?php echo $path_root; ?>images/license_cc-by-sa_88x31.png" /></a></p>
				<p>"Video Game Keyboard Diagrams" software was created by Michael Horvath and is licensed under <a target="_blank" rel="license" href="https://creativecommons.org/licenses/LGPL/2.1/;/">CC LGPL 2.1</a>. Content is licensed under <a target="_blank" href="https://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a>. You can find this project on <a target="_blank" href="https://github.com/mjhorvath/vgkd">GitHub</a>.</p>
				<p>
<?php
	if (($gamesrecord_author) && ($gamesrecord_author != "Michael Horvath"))
	{
		echo
"Binding scheme by: " . $gamesrecord_author . ". ";
	}
	if (($layout_author) && ($layout_author != "Michael Horvath"))
	{
		echo
"Keyboard layout by: " . $layout_author . ". ";
	}
	if (($stylesrecord_author) && ($stylesrecord_author != "Michael Horvath"))
	{
		echo
"Style design by: " . $stylesrecord_author . ". ";
	}
?>
				</p>
				<p>Return to <a href="keyboard.php">Video Game Keyboard Diagrams</a>. View the <a href="keyboard-list.php">master list</a>. Having trouble printing? Take a look at <a href="keyboard.php#print_tips">these printing tips</a>.</p>
<?php
	echo
"				<form name=\"VisualStyleSwitch\">
					<label for=\"stylesel\">Visual style:</label>
					<select class=\"stylechange\" id=\"stylesel\" name=\"style\">\n";
	for ($i = 0; $i < count($style_table); $i++)
	{
		$style_row = $style_table[$i];
		if ($style_row[0])
		{
			$style_idx = $style_row[0];
			$style_nam = cleantextHTML($style_row[1]);
			if ($style_id == $style_idx)
			{
				echo
"						<option value=\"" . $style_idx . "\" selected>" . $style_nam . "</option>\n";
			}
			else
			{
				echo
"						<option value=\"" . $style_idx . "\">" . $style_nam . "</option>\n";
			}
		}
	}

	echo
"					</select>
					<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"rad0\" value=\"0\" " . ($format_id == 0 ? "checked" : "") . " />&nbsp;<label for=\"rad0\">HTML</label>
					<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"rad1\" value=\"1\" " . ($format_id == 1 ? "checked" : "") . " />&nbsp;<label for=\"rad1\">SVG</label>
					<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"rad2\" value=\"2\" " . ($format_id == 2 ? "checked" : "") . " />&nbsp;<label for=\"rad2\">MediaWiki</label>
					<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"rad3\" value=\"3\" disabled />&nbsp;<label for=\"rad3\"><s>PDF</s></label>
					<input class=\"stylechange\" type=\"button\" value=\"Change\" onclick=\"reloadThisPage('" . $game_id . "', '" . $layout_id . "', '" . $game_seo . "')\" />
				</form>\n";
?>
				<p>Last modified: <?php echo date("F d Y H:i:s.", getlastmod()) ?></p>
			</div>
		</footer>
	</body>
</html>
