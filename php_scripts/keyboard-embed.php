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

	header("Content-Type: text/html; charset=utf8");

	$path_root		= "../";
	$path_file		= "./keyboard-embed.php";

	include($path_root . "ssi/analyticstracking.php");
	include($path_root . "ssi/keyboard-connection.php");
	include("./lib/keyboard-common.php");
	include("./lib/keyboard-queries.php");

	$php_url		= "";
	$svg_url		= "";
	$stylegroup_id		= 0;
	$command_table		= [];
	$combo_table		= [];
	$joystick_table		= [];
	$mouse_table		= [];
	$note_table		= [];
	$author_table		= [];
	$style_table		= [];
	$gamesrecord_id		= 0;
	$gamesrecord_authors	= [];
	$stylesrecord_id	= 0;
	$stylesrecord_authors	= [];
	$combo_count		= 0;
	$mouse_count		= 0;
	$joystick_count		= 0;
	$note_count		= 0;
	$cheat_count		= 0;
	$console_count		= 0;
	$emote_count		= 0;
	$style_filename		= "";
	$style_name		= "";
	$style_author		= "";
	$game_name		= "";
	$platform_name		= "";
	$platform_id		= 0;
	$layout_name		= "";
	$layout_title		= "";
	$layout_mouse		= "";
	$layout_joystick	= "";
	$layout_combos		= "";
	$layout_notes		= "";
	$layout_authors		= [];
	$layout_description	= "";
	$layout_keywords	= "";
	$layout_language	= "";
	$layout_keysnum		= 0;
	$layout_keygap		= 4;
	$layout_padding		= 18;
	$layout_fullsize_width		= 1200;
	$layout_fullsize_height		= 400;
	$layout_tenkeyless_width	= 1200;
	$layout_tenkeyless_height	= 400;
	$layout_legend_padding		= 36;
	$layout_legend_height		= 72;
	$temp_game_seo		= "";
	$temp_game_name		= "";
	$temp_layout_name	= "";
	$temp_style_name	= "";
	$temp_platform_name	= "";
	// these should maybe be moved to the database and localized
	$string_title		= cleantextHTML("Video Game Keyboard Diagrams");
	$string_combo		= cleantextHTML("Keyboard Combinations");
	$string_mouse		= cleantextHTML("Mouse Controls");
	$string_joystick	= cleantextHTML("Joystick/Gamepad Controls");
	$string_note		= cleantextHTML("Additional Notes");
	$string_cheat		= cleantextHTML("Cheat Codes");
	$string_console		= cleantextHTML("Console Commands");
	$string_emote		= cleantextHTML("Chat Commands/Emotes");
	$string_description	= cleantextHTML("Keyboard hotkey & binding chart for ");
	$string_keywords	= cleantextHTML("English,keyboard,keys,diagram,chart,overlay,shortcut,binding,mapping,map,controls,hotkeys,database,print,printable,video game,software,visual,guide,reference");

	// MySQL connection
	$con = mysqli_connect($con_website, $con_username, $con_password, $con_database);
 	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}
	mysqli_query($con, "SET NAMES 'utf8'");

	// these also execute a few MySQL queries
	getDefaults();			// get default values for entities if missing
	checkURLParameters("svg");	// gather and validate URL parameters

	// MySQL queries
	selAuthorsHTML();
	selStyleGroupsHTML();
	selStylesHTML();
	selThisStyleHTML();
//	selPositionsHTML();
	selGamesRecordsHTML();
	selStylesRecordsHTML();
	selLayoutsHTML();
	selPlatformsHTML();
//	selKeystylesHTML();
//	selBindingsHTML();
	selLegendsHTML();
	selCommandsHTML();
	selContribGamesHTML();
	selContribStylesHTML();
	selContribLayoutsHTML();

	mysqli_close($con);

	// validity checks
	checkForErrors();

	$thispage_title_a	= $temp_game_name;
	$thispage_title_b	= " - " . $string_title . " - " . $temp_platform_name . " - " . $temp_layout_name . " - " . $temp_style_name . " - GRID:" . $gamesrecord_id;

	// layout outer bounds
	if ($ten_bool == 0)
	{
		$layout_min_horizontal	= -$layout_padding;
		$layout_max_horizontal	=  $layout_padding * 2 + $layout_tenkeyless_width;
		$layout_min_vertical	= -$layout_padding;
		$layout_max_vertical	=  $layout_padding * 2 + $layout_tenkeyless_height + $layout_legend_padding + $layout_legend_height;
		$layout_legend_top	=  $layout_tenkeyless_height + $layout_legend_padding;
	}
	else if ($ten_bool == 1)
	{
		$layout_min_horizontal	= -$layout_padding;
		$layout_max_horizontal	=  $layout_padding * 2 + $layout_fullsize_width;
		$layout_min_vertical	= -$layout_padding;
		$layout_max_vertical	=  $layout_padding * 2 + $layout_fullsize_height + $layout_legend_padding + $layout_legend_height;
		$layout_legend_top	=  $layout_fullsize_height + $layout_legend_padding;
	}
?>
<?php
	echo
"<!DOCTYPE HTML>
<html lang=\"" . $layout_language . "\">
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
		<title>" . $thispage_title_a . $thispage_title_b . "</title>
		<link rel=\"canonical\" href=\"" . $php_url . "\">
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_root . "favicon.png\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_root . "style_normalize.css\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
		<meta name=\"description\" content=\"" . $string_description . $game_name . ".\">
		<meta name=\"keywords\" content=\"visual,keyboard,keys,diagrams,charts,overlay,shortcuts,bindings,mapping,maps,controls,hotkeys,database,print,printable,video game,software,guide,reference," . $layout_keywords . "," . $game_name . "\">\n";
	echo writeAnalyticsTracking();
	echo
