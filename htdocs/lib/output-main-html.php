<?php
	// Video Game Keyboard Diagrams
	// Copyright (C) 2018  Michael Horvath
        // 
	// This file is part of Video Game Keyboard Diagrams.
        // 
	// This program is free software: you can redistribute it and/or output-
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
	include($path_lib2 . "queries-chart.php");
	include($path_ssi2 . "plugin-analyticstracking.php");

	$path_vgkd		= "http://isometricland.net/keyboard/";
	$path_file		= "output-main-html.php";	// this file
	$commandouter_table	= [];		// set by selCommandsChart()
	$commandlabel_table	= [];		// set by selCommandLabelsChart()
	$position_table		= [];		// populated by selPositionsChart()
	$keystyle_table		= [];		// populated by selKeyStylesChart()
	$binding_table		= [];		// populated by selBindingsChart()
	$legend_table		= [];		// populated by selLegendsChart()
	$author_table		= [];		// populated by selAuthorsChart()
	$stylegroup_table	= [];		// set by selStyleGroupsChart() and selStylesChart(), utilized by "footer-chart.php"
	$style_table		= [];		// set by selStyleGroupsChart() and selStylesChart(), utilized by "footer-chart.php"
	$gamesrecord_id		= 0;		// set by selThisGamesRecordChart()
	$gamesrecord_authors	= [];		// populated by selContribsGamesChart(), utilized by "footer-chart.php"
	$stylesrecord_id	= 0;		// set by selThisStylesRecordChart()
	$stylesrecord_authors	= [];		// populated by selContribsStylesChart(), utilized by "footer-chart.php"
	$stylegroup_id		= 0;		// set by selThisStyleChart(), also contained inside $stylegroup_table, utilized by "footer-chart.php"
	$style_filename		= "";		// set by selThisStyleChart()
	$style_name		= "";		// set by selThisStyleChart() and checkURLParameters(), utilized by checkForErrors()
	$game_name		= "";		// set by checkURLParameters(), utilized by checkForErrors()
	$platform_name		= "";		// set by checkURLParameters(), utilized by checkForErrors()
	$platform_id		= 0;		// set by checkURLParameters(), utilized by checkForErrors()
	$layout_name		= "";		// set by checkURLParameters(), utilized by checkForErrors()
	$layout_authors		= [];		// populated by selContribsLayoutsChart(), utilized by "footer-chart.php"
	$layout_keysnum		= 0;		// reset by selThisLayoutChart(), hopefully obsolete
	$layout_keygap		= 4;		// reset by selThisLayoutChart()
	$layout_fullsize_padding	= 18;		// reset by selThisLayoutChart()
	$layout_fullsize_width		= 1200;		// reset by selThisLayoutChart()
	$layout_fullsize_height		= 400;		// reset by selThisLayoutChart()
	$layout_tenkeyless_padding	= 18;		// reset by selThisLayoutChart()
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

	// MySQL queries
	selURLQueriesAll();		// gather and validate URL parameters
	selDefaultsAll();		// get default values for entities if missing
	getURLParameters();		// gather and validate URL parameters, not a query
	checkURLParameters();		// gather and validate URL parameters, not a query
	selThisLanguageStringsChart();
	selAuthorsChart();
	selStyleGroupsChart();
	selStylesChart();
	selThisStyleChart();
	selThisFormatChart();
	selThisGamesRecordChart();
	selThisStylesRecordChart();
	selThisLayoutChart();
	selThisPlatformChart();
	selPositionsChart();
	selBindingsChart();
	selLegendsChart();
	selCommandsChart();
	selContribsGamesChart();
	selContribsStylesChart();
	selContribsLayoutsChart();
	selLegendColorsChart();
	selKeyStylesChart();
	selKeyStyleClassesChart();
	selCommandLabelsChart();

	// close connection
	mysqli_close($con);

	checkForErrors();
	pageTitle();

	// layout outer bounds
	if ($ten_bool == 0)
	{
		$layout_min_horizontal	= -$layout_tenkeyless_padding;
		$layout_max_horizontal	=  $layout_tenkeyless_padding * 2 + $layout_tenkeyless_width;
		$layout_min_vertical	= -$layout_tenkeyless_padding;
		$layout_max_vertical	=  $layout_tenkeyless_padding * 2 + $layout_tenkeyless_height + $layout_legend_padding * 2 + $layout_legend_height;
		$layout_legend_top	=  $layout_tenkeyless_padding * 2 + $layout_tenkeyless_height + $layout_legend_padding;
	}
	else if ($ten_bool == 1)
	{
		$layout_min_horizontal	= -$layout_fullsize_padding;
		$layout_max_horizontal	=  $layout_fullsize_padding * 2 + $layout_fullsize_width;
		$layout_min_vertical	= -$layout_fullsize_padding;
		$layout_max_vertical	=  $layout_fullsize_padding * 2 + $layout_fullsize_height + $layout_legend_padding * 2 + $layout_legend_height;
		$layout_legend_top	=  $layout_fullsize_padding * 2 + $layout_fullsize_height + $layout_legend_padding;
	}

	echo
