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
	$format_id		= array_key_exists("fmt", $_GET) ? intval(ltrim($_GET["fmt"], "0")) : 0;
	$svg_bool		= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0")) : null;
	$fix_url		= false;
	$php_url		= "";
	$svg_url		= "";
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
	$style_table		= [];
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
		global $style_filename, $style_name, $style_author;
		$style_row = mysqli_fetch_row($in_result);
		$style_filename = $style_row[0];
		$style_name = cleantextHTML($style_row[1]);
		$style_author = cleantextHTML(getAuthorName($style_row[2]));
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

	mysqli_close($con);

	// validity checks
	$game_seo		= $game_seo ? $game_seo : "unrecognized-game";
	$temp_game_name		= $game_name ? $game_name : "Unrecognized Game";
	$thispage_title_a	= $temp_game_name;
	$thispage_title_b	= " - MediaWiki keyboard diagram code";

	$thispage_title	= $game_name ? $game_name : "Unrecognized Game";

	$php_url = "http://isometricland.net/keyboard/keyboard-diagram-" . $game_seo . ".php?sty=" . $style_id . "&lay=" . $layout_id . "&fmt=" . $format_id;

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
"		<meta name=\"keywords\" content=\"visual,keyboard,keys,diagrams,charts,overlay,shortcuts,bindings,mapping,maps,controls,hotkeys,database,print,printable,video game,software,guide,reference,MediaWiki," . $temp_game_name . "\"></meta>\n";
	include($path_root . "ssi/analyticstracking.php");
?>
		<script src="keyboard-js.php"></script>
		<style type="text/css">
<?php	include("./style_mediawiki.css"); ?>
		</style>
	</head>
	<body style="margin:auto;width:80%;">
		<header>
			<h2><?php echo $thispage_title_a; ?><?php echo $thispage_title_b; ?></h2>
		</header>
		<main>
			<p>I have created templates for MediaWiki that do basically the same thing as the other charts on this site. You can find the templates as well as instructions on how to use them on StrategyWiki and Wikia, <a target="_blank" href="http://strategywiki.org/wiki/Template:Kbdchart">here</a> and <a target="_blank" href="http://templates.wikia.com/wiki/Template:Kbdchart">here</a>. Below is the code you would use to fill the template with data and display a keyboard diagram on a MediaWiki wiki. On the destination wiki page, you may also want to wrap the chart in a scrollable DIV element, since the generated chart is wider than a typical MediaWiki page.</p>
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
		</main>
		<footer>
			<div class="bodiv" style="clear:both;">
				<p><a target="_blank" rel="license" href="http://creativecommons.org/licenses/LGPL/2.1/"><img alt="Creative Commons License" src="<?php echo $path_root; ?>images/license_cc-lgpl_88x31.png" /></a><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="<?php echo $path_root; ?>images/license_cc-by-sa_88x31.png" /></a></p>
				<p>"Video Game Keyboard Diagrams" software was created by Michael Horvath and is licensed under <a target="_blank" rel="license" href="https://creativecommons.org/licenses/LGPL/2.1/;/">CC LGPL 2.1</a>. Content is licensed under <a target="_blank" href="https://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a>. You can find this project on <a target="_blank" href="https://github.com/mjhorvath/vgkd">GitHub</a>.</p>
				<p>Return to <a href="keyboard.php">Video Game Keyboard Diagrams</a>. View the <a href="keyboard-list.php">master list</a>.</p>
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