"		<style type=\"text/css\">\n";
	include("./lib/embed_" . $style_filename . ".css");
	echo
"		</style>\n";
?>
	</head>
	<body>
		<header>
			<div class="boxdiv"><h2><?php echo $thispage_title_a; ?><small><?php echo $thispage_title_b; ?></small></h2></div>
		</header>
		<main>
			<div class="svgdiv" style="position:relative;width:<?php echo $layout_max_horizontal; ?>px;height:<?php echo $layout_max_vertical; ?>px;">
				<iframe src="<?php echo $svg_url; ?>" width="<?php echo $layout_max_horizontal; ?>" height="<?php echo $layout_max_vertical; ?>" sandbox style="border:none;margin:0;padding:0;">
					<!--<img src="triangle.png" alt="Triangle with three unequal sides" />-->
				</iframe>
<?php
/*
	echo
"				<div style=\"position:absolute;left:600px;top:521.5px;width:728px;height:90px;padding:0;margin:0;z-index:10;\">\n";
	include($path_root . "ssi/adsense_horz_large.php");
	echo
"				</div>\n";
*/
?>
			</div>
			<div id="flxdiv">
<?php
	// combo
	if ($combo_count > 0)
	{
		echo
"			<div class=\"comdiv\">
				<h3>" . $string_combo . "</h3>
				<p>\n";
		for ($i = 0; $i < $combo_count; $i++)
		{
			$combo_row = $combo_table[$i];
			if ($combo_row[0] || $combo_row[1])
			{
				$combo_com = cleantextHTML($combo_row[0]);
				$combo_des = cleantextHTML($combo_row[1]);
				echo $combo_com . " = " . $combo_des . "<br>\n";
			}
		}
		echo
"				</p>
			</div>\n";
	}

	// mouse
	if ($mouse_count > 0)
	{
		echo
"			<div class=\"comdiv\">
				<h3>" . $string_mouse . "</h3>
				<p>\n";
		for ($i = 0; $i < $mouse_count; $i++)
		{
			$mouse_row = $mouse_table[$i];
			if ($mouse_row[0] || $mouse_row[1])
			{
				$mouse_com = cleantextHTML($mouse_row[0]);
				$mouse_des = cleantextHTML($mouse_row[1]);
				echo $mouse_com . " = " . $mouse_des . "<br>\n";
			}
		}
		echo
"				</p>
			</div>\n";
	}

	// joystick
	if ($joystick_count > 0)
	{
		echo
"			<div class=\"comdiv\">
				<h3>" . $string_joystick . "</h3>
				<p>\n";
		for ($i = 0; $i < $joystick_count; $i++)
		{
			$joystick_row = $joystick_table[$i];
			if ($joystick_row[0] || $joystick_row[1])
			{
				$joystick_com = cleantextHTML($joystick_row[0]);
				$joystick_des = cleantextHTML($joystick_row[1]);
				echo $joystick_com . " = " . $joystick_des . "<br>\n";
			}
		}
		echo
"				</p>
			</div>\n";
	}

	// note
	if ($note_count > 0)
	{
		echo
"			<div class=\"comdiv\">
				<h3>" . $string_note . "</h3>
				<p>\n";
		for ($i = 0; $i < $note_count; $i++)
		{
			$note_row = $note_table[$i];
			if ($note_row[0] || $note_row[1])
			{
				$note_com = cleantextHTML($note_row[0]);
				$note_des = cleantextHTML($note_row[1]);
				echo $note_des . "<br>\n";
			}
		}
		echo
"				</p>
			</div>\n";
	}

	// cheat
	if ($cheat_count > 0)
	{
		echo
"			<div class=\"comdiv\">
				<h3>" . $string_cheat . "</h3>
				<p>\n";
		for ($i = 0; $i < $cheat_count; $i++)
		{
			$cheat_row = $cheat_table[$i];
			if ($cheat_row[0] || $cheat_row[1])
			{
				$cheat_com = cleantextHTML($cheat_row[0]);
				$cheat_des = cleantextHTML($cheat_row[1]);
				echo $cheat_com . " = " . $cheat_des . "<br>\n";
			}
		}
		echo
"				</p>
			</div>\n";
	}

	// console
	if ($console_count > 0)
	{
		echo
"			<div class=\"comdiv\">
				<h3>" . $string_console . "</h3>
				<p>\n";
		for ($i = 0; $i < $console_count; $i++)
		{
			$console_row = $console_table[$i];
			if ($console_row[0] || $console_row[1])
			{
				$console_com = cleantextHTML($console_row[0]);
				$console_des = cleantextHTML($console_row[1]);
				echo $console_com . " = " . $console_des . "<br>\n";
			}
		}
		echo
"				</p>
			</div>\n";
	}

	// emote
	if ($emote_count > 0)
	{
		echo
"			<div class=\"comdiv\">
				<h3>" . $string_emote . "</h3>
				<p>\n";
		for ($i = 0; $i < $emote_count; $i++)
		{
			$emote_row = $emote_table[$i];
			if ($emote_row[0] || $emote_row[1])
			{
				$emote_com = cleantextHTML($emote_row[0]);
				$emote_des = cleantextHTML($emote_row[1]);
				echo $emote_com . " = " . $emote_des . "<br>\n";
			}
		}
		echo
"				</p>
			</div>\n";
	}
?>
			</div>
		</main>
		<footer>
<?php include("./keyboard-footer.php"); ?>
		</footer>
	</body>
</html>
