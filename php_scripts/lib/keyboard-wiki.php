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

	$path_file		= "./keyboard-wiki.php";	// should create a script to fill this variable in
	$keys_number		= 118;		// hardcoded!
	$actions_number		= 10;		// hardcoded!
	$legend_number		= 12;		// hardcoded!
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
	$stylegroup_table	= [];
	$errors_table		= [];
	$gamesrecord_id		= 0;
	$gamesrecord_authors	= [];
	$stylesrecord_id	= 0;
	$stylesrecord_authors	= [];
	$combo_count		= 0;
	$joystick_count		= 0;
	$mouse_count		= 0;
	$note_count		= 0;
	$style_filename		= "";
	$style_name		= "";
	$game_name		= "";
	$platform_name		= "";
	$platform_id		= 0;
	$layout_name		= "";
	$layout_authors		= [];

	// MySQL connection
	$con = mysqli_connect($con_website, $con_username, $con_password, $con_database);
 	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}
	mysqli_query($con, "SET NAMES 'utf8'");

	// MySQL queries
	selURLQueries();		// gather and validate URL parameters
	selDefaults();			// get default values for urlqueries if missing
	checkURLParameters();		// gather and validate URL parameters
	selLanguageStrings();
	selAuthors();
	selStyleGroups();
	selStyles();
	selThisStyle();
	selThisFormat();
	selPositions();
	selThisLayout();
	selThisPlatform();
	selThisGamesRecord();
	selThisStylesRecord();
	selBindings();
	selLegends();
	selCommands();
	selContribsGames();
	selContribsStyles();
	selContribsLayouts();
	selKeystyles();

	// close connection
	mysqli_close($con);

	checkForErrors();
	pageTitle();

	echo
"<!DOCTYPE HTML>
<html lang=\"" . $language_code . "\">
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
		<title>" . cleantextHTML($page_title_a . $temp_separator . $page_title_b) . "</title>
		<link rel=\"canonical\" href=\"" . $can_url . "\"/>
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_root . "favicon.png\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_root . "style_normalize.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style_common.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style_mediawiki.css\"/>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\""	. cleantextHTML($language_description		. $temp_game_name . ". ("	. $temp_style_name . ", "	. $temp_layout_name . ", "	. $temp_format_name)	. ")\"/>
		<meta name=\"keywords\" content=\""	. cleantextHTML($language_keywords . ","	. $temp_game_name . ","		. $temp_style_name . ","	. $temp_layout_name . ","	. $temp_format_name)	. "\"/>
";
	echo writeAnalyticsTracking();
?>
	</head>
	<body style="margin:auto;width:80%;">
		<header>
			<h2><?php echo cleantextHTML($page_title_a . $temp_separator . $page_title_b); ?></h2>
		</header>
		<main>
			<p>I have created templates for MediaWiki that do basically the same thing as the other charts on this site. You can find the templates as well as instructions on how to use them at <a target="_blank" href="http://strategywiki.org/wiki/Template:Kbdchart">StrategyWiki</a> and <a target="_blank" href="http://templates.wikia.com/wiki/Template:Kbdchart">Fandom</a>. Below is the code you would use to fill the template with data and display a keyboard diagram on a MediaWiki wiki. On the destination wiki page, you may also want to wrap the chart in a scrollable DIV element, since the generated chart is wider than a typical MediaWiki page.</p>
			<textarea readonly="readonly" wrap="off" style="width:100%;height:30em;font-family:monospace;">
{{kbdchart
<?php
	// keys
	for ($i = 0; $i < $keys_number; $i++)
	{
		$leadZ		= leadingZeros3($i);
		if (array_key_exists($i, $binding_table))
		{
			$binding_row	= $binding_table[$i];
			$bkg_nor	= getkeycolor($binding_row[0]);
			$key_nor	= cleantextWiki($binding_row[1]);
			$bkg_shf	= getkeycolor($binding_row[2]);
			$key_shf	= cleantextWiki($binding_row[3]);
			$bkg_ctl	= getkeycolor($binding_row[4]);
			$key_ctl	= cleantextWiki($binding_row[5]);
			$bkg_alt	= getkeycolor($binding_row[6]);
			$key_alt	= cleantextWiki($binding_row[7]);
			$bkg_agr	= getkeycolor($binding_row[8]);
			$key_agr	= cleantextWiki($binding_row[9]);
			$bkg_xtr	= getkeycolor($binding_row[10]);
			$key_xtr	= cleantextWiki($binding_row[11]);
		}
		// is the 'else' really needed here? or can these be omitted?
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
			$leg_grp = getkeycolor($legend_row[0]);
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
<?php include($path_lib2 . "keyboard-footer.php"); ?>
		</footer>
	</body>
</html>
