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

	$path_root		= "../";
	$path_file		= "./keyboard-html.php";

	header("Content-Type: text/html; charset=utf8");

	include($path_root . 'ssi/analyticstracking.php');
	include($path_root . "ssi/keyboard-connection.php");
	include("./keyboard-common.php");

	$con = mysqli_connect($con_website,$con_username,$con_password,$con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");

	$game_seo		= array_key_exists("seo", $_GET) ? $_GET["seo"] : null;
	$game_id		= array_key_exists("gam", $_GET) ? intval(ltrim($_GET["gam"], "0")) : null;
	$style_id		= array_key_exists("sty", $_GET) ? intval(ltrim($_GET["sty"], "0")) : null;
	$layout_id		= array_key_exists("lay", $_GET) ? intval(ltrim($_GET["lay"], "0")) : null;
	$format_id		= array_key_exists("fmt", $_GET) ? intval(ltrim($_GET["fmt"], "0")) : null;
	$svg_bool		= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0")) : null;
	$fix_url		= false;
	$php_url		= "";
	$svg_url		= "";
	$write_maximal_keys	= false;
	$show_advertisements	= false;
	$stylegroup_id		= 0;
	$position_table		= [];
	$keystyle_table		= [];
	$binding_table		= [];
	$legend_table		= [];
	$command_table		= [];
	$combo_table		= [];
	$mouse_table		= [];
	$joystick_table		= [];
	$note_table		= [];
	$cheat_table		= [];
	$console_table		= [];
	$emote_table		= [];
	$author_table		= [];
	$style_table		= [];
	$style_group_table	= [];
	$gamesrecord_id		= 0;
	$gamesrecord_author	= "";
	$stylesrecord_id	= 0;
	$stylesrecord_author	= "";
	$legend_count		= 12;
	$combo_count		= 0;
	$mouse_count		= 0;
	$joystick_count		= 0;
	$note_count		= 0;
	$cheat_count		= 0;
	$console_count		= 0;
	$emote_count		= 0;
	$style_filename		= "";
	$style_name		= "";
	$game_name		= "";
	$platform_name		= "";
	$layout_platform	= 0;
	$layout_name		= "";
	$layout_author		= "";
	$layout_keysnum		= 0;
	$layout_keygap		= 4;
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


	// validity checks
	if ($game_id === null)
	{
		if ($game_seo !== null)
		{
			$selectString = "SELECT g.game_name, g.game_id FROM games AS g WHERE g.game_friendlyurl = \"" . $game_seo . "\";";
			selectQuery($con, $selectString, "doGamesSEOHTML");
		}
		else
		{
			$game_id = 1;
		}
	}
	if ($game_seo === null)
	{
		$fix_url = true;
	}
	if ($style_id === null)
	{
		$style_id = 15;
		$fix_url = true;
	}
	if ($layout_id === null)
	{
		$layout_id = 1;
		$fix_url = true;
	}
	if ($format_id === null)
	{
		if ($svg_bool !== null)
		{
			$format_id = $svg_bool;
		}
		else
		{
			$format_id = 0;
		}
		$fix_url = true;
	}


	selGamesHTML();
	selAuthorsHTML();
	selStyleGroupsHTML();
	selStylesHTML();
	selThisStyleHTML();
	selPositionsHTML();
	selLayoutsHTML();
	selPlatformsHTML();
	selGamesRecordsHTML();
	selStylesRecordsHTML();
	selKeystylesHTML();
	selBindingsHTML();
	selLegendsHTML();
	selCommandsHTML();


	mysqli_close($con);

	// validity checks
	$game_seo		= $game_seo ? $game_seo : "unrecognized-game";
	$temp_game_name		= $game_name ? $game_name : "Unrecognized Game";
	$temp_layout_name	= $layout_name ? $layout_name : "Unrecognized Layout";
	$temp_style_name	= $style_name ? $style_name : "Unrecognized Style";
	$temp_platform_name	= $platform_name ? $platform_name : "Unrecognized Platform";
	$thispage_title_a	= $temp_game_name;
	$thispage_title_b	= " - " . $string_title . " - " . $temp_platform_name . " " . $temp_layout_name . " - " . $temp_style_name . " - GRID:" . $gamesrecord_id;

	// validity checks (should check the layout here too... but)
	if (!checkStyle($style_id))
	{
		$style_id = 15;
		$fix_url = true;
	}

	$php_url = "http://isometricland.net/keyboard/keyboard-diagram-" . $game_seo . ".php?sty=" . $style_id . "&lay=" . $layout_id . "&fmt=" . $format_id;
	$svg_url = "http://isometricland.net/keyboard/keyboard-diagram-" . $game_seo . ".svg?sty=" . $style_id . "&lay=" . $layout_id . "&fmt=" . $format_id;

	// fix URL
	if ($fix_url === true)
	{
		header("Location: " . $php_url);
		die();
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
<?php
	echo
"		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
		<title>" . $thispage_title_a . $thispage_title_b . "</title>
		<link rel=\"canonical\" href=\"" . $php_url . "\"/>
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_root . "favicon.png\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_root . "style_normalize.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"./style_common.css\"/>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\"" . $string_description . $temp_game_name . ". (" . $temp_style_name . ")\"/>
		<meta name=\"keywords\" content=\"" . $temp_game_name . "," . $temp_style_name . "," . "HTML" . "," . $string_keywords . "\"/>
		<script src=\"keyboard-chart-js.php\"></script>\n";
	echo writeAnalyticsTracking();
	echo
"		<style type=\"text/css\">\n";
	include("./html_" . $style_filename . ".css");
	echo
"		</style>\n";
?>
	</head>
	<body>
		<header>
			<div class="bodiv">
				<h2><?php echo $thispage_title_a; ?><small><?php echo $thispage_title_b; ?></small></h2>
			</div>
		</header>
		<main>
			<div class="bodiv" style="width:1660px;height:480px;">
				<div id="keydiv" style="position:relative;width:1660px;height:480px;">
<?php
	// validity checks
	if (!$gamesrecord_id)
	{
		echo
"					<h3>No bindings found for game \"" . $temp_game_name . "\" on layout \"" . $temp_platform_name . " " . $temp_layout_name . "\".</h3>";
	}
	// validity checks
	if (!$stylesrecord_id)
	{
		echo
"					<h3>No configurations found for style \"" . $temp_style_name . "\" on layout \"" . $temp_platform_name . " " . $temp_layout_name . "\".</h3>";
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
				$key_low	= $position_row[4];
				$key_hgh	= $position_row[5];
				$key_rgt	= $position_row[6];
				$key_opt	= $position_row[8];
				$img_wid	= 48;
				$img_hgh	= 48;
				$img_pos_x	= $pos_wid/2 - $img_wid/2;
				$img_pos_y	= $pos_hgh/2 - $img_hgh/2;
				if (array_key_exists($i, $binding_table))
				{
					$binding_row	= $binding_table[$i];
					$bkg_nor = getcolor($binding_row[0]);
					$cap_nor = $binding_row[1];
					$bkg_shf = getcolor($binding_row[2]);
					$cap_shf = $binding_row[3];
					$bkg_ctl = getcolor($binding_row[4]);
					$cap_ctl = $binding_row[5];
					$bkg_alt = getcolor($binding_row[6]);
					$cap_alt = $binding_row[7];
					$bkg_agr = getcolor($binding_row[8]);
					$cap_agr = $binding_row[9];
					$bkg_xtr = getcolor($binding_row[10]);
					$cap_xtr = $binding_row[11];
					$img_fil = $binding_row[12];
					$img_uri = $binding_row[13];
				}
				else
				{
					$bkg_nor = "non";
					$cap_nor = "";
					$bkg_shf = "non";
					$cap_shf = "";
					$bkg_ctl = "non";
					$cap_ctl = "";
					$bkg_alt = "non";
					$cap_alt = "";
					$bkg_agr = "non";
					$cap_agr = "";
					$bkg_xtr = "non";
					$cap_xtr = "";
					$img_fil = "";
					$img_uri = "";
				}
				// key outer container
				echo
"					<div id=\"keyout_" . $i . "\" class=\"keyout cap" . $bkg_nor . " " . $key_sty . "\" style=\"left:" . $pos_lft . "px;top:" . $pos_top . "px;width:" . $pos_wid . "px;height:" . $pos_hgh . "px;background-size:auto;\">\n";
				// icon images
				if (($img_uri != "") || ($write_maximal_keys == true))
				{
					if ($img_uri == "")
					{
						$display = "none";
					}
					else
					{
						$display = "block";
					}
					echo
"						<img id=\"capimg_" . $i . "\" class=\"capimg\" style=\"left:" . $img_pos_x . "px;top:" . $img_pos_y . "px;width:" . $img_wid . "px;height:" . $img_hgh . "px;display:" . $display . ";\" src=\"" . $img_uri . "\"/>\n";
				}
				// key characters
				if (($key_hgh != "") || ($write_maximal_keys == true))
				{
					print_key_html("keyhgh_" . $i, "keyhgh", null, $key_hgh);
				}
				if (($key_low != "") || ($write_maximal_keys == true))
				{
					if ($key_opt == false)
					{
						print_key_html("keylow_" . $i, "keylow", null, $key_low);
					}
					else
					{
						print_key_html("keylow_" . $i, "keynon", null, $key_low);
					}
				}
				if (($key_rgt != "") || ($write_maximal_keys == true))
				{
					print_key_html("keyrgt_" . $i, "keyrgt", null, $key_rgt);
				}
				// key captions
				if (($cap_nor != "") || ($write_maximal_keys == true))
				{
					if ($key_opt == false)
					{
						print_key_html("capnor_" . $i, "capopf", $bkg_nor, $cap_nor);
					}
					else
					{
						print_key_html("capnor_" . $i, "capopt", $bkg_nor, $cap_nor);
					}
				}
				if (($cap_shf != "") || ($write_maximal_keys == true))
				{
					print_key_html("capshf_" . $i, "capshf", $bkg_shf, $cap_shf);
				}
				if (($cap_ctl != "") || ($write_maximal_keys == true))
				{
					print_key_html("capctl_" . $i, "capctl", $bkg_ctl, $cap_ctl);
				}
				if (($cap_alt != "") || ($write_maximal_keys == true))
				{
					print_key_html("capalt_" . $i, "capalt", $bkg_alt, $cap_alt);
				}
				if (($cap_agr != "") || ($write_maximal_keys == true))
				{
					print_key_html("capagr_" . $i, "capagr", $bkg_agr, $cap_agr);
				}
				if (($cap_xtr != "") || ($write_maximal_keys == true))
				{
					print_key_html("capxtr_" . $i, "capxtr", $bkg_xtr, $cap_xtr);
				}
				echo
"					</div>\n";
			}
		}
	}
