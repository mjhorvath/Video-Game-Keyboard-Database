<?php
	// Video Game Keyboard Database
	// Copyright (C) 2018  Michael Horvath
        // 
	// This file is part of Video Game Keyboard Database.
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

	$path_vgkd		= "https://isometricland.net/keyboard/";
	$path_file		= "export-html.php";	// this file
	$path_root1		= "../";		// for files in "keyboard/"
	$path_lib1		= "./lib/";		// for files in "keyboard/"
	$path_java1		= "../java/";		// for files in "keyboard/"
	$path_ssi1		= "../ssi/";		// for files in "keyboard/"
	$path_root2		= "../../";		// for files in "keyboard/lib/"
	$path_lib2		= "./";			// for files in "keyboard/lib/"
	$path_java2		= "../../java/";	// for files in "keyboard/lib/"
	$path_ssi2		= "../../ssi/";		// for files in "keyboard/lib/"

	include($path_ssi2 . "keyboard-connection.php");
	include($path_lib2 . "scripts-common.php");
	include($path_lib2 . "queries-common.php");
	include($path_lib2 . "queries-chart.php");

	$json_input = file_get_contents('php://input');
	$json_data = json_decode($json_input, true);

//	$gamesrecord_id		= $json_data["gamesrecord_id"];		// set by java-export.js
	$style_id		= $json_data["style_id"];		// set by java-export.js
	$layout_id		= $json_data["layout_id"];		// set by java-export.js
	$format_id		= $json_data["format_id"];		// set by java-export.js
	$game_id		= $json_data["game_id"];		// set by java-export.js
	$game_seo		= $json_data["game_seo"];		// set by java-export.js
	$tenk_flag		= $json_data["tenk_flag"];		// set by java-export.js
	$vert_flag		= $json_data["vert_flag"];		// set by java-export.js
	$kcap_flag		= $json_data["kcap_flag"];		// set by java-export.js
	$svgb_flag		= $json_data["svgb_flag"];		// set by java-export.js
	$commandouter_table	= $json_data["commandouter_table"];	// set by java-export.js
	$legend_table		= $json_data["legend_table"];		// set by java-export.js
	$binding_table		= $json_data["binding_table"];		// set by java-export.js
//	$commandouter_table	= [];		// set by selCommandsChart()
	$commandlabel_table	= [];		// set by selCommandLabelsChart()
	$position_table		= [];		// populated by selPositionsChart()
	$keystyle_table		= [];		// populated by selKeyStylesChart()
//	$binding_table		= [];		// populated by selBindingsChart()
//	$legend_table		= [];		// populated by selLegendsChart()
	$author_table		= [];		// populated by selAuthorsChart()
//	$stylegroup_table	= [];		// set by selStyleGroupsChart() and selStylesChart(), utilized by "footer-chart.php"
//	$style_table		= [];		// set by selStyleGroupsChart() and selStylesChart(), utilized by "footer-chart.php"
	$gamesrecord_id		= 0;		// set by selThisGamesRecordChart()
	$gamesrecord_authors	= [];		// populated by selContribsGamesChart(), utilized by "footer-chart.php"
	$stylesrecord_id	= 0;		// set by selThisStylesRecordChart()
	$stylesrecord_authors	= [];		// populated by selContribsStylesChart(), utilized by "footer-chart.php"
	$stylegroup_id		= 0;		// set by selThisStyleChart(), also contained inside $stylegroup_table, utilized by "footer-chart.php"
	$style_filename		= "";		// set by selThisStyleChart()
	$style_name		= "";		// set by selThisStyleChart() and checkURLParameters(), utilized by checkForErrors()
	$game_name		= "";		// set by selThisGamesIDChart(), utilized by checkForErrors()
	$format_name		= "";		// set by selThisFormatChart(), utilized by checkForErrors()
	$platform_name		= "";		// set by selThisPlatformChart()
	$platform_id		= 0;		// set by selThisLayoutChart()
	$layout_name		= "";		// set by selThisLayoutChart()
	$layout_authors		= [];		// set by selContribsLayoutsChart()
	$layout_keysnum		= 0;		// reset by selThisLayoutChart(), hopefully obsolete
	$layout_keygap		= 4;		// reset by selThisLayoutChart()
	$layout_padding		= 18;		// reset by selThisLayoutChart()
	$layout_fullsize_width		= 1200;		// reset by selThisLayoutChart()
	$layout_fullsize_height		= 400;		// reset by selThisLayoutChart()
	$layout_tenkeyless_width	= 1200;		// reset by selThisLayoutChart()
	$layout_tenkeyless_height	= 400;		// reset by selThisLayoutChart()
	$layout_legend_padding		= 36;		// reset by selThisLayoutChart()
	$layout_legend_height		= 72;		// reset by selThisLayoutChart()
	$layout_legend_top	= 0;		// reset by selThisLayoutChart()
	$layout_min_horizontal	= 0;		// reset by selThisLayoutChart()
	$layout_max_horizontal	= 0;		// reset by selThisLayoutChart()
	$layout_min_vertical	= 0;		// reset by selThisLayoutChart()
	$layout_max_vertical	= 0;		// reset by selThisLayoutChart()

	// MySQL connection
	$con = mysqli_connect($con_website, $con_username, $con_password, $con_database);
 	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}
	mysqli_query($con, "SET NAMES 'utf8'");

	// MySQL queries (mostly)
	selURLQueriesAll();		// gather and validate URL parameters
	selDefaultsAll();		// get default values for entities if missing
