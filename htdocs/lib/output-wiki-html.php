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
	include($path_ssi2 . "plugin-analyticstracking.php");

	$path_vgkd		= "https://isometricland.net/keyboard/";
	$path_file		= "output-wiki-html.php";	// this file
	$binding_table		= [];
	$legend_table		= [];
	$command_table		= [];		// not implemented yet
	$author_table		= [];		// utilized by footer
	$style_table		= [];		// utilized by footer
	$stylegroup_table	= [];		// utilized by footer
	$gamesrecord_id		= 0;		// utilized by footer
	$gamesrecord_authors	= [];		// utilized by footer
	$stylesrecord_id	= 0;		// utilized by footer
	$stylesrecord_authors	= [];		// utilized by footer
	$layout_authors		= [];		// utilized by footer
	$game_seo		= "";		// utilized by checkForErrors() and checkURLParameters()
	$game_name		= "";		// utilized by checkForErrors() and checkURLParameters()
	$game_id		= 0;		// utilized by checkForErrors() and checkURLParameters()
	$platform_name		= "";		// utilized by checkForErrors() and checkURLParameters()
	$platform_id		= 0;		// utilized by checkForErrors() and checkURLParameters()
	$layout_name		= "";		// utilized by checkForErrors() and checkURLParameters()
	$layout_id		= 0;		// utilized by checkForErrors() and checkURLParameters()
	$style_name		= "";		// utilized by checkForErrors() and checkURLParameters()
	$style_id		= 0;		// utilized by checkForErrors() and checkURLParameters()
	$format_name		= "";		// utilized by checkForErrors() and checkURLParameters()
//	$format_id		= 0;		// should not be set here since it has already been set in "keyboard-init.php"
//	$svgb_flag		= 0;		// should not be set here since it has already been set in "keyboard-init.php"

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
	selBindingsChart();
	selLegendsChart();
//	selCommandsChart();		// not implemented yet
	selLegendColorsChart();
	selContribsGamesChart();
	selContribsStylesChart();
	selContribsLayoutsChart();
	selKeyStylesChart();

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
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_lib1 . "favicon.png\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style-normalize.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style-common.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style-wiki.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style-footer.css\"/>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\"" . cleantextHTML($language_description		. $temp_game_name . ". ("	. $temp_style_name . ", "	. $temp_layout_name . ", "	. $temp_format_name)	. ")\"/>
		<meta name=\"keywords\" content=\""    . cleantextHTML($language_keywords	. ","	. $temp_game_name . ","		. $temp_style_name . ","	. $temp_layout_name . ","	. $temp_format_name)	. "\"/>
		<script src=\"" . $path_lib1 . "java-common.js\"></script>
		<script src=\"" . $path_lib1 . "java-footer.js\"></script>\n";
	echo writeAnalyticsTracking();
	echo
'		<script>
var binding_data =
{
	gamesrecord_id: ' . $gamesrecord_id . ',
	style_id: '       . $style_id . ',
	layout_id: '      . $layout_id . ',
	format_id: '      . $format_id . ',
	game_id: '        . $game_id . ',
	game_seo: \''      . $game_seo . '\',
	tenk_flag: '       . $tenk_flag . ',
	vert_flag: '      . $vert_flag . ',
	svgb_flag: '       . $svgb_flag . '
}
		</script>
';
	echo
"	</head>\n";
?>
	<body onload="init_footer();">
		<header>
			<h2><?php echo cleantextHTML($page_title_a . $temp_separator . $page_title_b); ?></h2>
		</header>
		<main>
			<p>I have created templates for MediaWiki that do basically the same thing as the other charts on this site. You can find the templates as well as instructions on how to use them at <a target="_blank" href="http://strategywiki.org/wiki/Template:Kbdchart">StrategyWiki</a> and <a target="_blank" href="http://templates.wikia.com/wiki/Template:Kbdchart">Fandom</a>. There is a test case <a target="_blank" href="https://templates.fandom.com/wiki/User:Mikali_Homeworld/Kbdchart_example">located here</a>. Below is the code you would use to fill the template with data and display a keyboard diagram on a MediaWiki wiki. On the destination wiki page, you may also want to wrap the chart in a scrollable DIV element, since the generated chart is wider than a typical MediaWiki page.</p>
<textarea id="site_out" readonly="readonly" wrap="off" style="width:100%;height:30em;font-family:monospace;">
{{kbdchart
<?php
	// keys
	foreach ($binding_table as $i => $binding_row)
	{
		$leadZ		= leadingZeros($i, 3);
		$bkg_nor	= getkeycolor($binding_row[0]);
		$key_nor	= $binding_row[1];
		$bkg_shf	= getkeycolor($binding_row[2]);
		$key_shf	= $binding_row[3];
		$bkg_ctl	= getkeycolor($binding_row[4]);
		$key_ctl	= $binding_row[5];
		$bkg_alt	= getkeycolor($binding_row[6]);
		$key_alt	= $binding_row[7];
		$bkg_agr	= getkeycolor($binding_row[8]);
		$key_agr	= $binding_row[9];
		$bkg_xtr	= getkeycolor($binding_row[10]);
		$key_xtr	= $binding_row[11];
		if ($bkg_nor != "")
			echo "|" . $leadZ . "b=" . $bkg_nor;
		if ($key_nor != "")
			echo "|" . $leadZ . "t=" . cleantextWiki($key_nor);
		if ($key_shf != "")
			echo "|" . $leadZ . "s=" . cleantextWiki($key_shf);
		if ($key_ctl != "")
			echo "|" . $leadZ . "c=" . cleantextWiki($key_ctl);
		if ($key_alt != "")
			echo "|" . $leadZ . "a=" . cleantextWiki($key_alt);
		echo "\n";
	}
?>
}}

{{kbdlegend
<?php
	// legend
	foreach ($legend_table as $i => $legend_row)
	{
		$leadZ		= leadingZeros($i, 2);
		$leg_grp	= getkeycolor($legend_row[0]);
		$leg_dsc	= $legend_row[1];
		echo
"|" . $leadZ. "lgb=" . $leg_grp . "|" . $leadZ . "lgt=" . cleantextWiki($leg_dsc) . "\n";
	}
?>
}}
</textarea>
			<button onclick="select_and_copy_textarea();">Select All &amp; Copy</button>
<!--
			<button onclick="clear_textarea();">Clear</button>
			<button onclick="" disabled="disabled">Reset</button>
-->
		</main>
		<footer>
<?php include($path_lib2 . "footer-chart.php"); ?>
		</footer>
	</body>
</html>