?>
				</div>
			</div>
			<div class="bodiv">
				<div class="inbtop" style="margin-bottom:1em;">
					<div class="keyout capnon" style="position:relative;left:2px;top:2px;width:68px;height:68px;">
						<div class="keyhgh">Upcase</div>
						<div class="keylow">Lowcase</div>
						<div class="capopf">Caption</div>
						<div class="capshf">Shift</div>
						<div class="capctl">Ctrl</div>
						<div class="capalt">Alt</div>
					</div>
				</div>
<?php
	// legend
	if ($stylegroup_id == 1)
	{
		echo
"				<div class=\"inbtop\" style=\"margin-left:1em;\">\n";
		// $legend_count is hardcoded as 12!
		for ($i = 0; $i < $legend_count; $i++)
		{
			if ($i % 3 == 0)
			{
				echo
"					<div class=\"legtbl inbtop\">\n";
			}
			if (isset($legend_table[$i]))
			{
				$leg_color = getcolor($legend_table[$i][0]);
				$leg_value = cleantextHTML($legend_table[$i][1]);
				echo
"						<div class=\"legrow\"><div class=\"legcll legbox leg" . $leg_color . "\"></div><div class=\"legcll legtxt\">" . $leg_value . "</div></div>\n";
			}
			else
			{
				echo
"						<div class=\"legrow\"><div class=\"legcll\"></div><div class=\"legcll\"></div></div>\n";
			}
			if ($i % 3 == 2)
			{
				echo
"					</div>\n";
			}
		}
		echo
"				</div>\n";
	}
	if ($show_advertisements === true)
	{
		echo
"				<div class=\"inbtop\" style=\"margin-left:1em;\">";
		include($path_root . "ssi/adsense_horz_large.php");
		echo
"				</div>\n";
	}