//	getURLParameters();		// gather and validate URL parameters, not a query, AJAX is being used above instead
//	checkURLParameters();		// gather and validate URL parameters, not a query, AJAX is being used above instead
	selThisLanguageStringsChart();
	selAuthorsChart();
	selThisGamesIDChart();
	selStyleGroupsChart();
	selStylesChart();
	selThisStyleChart();
	selThisFormatChart();
	selPositionsChart();
	selThisGamesRecordChart();
	selThisStylesRecordChart();
	selThisLayoutChart();
	selThisPlatformChart();
//	selBindingsChart();
//	selLegendsChart();
//	selCommandsChart();
	selContribsGamesChart();
	selContribsStylesChart();
	selContribsLayoutsChart();
	selLegendColorsChart();
	selCommandLabelsChart();
	selKeyStylesChart();
	selKeyStyleClassesChart();

	// close connection
	mysqli_close($con);

	checkForErrors();
	pageTitle();

	// layout outer bounds
	if ($tenk_flag == 0)
	{
		$layout_min_horizontal	= -$layout_padding;
		$layout_max_horizontal	=  $layout_padding * 2 + $layout_tenkeyless_width;
		$layout_min_vertical	= -$layout_padding;
		$layout_max_vertical	=  $layout_padding * 2 + $layout_tenkeyless_height + $layout_legend_padding + $layout_legend_height;
		$layout_legend_top	=  $layout_tenkeyless_height + $layout_legend_padding;
	}
	else if ($tenk_flag == 1)
	{
		$layout_min_horizontal	= -$layout_padding;
		$layout_max_horizontal	=  $layout_padding * 2 + $layout_fullsize_width;
		$layout_min_vertical	= -$layout_padding;
		$layout_max_vertical	=  $layout_padding * 2 + $layout_fullsize_height + $layout_legend_padding + $layout_legend_height;
		$layout_legend_top	=  $layout_fullsize_height + $layout_legend_padding;
	}

	echo
'<!DOCTYPE HTML>
<!--
This file was generated using Video Game Keyboard Database by Michael Horvath.
https://isometricland.net/keyboard/keyboard.php
This work is licensed under the Creative Commons Attribution-ShareAlike 3.0
United States License. To view a copy of this license, visit
http://creativecommons.org/licenses/by-sa/3.0/us/ or send a letter to Creative
Commons, PO Box 1866, Mountain View, CA 94042, USA.
';
	echo "Binding scheme created by: ";
	$count_authors = count($gamesrecord_authors);
	for ($i = 0; $i < $count_authors; $i++)
	{
		echo $gamesrecord_authors[$i];
		if ($i < $count_authors - 1)
			echo ", ";
		else
			echo ".\n";
	}
	echo "Keyboard layout created by: ";
	$count_authors = count($layout_authors);
	for ($i = 0; $i < $count_authors; $i++)
	{
		echo $layout_authors[$i];
		if ($i < $count_authors - 1)
			echo ", ";
		else
			echo ".\n";
	}
	echo "Theme created by: ";
	$count_authors = count($stylesrecord_authors);
	for ($i = 0; $i < $count_authors; $i++)
	{
		echo $stylesrecord_authors[$i];
		if ($i < $count_authors - 1)
			echo ", ";
		else
			echo ".\n";
	}
	echo
