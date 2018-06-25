<?php
	// Copyright (C) 2018  Michael Horvath
	//
	// This program is free software: you can redistribute it and/or modify
	// it under the terms of the GNU General Public License as published by
	// the Free Software Foundation, either version 3 of the License, or
	// any later version.
	// 
	// This program is distributed in the hope that it will be useful,
	// but WITHOUT ANY WARRANTY; without even the implied warranty of
	// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	// GNU General Public License for more details.
	// 
	// You should have received a copy of the GNU General Public License
	// along with this program. If not, see <https://www.gnu.org/licenses/>.

	$path_root		= "../";

	header("Content-Type: text/html; charset=utf8");

	include($path_root. "ssi/keyboard-connection.php");
	include("./keyboard-common.php");

	$con = mysqli_connect($con_website,$con_username,$con_password,$con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");

	$path_root		= "../";
	$game_seo		= array_key_exists("seo", $_GET) ? $_GET["seo"] : null;
	$game_id		= array_key_exists("gam", $_GET) ? intval(ltrim($_GET["gam"], "0")) : null;
	$style_id		= array_key_exists("sty", $_GET) ? intval(ltrim($_GET["sty"], "0")) : null;
	$layout_id		= array_key_exists("lay", $_GET) ? intval(ltrim($_GET["lay"], "0")) : null;
	$format_id		= array_key_exists("fmt", $_GET) ? intval(ltrim($_GET["fmt"], "0")) : null;
	$svg_bool		= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0")) : null;
	$fix_url		= false;
	$php_url		= "";
	$svg_url		= "";
	$write_maximal_keys	= true;
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
	$layout_title		= cleantextHTML("Video Game Keyboard Diagrams");
	$layout_combo		= cleantextHTML("Keyboard Combinations");
	$layout_mouse		= cleantextHTML("Mouse Controls");
	$layout_joystick	= cleantextHTML("Joystick/Gamepad Controls");
	$layout_note		= cleantextHTML("Additional Notes");
	$layout_cheat		= cleantextHTML("Cheat Codes");
	$layout_console		= cleantextHTML("Console Commands");
	$layout_emote		= cleantextHTML("Chat Commands/Emotes");
	$layout_description	= cleantextHTML("Keyboard hotkey & binding chart for ");
	$layout_keywords	= cleantextHTML("English,keyboard,keys,diagram,chart,overlay,shortcut,binding,mapping,map,controls,hotkeys,database,print,printable,video game,software,visual,guide,reference");

	include($path_root . 'ssi/recaptchakey.php');

	// validity checks
	if ($game_id === null)
	{
		if ($game_seo !== null)
		{
			callProcedure1Txt($con, "get_games_friendly_chart", "doGamesSEO", $game_seo);
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
			$format_id = 3;
		}
		$fix_url = true;
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
		for ($i = 0; $i < 12; $i++)
		{
			$legend_table[$i] = null;
		}
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// legend_group, legend_description
			$legend_table[$temp_row[0]-1] = $temp_row;
		}
	}
	function doCommands($in_result)
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
	function doPositions($in_result)
	{
		global $position_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// position_left, position_top, position_width, position_height, symbol_low, symbol_cap, symbol_altgr, key_number, lowkey_optional
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
	// need to move most of this stuff to `doLanguages`
	function doLayouts($in_result)
	{
		global $layout_platform, $layout_name, $layout_author, $layout_keysnum;
		$layout_row		= mysqli_fetch_row($in_result);
		$layout_platform	= $layout_row[0];
		$layout_name		= cleantextHTML($layout_row[1]);
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
	$thispage_title_b	= " - VGKD Submission Editor (under construction) - " . $temp_platform_name . " " . $temp_layout_name . " - " . $temp_style_name;

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
		<meta name=\"description\" content=\"" . $layout_description . $temp_game_name . ". (" . $temp_style_name . ")\"/>
		<meta name=\"keywords\" content=\"" . $temp_game_name . "," . $temp_style_name . "," . "HTML" . "," . $layout_keywords . "\"/>
";
	include($path_root . "ssi/analyticstracking.php");
	echo
"		<style type=\"text/css\">\n";
	include("./html_" . $style_filename . ".css");
	echo
"		</style>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"./style_submit.css\"/>
		<script src=\"" . $path_root . "java/jquery-3.3.1.min.js\"></script>
		<script src=\"./keyboard-chart-js.php\"></script>
		<script src=\"./keyboard-submit-js.php\"></script>
		<script src=\"https://www.google.com/recaptcha/api.js\"></script>
		<script>
var record_id = " . $gamesrecord_id . ";
var legend_table = [];
var combo_table = [];
var mouse_table = [];
var joystick_table = [];
var note_table = [];
var cheat_table = [];
var console_table = [];
var emote_table = [];
var binding_table =
[
";
	if ($gamesrecord_id && $stylesrecord_id)
	{
		for ($i = 0; $i < $layout_keysnum; $i++)
		{
			if (array_key_exists($i, $position_table))
			{
				$position_row	= $position_table[$i];
				$key_low	= "\"" . cleantextJS($position_row[ 4]) . "\"";
				$key_hgh	= "\"" . cleantextJS($position_row[ 5]) . "\"";
				$key_rgt	= "\"" . cleantextJS($position_row[ 6]) . "\"";
				if (array_key_exists($i, $binding_table))
				{
					$binding_row = $binding_table[$i];
					$cap_nor = "\"" . cleantextJS($binding_row[ 1]) . "\"";
					$cap_shf = "\"" . cleantextJS($binding_row[ 3]) . "\"";
					$cap_ctl = "\"" . cleantextJS($binding_row[ 5]) . "\"";
					$cap_alt = "\"" . cleantextJS($binding_row[ 7]) . "\"";
					$cap_agr = "\"" . cleantextJS($binding_row[ 9]) . "\"";
					$cap_xtr = "\"" . cleantextJS($binding_row[11]) . "\"";
					$img_fil = "\"" . $binding_row[12] . "\"";
					$img_uri = "\"" . $binding_row[14] . "\"";
					$bkg_nor = intval($binding_row[ 0]);
					$bkg_shf = intval($binding_row[ 2]);
					$bkg_ctl = intval($binding_row[ 4]);
					$bkg_alt = intval($binding_row[ 6]);
					$bkg_agr = intval($binding_row[ 8]);
					$bkg_xtr = intval($binding_row[10]);
				}
				else
				{
					$cap_nor = "\"\"";
					$cap_shf = "\"\"";
					$cap_ctl = "\"\"";
					$cap_alt = "\"\"";
					$cap_agr = "\"\"";
					$cap_xtr = "\"\"";
					$img_fil = "\"\"";
					$img_uri = "\"\"";
					$bkg_nor = 0;
					$bkg_shf = 0;
					$bkg_ctl = 0;
					$bkg_alt = 0;
					$bkg_agr = 0;
					$bkg_xtr = 0;
				}
				echo
				"	[" .
				$key_low . ", " .
				$key_hgh . ", " .
				$key_rgt . ", " .
				$cap_nor . ", " .
				$cap_shf . ", " .
				$cap_ctl . ", " .
				$cap_alt . ", " .
				$cap_agr . ", " .
				$cap_xtr . ", " .
				$img_fil . ", " .
				$img_uri . ", " .
				$bkg_nor . ", " .
				$bkg_shf . ", " .
				$bkg_ctl . ", " .
				$bkg_alt . ", " .
				$bkg_agr . ", " .
				$bkg_xtr . "]";
			}
			else
			{
				echo
"	null";
			}
			if ($i < $layout_keysnum - 1)
			{
				echo ",\n";
			}
			else
			{
				echo "\n";
			}
		}
	}
	echo
"];
</script>\n";
?>
	</head>
	<body onload="init_submissions();">
		<img id="waiting" src="loading.gif" alt="loading" style="position:fixed;display:block;z-index:10;width:100px;height:100px;left:50%;top:50%;margin-top:-50px;margin-left:-50px;"/>
		<div id="butt_min" class="side_butt" title="Toggle Side Panel" onclick="toggle_left_pane(0);"><img src="./icon_min.png"/></div>
		<div id="butt_max" class="side_butt" title="Toggle Side Panel" onclick="toggle_left_pane(1);"><img src="./icon_max.png"/></div>
		<div id="pane_lft">
			<div id="tbar_lft">
				<div id="butt_inp" class="tabs_butt" title="Toggle Input Panel" onclick="switch_left_pane(0);"><img src="./icon_inp.png"/></div>
				<div id="butt_hlp" class="tabs_butt" title="Toggle Info Panel"  onclick="switch_left_pane(1);"><img src="./icon_hlp.png"/></div>
			</div>
			<div id="pane_inp" style="display:block;">
				<div id="table_inp" class="inptbl" style="margin:auto;">
					<div class="inprow"><div class="inphed">Param</div><div class="inphed">Value</div><div class="inphed">Col</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_keynum" title="Key ID#"		>keynum:</label></div><div class="inpcll"><input id="inp_keynum" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Key ID"		disabled/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_keylow" title="Lower case"	>keylow:</label></div><div class="inpcll"><input id="inp_keylow" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Lower case"	disabled/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_keyhgh" title="Upper case"	>keyhgh:</label></div><div class="inpcll"><input id="inp_keyhgh" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Upper case"	disabled/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_keyrgt" title="AltGr case"	>keyrgt:</label></div><div class="inpcll"><input id="inp_keyrgt" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="AltGr case"	disabled/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capnor" title="Normal"		>capnor:</label></div><div class="inpcll"><input id="inp_capnor" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Normal"		/></div><div class="inpcll"><select id="sel_capnor" class="selnon" size="1" autocomplete="off"><option class="optnon">non</option><option class="optred">red</option><option class="optyel">yel</option><option class="optgrn">grn</option><option class="optcyn">cyn</option><option class="optblu">blu</option><option class="optmag">mag</option><option class="optwht">wht</option><option class="optgry">gry</option><option class="optblk">blk</option><option class="optorg">org</option><option class="optolv">olv</option><option class="optbrn">brn</option></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capshf" title="Shift"		>capshf:</label></div><div class="inpcll"><input id="inp_capshf" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Shift"		/></div><div class="inpcll"><select id="sel_capshf" class="selnon" size="1" autocomplete="off"><option class="optnon">non</option><option class="optred">red</option><option class="optyel">yel</option><option class="optgrn">grn</option><option class="optcyn">cyn</option><option class="optblu">blu</option><option class="optmag">mag</option><option class="optwht">wht</option><option class="optgry">gry</option><option class="optblk">blk</option><option class="optorg">org</option><option class="optolv">olv</option><option class="optbrn">brn</option></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capctl" title="Control"		>capctl:</label></div><div class="inpcll"><input id="inp_capctl" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Control"	/></div><div class="inpcll"><select id="sel_capctl" class="selnon" size="1" autocomplete="off"><option class="optnon">non</option><option class="optred">red</option><option class="optyel">yel</option><option class="optgrn">grn</option><option class="optcyn">cyn</option><option class="optblu">blu</option><option class="optmag">mag</option><option class="optwht">wht</option><option class="optgry">gry</option><option class="optblk">blk</option><option class="optorg">org</option><option class="optolv">olv</option><option class="optbrn">brn</option></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capalt" title="Alt"		>capalt:</label></div><div class="inpcll"><input id="inp_capalt" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Alt"		/></div><div class="inpcll"><select id="sel_capalt" class="selnon" size="1" autocomplete="off"><option class="optnon">non</option><option class="optred">red</option><option class="optyel">yel</option><option class="optgrn">grn</option><option class="optcyn">cyn</option><option class="optblu">blu</option><option class="optmag">mag</option><option class="optwht">wht</option><option class="optgry">gry</option><option class="optblk">blk</option><option class="optorg">org</option><option class="optolv">olv</option><option class="optbrn">brn</option></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capagr" title="AltGr"		>capagr:</label></div><div class="inpcll"><input id="inp_capagr" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="AltGr"		/></div><div class="inpcll"><select id="sel_capagr" class="selnon" size="1" autocomplete="off"><option class="optnon">non</option><option class="optred">red</option><option class="optyel">yel</option><option class="optgrn">grn</option><option class="optcyn">cyn</option><option class="optblu">blu</option><option class="optmag">mag</option><option class="optwht">wht</option><option class="optgry">gry</option><option class="optblk">blk</option><option class="optorg">org</option><option class="optolv">olv</option><option class="optbrn">brn</option></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capxtr" title="Extra"		>capxtr:</label></div><div class="inpcll"><input id="inp_capxtr" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Extra"		/></div><div class="inpcll"><select id="sel_capxtr" class="selnon" size="1" autocomplete="off"><option class="optnon">non</option><option class="optred">red</option><option class="optyel">yel</option><option class="optgrn">grn</option><option class="optcyn">cyn</option><option class="optblu">blu</option><option class="optmag">mag</option><option class="optwht">wht</option><option class="optgry">gry</option><option class="optblk">blk</option><option class="optorg">org</option><option class="optolv">olv</option><option class="optbrn">brn</option></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_imgfil" title="Image filename"	>imgfil:</label></div><div class="inpcll"><input id="inp_imgfil" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Image filename"	/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_imguri" title="Image data-URI"	>imguri:</label></div><div class="inpcll"><input id="inp_imguri" class="inplft" type="text" size="11"                 autocomplete="off" title="Image data-URI"	/></div><div class="inpcll">n/a</div></div>
				</div>
				<div id="button_inp" style="margin:1em;">
					<button id="set_key_button" type="button" style="padding:0.3em 1em;" autocomplete="off" disabled="disabled" onclick="key_save_changes();" title="Save changes to key">Set Key</button><button id="unset_key_button" type="button" style="padding:0.3em 1em;" autocomplete="off" disabled="disabled" onclick="key_revert_changes();" title="Revert changes to key">Revert</button>
				</div>
				<p>Enter new lines by typing <code>\n</code>.</p>
				<hr/>
				<form id="email_form" method="post" enctype="multipart/form-data" action="">
					<div id="email_table" class="emltbl inbtop" style="margin:auto;">
						<div class="emlrow">
							<div class="emlcll">Name:</div>
							<div class="emlcll"><input class="email_input" type="text" name="email_1" id="email_1" placeholder="First and last name" required autocomplete="on" data-lpignore="true"/></div>
						</div>
						<div class="emlrow">
							<div class="emlcll">Email:</div>
							<div class="emlcll"><input class="email_input" type="email" name="email_2" id="email_2" placeholder="Return email address" required autocomplete="on" data-lpignore="true"/></div>
						</div>
						<div class="emlrow">
							<div class="emlcll">Messg:</div>
							<div class="emlcll"><textarea class="email_textarea" name="email_3" id="email_3" placeholder="Message to admin" required autocomplete="off"></textarea></div>
						</div>
					</div>
					<div id="email_recaptcha" class="g-recaptcha" data-sitekey="<?php echo writeRecaptchaKey(); ?>"></div>
					<p style="text-align:left;">For human verification purposes, please click the checkbox labeled "I'm not a robot".</p>
					<input name="email_4" id="email_4" type="hidden" value=""/>
					<input name="email_5" id="email_5" type="hidden" value=""/>
					<input name="email_6" id="email_6" type="hidden" value=""/>
					<input name="email_7" id="email_7" type="hidden" value=""/>
					<div><button id="set_doc_button" type="button" style="padding:0.3em 1em;" disabled="disabled" autocomplete="off" onclick="document_save_changes();" title="Submit changes to data">Submit Data</button><button id="unset_doc_button" type="button" style="padding:0.3em 1em;" disabled="disabled" autocomplete="off" onclick="document_revert_changes();" title="Reset data to original state">Reset</button></div>
				</form>
			</div>
			<div id="pane_hlp" style="display:none;">
				<h2>Help</h2>
				<p>Enter new lines by typing <code>\n</code>.</p>
				<p>You can use an online service such as <a target="_blank" href="https://websemantics.uk/tools/image-to-data-uri-converter/">Data-URI base64 converter</a> to convert PNG images to data-URIs.</p>
				<p>Image data-URIs must be less than 256KiB in size.</p>
				<p>Images must be 48x48px and 32 bits.</p>
				<p>Image file names must be paired along with image data-URIs.</p>
				<p>"<a href="./keyboard.php">Video Game Keyboard Diagrams</a>" software was created by Michael Horvath and is licensed under <a target="_blank" rel="license" href="https://creativecommons.org/licenses/LGPL/2.1/;/">CC LGPL 2.1</a>. Content is licensed under <a target="_blank" href="https://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a>. You can find this project on <a target="_blank" href="https://github.com/mjhorvath/vgkd">GitHub</a>.</p>
			</div>
		</div>
		<div id="pane_rgt">
			<div id="tbar_rgt">
				<div id="butt_kbd" class="tabs_butt" title="Toggle Keyboard Panel"    onclick="switch_right_pane(0);"><img src="./icon_kbd.png"/></div>
				<div id="butt_csv" class="tabs_butt" title="Toggle Spreadsheet Panel" onclick="switch_right_pane(1);"><img src="./icon_spd.png"/></div>
			</div>
			<div id="pane_kbd" style="display:block;">
				<div class="bodiv">
					<input id="game_tit" type="text" size="25" maxlength="100" placeholder="Game Title" title="Game Title" style="font-size:x-large;" autocomplete="off" onchange="flag_as_dirty();" value="<?php echo $thispage_title_a; ?>"/>
					<input id="game_url" type="text" size="25" maxlength="100" placeholder="URL String" title="URL String" style="font-size:x-large;" autocomplete="off" onchange="flag_as_dirty();" value="<?php echo $game_seo; ?>" disabled="disabled"/>
				</div>
				<div class="bodiv" style="position:relative;width:1660px;height:480px;">
					<div id="keydiv" style="position:relative;width:1660px;height:480px;">
<?php
	// validity checks
	if (!$gamesrecord_id)
	{
		echo
"					<h3>No bindings found for game \"" . $temp_game_name . "\" on layout \"" . $temp_platform_name . " " . $temp_layout_name . "\".</h3>";
	}
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
				// position_left, position_top, position_width, position_height, symbol_low, symbol_cap, symbol_altgr, key_number, lowkey_optional
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
					$img_uri = $binding_row[14];
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
					<div class="inbtop" style="margin-right:1em;margin-bottom:1em;">
						<div class="keyout capnon" style="position:relative;left:2px;top:2px;width:68px;height:68px;">
							<div class="keyhgh">Upcase</div>
							<div class="keylow">Lowcase</div>
							<div class="capopf">Caption</div>
							<div class="capshf">Shift</div>
							<div class="capctl">Ctrl</div>
							<div class="capalt">Alt</div>
						</div>
					</div>
					<div id="table_legend" class="inbtop">
<?php
	// legend - $legend_count is hardcoded as 12!
	for ($i = 0; $i < $legend_count; $i++)
	{
		if ($i % 3 == 0)
		{
			echo
"						<div class=\"legtbl inbtop\">\n";
		}
		if ($legend_table[$i] !== null)
		{
			$leg_color = getcolor($legend_table[$i][0]);
			$leg_value = cleantextHTML($legend_table[$i][1]);
			echo
"							<div class=\"legrow\"><div class=\"legcll legbox leg" . $leg_color . "\">" . $leg_color . "</div><div class=\"legcll legtxt\"><input id=\"form_cap" . $leg_color . "\" type=\"text\" size=\"15\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $leg_value . "\"/></div></div>\n";
		}
		else
		{
			$leg_color = getcolor($i+1);
			$leg_value = "";
			echo
"							<div class=\"legrow\"><div class=\"legcll legbox leg" . $leg_color . "\">" . $leg_color . "</div><div class=\"legcll legtxt\"><input id=\"form_cap" . $leg_color . "\" type=\"text\" size=\"15\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $leg_value . "\"/></div></div>\n";
		}
		if ($i % 3 == 2)
		{
			echo
"						</div>\n";
		}
	}
?>
					</div>
				</div>
				<div>
					<div class="bodiv inbtop combox">
						<h3><?php echo $layout_combo; ?></h3>
						<div id="table_combo" class="nottbl">
<?php
		// combo
		for ($i = 0; $i < $combo_count; $i++)
		{
			$combo_row = $combo_table[$i];
			if ($combo_row[0] || $combo_row[1])
			{
				$combo_com = cleantextHTML($combo_row[0]);
				$combo_des = cleantextHTML($combo_row[1]);
				echo
"							<div class=\"notrow\">
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $combo_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $combo_des . "\"/></div>
								<div class=\"notcll cllfor\"><button class=\"butsub\" type=\"button\">-</button></div>
							</div>\n";
			}
		}
?>
							<div class="notrow">
								<div class="notcll cllone"></div>
								<div class="notcll clltwo"></div>
								<div class="notcll cllthr"></div>
								<div class="notcll cllfor"><button class="butadd" type="button">+</button></div>
							</div>
						</div>
					</div>
					<div class="bodiv inbtop combox">
						<h3><?php echo $layout_mouse; ?></h3>
						<div id="table_mouse" class="nottbl">
<?php
		// mouse
		for ($i = 0; $i < $mouse_count; $i++)
		{
			$mouse_row = $mouse_table[$i];
			if ($mouse_row[0] || $mouse_row[1])
			{
				$mouse_com = cleantextHTML($mouse_row[0]);
				$mouse_des = cleantextHTML($mouse_row[1]);
				echo
"							<div class=\"notrow\">
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $mouse_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $mouse_des . "\"/></div>
								<div class=\"notcll cllfor\"><button class=\"butsub\" type=\"button\">-</button></div>
							</div>\n";
			}
		}
?>
							<div class="notrow">
								<div class="notcll cllone"></div>
								<div class="notcll clltwo"></div>
								<div class="notcll cllthr"></div>
								<div class="notcll cllfor"><button class="butadd" type="button">+</button></div>
							</div>
						</div>
					</div>
					<div class="bodiv inbtop combox">
						<h3><?php echo $layout_joystick; ?></h3>
						<div id="table_joystick" class="nottbl">
<?php
		// joystick
		for ($i = 0; $i < $joystick_count; $i++)
		{
			$joystick_row = $joystick_table[$i];
			if ($joystick_row[0] || $joystick_row[1])
			{
				$joystick_com = cleantextHTML($joystick_row[0]);
				$joystick_des = cleantextHTML($joystick_row[1]);
				echo
"							<div class=\"notrow\">
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $joystick_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $joystick_des . "\"/></div>
								<div class=\"notcll cllfor\"><button class=\"butsub\" type=\"button\">-</button></div>
							</div>\n";
			}
		}
?>
							<div class="notrow">
								<div class="notcll cllone"></div>
								<div class="notcll clltwo"></div>
								<div class="notcll cllthr"></div>
								<div class="notcll cllfor"><button class="butadd" type="button">+</button></div>
							</div>
						</div>
					</div>
					<div class="bodiv inbtop combox">
						<h3><?php echo $layout_note; ?></h3>
						<div id="table_note" class="nottbl">
<?php
		// note
		for ($i = 0; $i < $note_count; $i++)
		{
			$note_row = $note_table[$i];
			if ($note_row[0] || $note_row[1])
			{
				$note_com = cleantextHTML($note_row[0]);
				$note_des = cleantextHTML($note_row[1]);
				echo
"							<div class=\"notrow\">
								<div class=\"notcll txtone\"><textarea placeholder=\"blah\" onchange=\"flag_as_dirty();\">" . $note_des . "</textarea></div>
								<div class=\"notcll txttwo\"><button class=\"txtsub\" type=\"button\">-</button></div>
							</div>\n";
			}
		}
?>
							<div class="notrow">
								<div class="notcll txtone"></div>
								<div class="notcll txttwo"><button class="txtadd" type="button">+</button></div>
							</div>
						</div>
					</div>
					<div class="bodiv inbtop combox">
						<h3><?php echo $layout_cheat; ?></h3>
						<div id="table_cheat" class="nottbl">
<?php
		// cheat
		for ($i = 0; $i < $cheat_count; $i++)
		{
			$cheat_row = $cheat_table[$i];
			if ($cheat_row[0] || $cheat_row[1])
			{
				$cheat_com = cleantextHTML($cheat_row[0]);
				$cheat_des = cleantextHTML($cheat_row[1]);
				echo
"							<div class=\"notrow\">
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $cheat_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $cheat_des . "\"/></div>
								<div class=\"notcll cllfor\"><button class=\"butsub\" type=\"button\">-</button></div>
							</div>\n";
			}
		}
?>
							<div class="notrow">
								<div class="notcll cllone"></div>
								<div class="notcll clltwo"></div>
								<div class="notcll cllthr"></div>
								<div class="notcll cllfor"><button class="butadd" type="button">+</button></div>
							</div>
						</div>
					</div>
					<div class="bodiv inbtop combox">
						<h3><?php echo $layout_console; ?></h3>
						<div id="table_console" class="nottbl">
<?php
		// console
		for ($i = 0; $i < $console_count; $i++)
		{
			$console_row = $console_table[$i];
			if ($console_row[0] || $console_row[1])
			{
				$console_com = cleantextHTML($console_row[0]);
				$console_des = cleantextHTML($console_row[1]);
				echo
"							<div class=\"notrow\">
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $console_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $console_des . "\"/></div>
								<div class=\"notcll cllfor\"><button class=\"butsub\" type=\"button\">-</button></div>
							</div>\n";
			}
		}
?>
							<div class="notrow">
								<div class="notcll cllone"></div>
								<div class="notcll clltwo"></div>
								<div class="notcll cllthr"></div>
								<div class="notcll cllfor"><button class="butadd" type="button">+</button></div>
							</div>
						</div>
					</div>
					<div class="bodiv inbtop combox">
						<h3><?php echo $layout_emote; ?></h3>
						<div id="table_emote" class="nottbl">
<?php
		// emote
		for ($i = 0; $i < $emote_count; $i++)
		{
			$emote_row = $emote_table[$i];
			if ($emote_row[0] || $emote_row[1])
			{
				$emote_com = cleantextHTML($emote_row[0]);
				$emote_des = cleantextHTML($emote_row[1]);
				echo
"							<div class=\"notrow\">
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $emote_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" placeholder=\"blah\" autocomplete=\"off\" onchange=\"flag_as_dirty();\" value=\"" . $emote_des . "\"/></div>
								<div class=\"notcll cllfor\"><button class=\"butsub\" type=\"button\">-</button></div>
							</div>\n";
			}
		}
?>
							<div class="notrow">
								<div class="notcll cllone"></div>
								<div class="notcll clltwo"></div>
								<div class="notcll cllthr"></div>
								<div class="notcll cllfor"><button class="butadd" type="button">+</button></div>
							</div>
						</div>
					</div>
				</div>
				<div class="bodiv" style="font-size:smaller;">
					<p><a target="_blank" rel="license" href="https://www.gnu.org/licenses/gpl-3.0.en.html" title="GPLv3 icon"><img alt="GPLv3 icon" src="<?php echo $path_root; ?>images/license_gpl-88x31.png" /></a><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/" title="CC BY-SA 3.0 icon"><img alt="CC BY-SA 3.0 icon" style="border-width:0" src="<?php echo $path_root; ?>images/license_cc-by-sa_88x31.png" /></a></p>
					<p>"Video Game Keyboard Diagrams" software was created by Michael Horvath and is licensed under <a target="_blank" rel="license" href="https://www.gnu.org/licenses/gpl.html">GNU GPLv3</a> or later license. Content is licensed under <a target="_blank" href="https://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a> or later license. You can find this project on <a target="_blank" href="https://github.com/mjhorvath/vgkd">GitHub</a>.</p>
					<p>
<?php
	if (($gamesrecord_author) && ($gamesrecord_author != "Michael Horvath"))
	{
		echo
"Binding scheme created by: " . $gamesrecord_author . ". ";
	}
	if (($layout_author) && ($layout_author != "Michael Horvath"))
	{
		echo
"Keyboard layout created by: " . $layout_author . ". ";
	}
	if (($stylesrecord_author) && ($stylesrecord_author != "Michael Horvath"))
	{
		echo
"Style design created by: " . $stylesrecord_author . ". ";
	}
?>
					</p>
					<p>Return to <a href="keyboard.php">Video Game Keyboard Diagrams</a>. View the <a href="keyboard-list.php">master list</a>. Having trouble printing? Take a look at <a href="keyboard.php#print_tips">these printing tips</a>.</p>
<?php
	echo
"					<form name=\"VisualStyleSwitch\">
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
"							<option value=\"" . $style_idx . "\" selected>" . $style_nam . "</option>\n";
			}
			else
			{
				echo
"							<option value=\"" . $style_idx . "\">" . $style_nam . "</option>\n";
			}
		}
	}
	echo
