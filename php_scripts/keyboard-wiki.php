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
	$path_file		= "./keyboard-wiki.php";

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
	$string_title		= "";
	$string_mouse		= "";
	$string_joystick	= "";
	$string_combos		= "";
	$string_notes		= "";
	$layout_legend		= "";		// heading was removed from the database
	$layout_author		= "";
	$string_description	= "";
	$string_keywords	= "";
	$game_array		= [];


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
			$format_id == 2;
		}
		$fix_url = true;
	}


	selGamesHTML();
	selAuthorsHTML();
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
<?php
	echo
"		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>\n" .
"		<title>" . $thispage_title_a . $thispage_title_b . "</title>\n" .
"		<link rel=\"canonical\" href=\"" . $php_url . "\"/>\n" .
"		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_root . "favicon.png\"/>\n" .
"		<link rel=\"stylesheet\" type=\"text/css\" href=\"./style_normalize.css\"/>\n" .
"		<link rel=\"stylesheet\" type=\"text/css\" href=\"./style_common.css\"/>\n" .
"		<link rel=\"stylesheet\" type=\"text/css\" href=\"./style_mediawiki.css\"/>\n" .
"		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>\n" .
"		<meta name=\"description\" content=\"" . $string_description . $temp_game_name . ".\"></meta>\n" .
"		<meta name=\"keywords\" content=\"visual,keyboard,keys,diagrams,charts,overlay,shortcuts,bindings,mapping,maps,controls,hotkeys,database,print,printable,video game,software,guide,reference,MediaWiki," . $temp_game_name . "\"></meta>\n" .
"		<script src=\"keyboard-chart-js.php\"></script>\n";
	include($path_root . "ssi/analyticstracking.php");
?>
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
		if (array_key_exists($i, $binding_table))
		{
			$binding_row	= $binding_table[$i];
			$bkg_nor	= getcolor($binding_row[0]);
			$key_nor	= cleantextWiki($binding_row[1]);
			$bkg_shf	= getcolor($binding_row[2]);
			$key_shf	= cleantextWiki($binding_row[3]);
			$bkg_ctl	= getcolor($binding_row[4]);
			$key_ctl	= cleantextWiki($binding_row[5]);
			$bkg_alt	= getcolor($binding_row[6]);
			$key_alt	= cleantextWiki($binding_row[7]);
			$bkg_agr	= getcolor($binding_row[8]);
			$key_agr	= cleantextWiki($binding_row[9]);
			$bkg_xtr	= getcolor($binding_row[10]);
			$key_xtr	= cleantextWiki($binding_row[11]);
		}
		// is the 'else' really needed here? or can these be skipped?
		else
		{
			$bkg_nor = "";
			$key_nor = "";
			$bkg_shf = "";
			$key_shf = "";
			$bkg_ctl = "";
			$key_ctl = "";
			$bkg_alt = "";
			$key_alt = "";
			$bkg_agr = "";
			$key_agr = "";
			$bkg_xtr = "";
			$key_xtr = "";
		}
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
		if (isset($legend_table[$i]))
		{
			$legend_row = $legend_table[$i];
			$leg_grp = getcolor($legend_row[0]);
			$leg_dsc = cleantextWiki($legend_row[1]);
		}
		// is the 'else' really needed here? or can these be skipped?
		else
		{
			$leg_grp = "";
			$leg_dsc = "";
		}
		echo
"|" . $leadZ. "lgb=" . $leg_grp . "|" . $leadZ . "lgt=" . $leg_dsc . "\n";
	}
?>
}}
			</textarea>
		</main>
		<footer>
<?php include("./keyboard-footer.php"); ?>
		</footer>
	</body>
</html>
