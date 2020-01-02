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
	include($path_lib2 . "queries-chart.php");

	$path_file		= "./chart-embed.php";	// this file
	$commandouter_table	= [];
	$commandouter_count	= 0;
	$commandlabel_table	= [];
	$commandlabel_count	= 0;
	$author_table		= [];
	$style_table		= [];
	$gamesrecord_id		= 0;
	$gamesrecord_authors	= [];
	$stylesrecord_id	= 0;
	$stylesrecord_authors	= [];
	$stylegroup_id		= 0;
	$style_filename		= "";
	$style_name		= "";
	$game_name		= "";
	$platform_name		= "";
	$platform_id		= 0;
	$layout_name		= "";
	$layout_authors		= [];
	$layout_keysnum		= 0;			// hopefully obsolete
	$layout_keygap		= 4;
	$layout_padding		= 18;
	$layout_fullsize_width		= 1200;
	$layout_fullsize_height		= 400;
	$layout_tenkeyless_width	= 1200;
	$layout_tenkeyless_height	= 400;
	$layout_legend_padding		= 36;
	$layout_legend_height		= 72;
	$layout_legend_top	= 0;
	$layout_min_horizontal	= 0;
	$layout_max_horizontal	= 0;
	$layout_min_vertical	= 0;
	$layout_max_vertical	= 0;

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
	selLegendsChart();
	selCommandsChart();
	selContribsGamesChart();
	selContribsStylesChart();
	selContribsLayoutsChart();
	selCommandLabelsChart();

	// close connection
	mysqli_close($con);

	checkForErrors();
	pageTitle();

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

	echo
"<!DOCTYPE HTML>
<html lang=\"" . $language_code . "\">
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
		<title>" . $page_title_a . $temp_separator . $page_title_b . "</title>
		<link rel=\"canonical\" href=\"" . $can_url . "\">
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_lib1 . "favicon.png\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_root2 . "style_normalize.css\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style-footer.css\"/>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
		<meta name=\"description\" content=\""	. $language_description		. $temp_game_name . ". ("	. $temp_style_name . ", "	. $temp_layout_name . ", "	. $temp_format_name	. ")\"/>
		<meta name=\"keywords\" content=\""	. $language_keywords . ","	. $temp_game_name . ","		. $temp_style_name . ","	. $temp_layout_name . ","	. $temp_format_name	. "\"/>
		<script src=\"" . $path_lib1 . "java-footer.js\"></script>\n";
	echo writeAnalyticsTracking();
	echo
"		<style type=\"text/css\">\n";
	include($path_lib2 . "embed-" . $style_filename . ".css");
	// delete this when the problem goes away
	if ($style_filename == "")
		error_log("Error: Something is wrong with style. " . $can_url, 0);
	echo
"		</style>\n";
?>
	</head>
	<body>
		<header>
			<div class="boxdiv"><h2><?php echo $page_title_a; ?><small><?php echo $temp_separator . $page_title_b; ?></small></h2></div>
		</header>
		<main>
			<div class="svgdiv" style="position:relative;width:<?php echo $layout_max_horizontal; ?>px;height:<?php echo $layout_max_vertical; ?>px;">
				<iframe src="<?php echo $svg_url; ?>" width="<?php echo $layout_max_horizontal; ?>" height="<?php echo $layout_max_vertical; ?>" sandbox style="border:none;margin:0;padding:0;">
					<!--<img src="triangle.png" alt="Triangle with three unequal sides" />-->
				</iframe>
<?php
/*
	// advertisement
	echo
"				<div style=\"position:absolute;left:600px;top:521.5px;width:728px;height:90px;padding:0;margin:0;z-index:10;\">\n";
	include($path_ssi2 . "adsense_horz_large.php");
	echo
"				</div>\n";
*/
?>
			</div>
			<div class="comflx">
<?php
	// commands
	foreach ($commandouter_table as $i => $commandouter_value)
	{
		$commandlabel_value = $commandlabel_table[$i];
		$commandlabel_label = $commandlabel_value[1];
		$commandlabel_abbrv = $commandlabel_value[2];
		$commandlabel_input = $commandlabel_value[3];
		echo
"				<div class=\"comdiv\">
					<h3>" . cleantextHTML($commandlabel_label) . "</h3>
					<table>\n";
		foreach ($commandouter_value as $j => $commandinner_value)
		{
			// "additional notes" are a special case with only the second value containing text
			if ($commandlabel_input == 1)
			{
				echo
"						<tr><td>" . cleantextHTML($commandinner_value[1]) . "</td><td>=</td><td>" . cleantextHTML($commandinner_value[2]) . "</td></tr>\n";
			}
			else
			{
				echo
"						<tr><td>" . cleantextHTML($commandinner_value[2]) . "</td></tr>\n";
			}
		}
		echo
"					</table>
				</div>\n";
	}
?>
			</div>
		</main>
		<footer>
<?php include($path_lib2 . "footer-chart.php"); ?>
		</footer>
	</body>
</html>