"						</select>
						<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"rad0\" value=\"0\"" . ($format_id == 0 ? " checked " : "") . "/>&nbsp;<label for=\"rad0\">HTML</label>
						<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"rad1\" value=\"1\"" . ($format_id == 1 ? " checked " : "") . "/>&nbsp;<label for=\"rad1\">SVG</label>
						<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"rad2\" value=\"2\"" . ($format_id == 2 ? " checked " : "") . "/>&nbsp;<label for=\"rad2\">MediaWiki</label>
						<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"rad3\" value=\"3\"" . ($format_id == 3 ? " checked " : "") . "/>&nbsp;<label for=\"rad3\">Editor</label>
						<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"rad4\" value=\"4\" disabled />&nbsp;<label for=\"rad4\"><s>PDF</s></label>
						<input class=\"stylechange\" type=\"button\" value=\"Change\" onclick=\"document_change_style('" . $game_id . "', '" . $layout_id . "', '" . $game_seo . "')\" />
					</form>\n";
?>
					<p><?php getFileTime("keyboard-submit.php"); ?></p>
				</div>
			</div>
			<div id="pane_tsv" style="display:none;">
				<h2>Tab-Separated Values (TSV)</h2>
				<p>You should be able to copy and paste the following tab-separated values (TSV) into MS Excel or some other spreadsheet software and edit them further from there. Or you could import them directly into a SQL database using the <code>LOAD DATA</code> command.</p>
				<p>The string <code>\N</code> (with an upper-case "n" and one backslash) indicates a value of null, and may represent an auto-increment field in SQL. The string <code>\\n</code> (with a lower-case "n" and two backslashes) indicates a newline character.</p>
				<p><button style="padding:0.3em 1em;" onclick="fill_spreadsheet();">Fetch Data</button><button style="padding:0.3em 1em;" onclick="clear_spreadsheet();">Clear Data</button></p>
				<h4>Legend</h4>
				<textarea id="legend_tsv" style="width:60em;height:20em;font-family:monospace;" wrap="off" readonly="readonly" autocomplete="off" placeholder="blah"></textarea>
				<p><button style="font-size:smaller;padding:0.3em 1em;" onclick="text_select_and_copy('legend_tsv');">Select &amp; Copy</button></p>
				<h4>Commands</h4>
				<textarea id="command_tsv" style="width:60em;height:20em;font-family:monospace;" wrap="off" readonly="readonly" autocomplete="off" placeholder="blah"></textarea>
				<p><button style="font-size:smaller;padding:0.3em 1em;" onclick="text_select_and_copy('command_tsv');">Select &amp; Copy</button></p>
				<h4>Bindings</h4>
				<textarea id="binding_tsv" style="width:60em;height:20em;font-family:monospace;" wrap="off" readonly="readonly" autocomplete="off" placeholder="blah"></textarea>
				<p><button style="font-size:smaller;padding:0.3em 1em;" onclick="text_select_and_copy('binding_tsv');">Select &amp; Copy</button></p>
			</div>
		</div>
	</body>
</html>