?>
			</div>
<?php
	// combo
	if ($combo_count > 0)
	{
		echo
"			<div class=\"bodiv inbtop combox\">
				<h3>" . $string_combo . "</h3>
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
"				</p>
			</div>\n";
	}

	// mouse
	if ($mouse_count > 0)
	{
		echo
"			<div class=\"bodiv inbtop combox\">
				<h3>" . $string_mouse . "</h3>
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
"				</p>
			</div>\n";
	}

	// joystick
	if ($joystick_count > 0)
	{
		echo
"			<div class=\"bodiv inbtop combox\">
				<h3>" . $string_joystick . "</h3>
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
"				</p>
			</div>\n";
	}

	// note
	if ($note_count > 0)
	{
		echo
"			<div class=\"bodiv inbtop combox\">
				<h3>" . $string_note . "</h3>
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
"				</p>
			</div>\n";
	}

	// cheat
	if ($cheat_count > 0)
	{
		echo
"			<div class=\"bodiv inbtop combox\">
				<h3>" . $string_cheat . "</h3>
				<p>\n";
		for ($i = 0; $i < $cheat_count; $i++)
		{
			$cheat_row = $cheat_table[$i];
			if ($cheat_row[0] || $cheat_row[1])
			{
				$cheat_com = cleantextHTML($cheat_row[0]);
				$cheat_des = cleantextHTML($cheat_row[1]);
				echo
"					" . $cheat_com . " = " . $cheat_des . "<br/>\n";
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
"			<div class=\"bodiv inbtop combox\">
				<h3>" . $string_console . "</h3>
				<p>\n";
		for ($i = 0; $i < $console_count; $i++)
		{
			$console_row = $console_table[$i];
			if ($console_row[0] || $console_row[1])
			{
				$console_com = cleantextHTML($console_row[0]);
				$console_des = cleantextHTML($console_row[1]);
				echo
"					" . $console_com . " = " . $console_des . "<br/>\n";
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
"			<div class=\"bodiv inbtop combox\">
				<h3>" . $string_emote . "</h3>
				<p>\n";
		for ($i = 0; $i < $emote_count; $i++)
		{
			$emote_row = $emote_table[$i];
			if ($emote_row[0] || $emote_row[1])
			{
				$emote_com = cleantextHTML($emote_row[0]);
				$emote_des = cleantextHTML($emote_row[1]);
				echo
"					" . $emote_com . " = " . $emote_des . "<br/>\n";
			}
		}
		echo
"				</p>
			</div>\n";
	}
?>
		</main>
		<footer>
<?php include("./keyboard-footer.php"); ?>
		</footer>
	</body>
</html>