'-->
<html lang="' . $language_code . '">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>' . $page_title_a . $page_title_b . '</title>
		<link rel="canonical" href="' . $can_url . '"/>
		<link rel="icon" type="image/png" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAALiEAAC4hAQdb/P8AAAAgSURBVDhPY/xvG8VACmCC0kSDUQ3EgFENxABaa2BgAAANNAG2n4KuogAAAABJRU5ErkJggg=="/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta name="description" content="' . $language_title . ': ' . $language_description . '."/>
		<meta name="keywords" content="' . $language_keywords . '"/>
';
	echo
"		<style type=\"text/css\">\n";
	include($path_lib2 . "style-normalize.css");
	echo
"		</style>\n";
	echo
"		<style type=\"text/css\">\n";
	include($path_lib2 . "style-footer.css");
	echo
"		</style>\n";
	echo
"		<style type=\"text/css\">\n";
	if ($style_filename == "")
		error_log("Error: Something is wrong with style. " . $can_url, 0);	// delete this when the problem goes away
	else
		include($path_lib2 . "embed-" . $style_filename . ".css");
	echo
'		</style>
	</head>
	<body>
		<header>
			<div class="boxdiv">
				<h2>' . $page_title_a . '<small>' . $page_title_b . '</small></h2>
				<p>' . $language_title . ': ' . $language_description . '</p>
			</div>
		</header>
		<main>
';

	if ($vert_flag == false)
	{
		echo
'			<div class="svgdiv" style="position:relative;width:' . $layout_max_horizontal . 'px;height:' . $layout_max_vertical . 'px;">
';
	}
	else
	{
		echo
'			<div class="svgdiv" style="position:relative;width:' . $layout_max_vertical . 'px;height:' . $layout_max_horizontal . 'px;">
';
	}

	include($path_lib2 . "export-submit-svg.php");

	echo
'			</div>
			<div class="comflx">
';

	// cleaning!
	// commands
	foreach ($commandouter_table as $i => $commandouter_value)
	{
		$commandlabel_value = $commandlabel_table[$i];
		$commandlabel_label = $commandlabel_value[1];
		$commandlabel_abbrv = $commandlabel_value[2];
		$commandlabel_input = $commandlabel_value[3];
		$commandlabel_group = $commandlabel_value[4];
		if (count($commandouter_value) > 0)
		{
			echo
"				<div class=\"comdiv\">
					<h3>" . cleantextHTML($commandlabel_label) . "</h3>
					<table>\n";
			foreach ($commandouter_value as $j => $commandinner_value)
			{
				// this part is different than in the non-export version of this document
				$commandinner_input = $commandinner_value[1];
				$commandinner_combo = $commandinner_value[2];
				$commandinner_group = $commandinner_value[0];
				$leg_color = getkeycolor($commandinner_group);
	//			$leg_color = $commandinner_group;
				// does the command have a keygroup?
				if ($commandlabel_group == 1)
				{
					echo
"						<tr><td><div class=\"legbox leg" . $leg_color . "\">&nbsp;</div></td><td>" . cleantextHTML($commandinner_input) . "</td><td>=</td><td>" . cleantextHTML($commandinner_combo) . "</td></tr>\n";
				}
				// does the command have a keygroup?
				elseif ($commandlabel_input == 1)
				{
					echo
"						<tr><td>" . cleantextHTML($commandinner_input) . "</td><td>=</td><td>" . cleantextHTML($commandinner_combo) . "</td></tr>\n";
				}
				// "additional notes" are a special case with only the second value containing text
				else
				{
					echo
"						<tr><td>" . cleantextHTML($commandinner_combo) . "</td></tr>\n";
				}
			}
			echo
"					</table>
				</div>\n";
		}
	}
	echo
'			</div>
		</main>
		<footer>
';

	include($path_lib2 . "footer-mini.php");

	echo
'		</footer>
	</body>
</html>
';