'<!DOCTYPE HTML>
<!--
This file was generated using Video Game Keyboard Diagrams by Michael Horvath.
http://isometricland.net/keyboard/keyboard.php
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
		<title>' . $page_title_a . $temp_separator . $page_title_b . '</title>
		<link rel="canonical" href="' . $can_url . '"/>
		<link rel="icon" type="image/png" href="' . $path_lib1 . 'favicon.png"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta name="description" content="'	. $language_description		. $temp_game_name . '"/>
		<meta name="keywords" content="'	. $language_keywords . ','	. $temp_game_name . ','		. $temp_style_name . ','	. $temp_layout_name . ','	. $temp_format_name	. '"/>
		<script src="' . $path_lib1 . 'jquery-3.3.1.min.js"></script>
		<script src="' . $path_lib1 . 'java-footer.js"></script>
		<script src="' . $path_lib1 . 'java-export.js"></script>
		<link rel="stylesheet" href="' . $path_root1 . 'style_normalize.css"/>
		<link rel="stylesheet" href="' . $path_lib1 . 'style-footer.css"/>
';

	if ($style_filename == "")
	{
		error_log("Error: Something is wrong with style. " . $can_url, 0);	// delete this when the problem goes away
	}
	else
	{
		echo
"		<link rel=\"stylesheet\" href=\"" . $path_lib1 . "embed-" . $style_filename . ".css\"/>\n";
	}

	echo
'		<script>
var binding_data =
{
	gamesrecord_id: ' . $gamesrecord_id . ',
	style_id: '       . $style_id . ',
	layout_id: '      . $layout_id . ',
	format_id: '      . $format_id . ',
	game_id: '        . $game_id . ',
	game_seo: \''     . $game_seo . '\',
	ten_bool: '       . $ten_bool . ',
	vert_bool: '      . $vert_bool . ',
	svg_bool: '       . $svg_bool . '
}
		</script>
';

	echo writeAnalyticsTracking();

	echo
'	</head>
	<body onload="init_footer();">
		<header>
			<div class="boxdiv"><h2>' . $page_title_a . '<small>' . $temp_separator . $page_title_b . '</small></h2></div>
		</header>
		<main>
';

	if ($vert_bool == false)
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

	include($path_lib2 . "output-main-svg.php");

	echo
'			</div>
			<div class="comflx" style="">
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
		echo
'				<div class="comdiv">
					<h3>' . cleantextHTML($commandlabel_label) . '</h3>
					<table>
';
		foreach ($commandouter_value as $j => $commandinner_value)
		{
			$commandinner_input = $commandinner_value[1];
			$commandinner_combo = $commandinner_value[2];
			$commandinner_group = $commandinner_value[3];
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
'					</table>
				</div>
';
	}

	echo
'			</div>
		</main>
		<footer>
';

	include($path_lib2 . "footer-chart.php");

	echo
'		</footer>
	</body>
</html>
';
