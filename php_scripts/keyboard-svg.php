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

	// NOTE: I need to check versus the style *names* instead of IDs if I 
	// want the code to still work after re-indexing the "styles" table.

	header("Content-type: image/svg+xml");

	include("./keyboard-connection.php");
	include("./keyboard-common.php");

	$con = mysqli_connect($con_website,$con_username,$con_password,$con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");

	$game_seo		= array_key_exists("seo", $_GET) ? $_GET["seo"] : "";
	$game_id		= array_key_exists("gam", $_GET) ? intval(ltrim($_GET["gam"], "0")) : null;
	$style_id		= array_key_exists("sty", $_GET) ? intval(ltrim($_GET["sty"], "0")) : 15;
	$layout_id		= array_key_exists("lay", $_GET) ? intval(ltrim($_GET["lay"], "0")) : 1;
	$svg_bool		= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0")) : 0;
	$fix_url		= false;
	$final_url		= "";
	$stylegroup_id		= 0;
	$legend_number		= 12;
	$position_table		= [];
	$keystyle_table		= [];
	$binding_table		= [];
	$legend_table		= [];
	$author_table		= [];
	$style_filename		= "";
	$style_name		= "";
	$gamesrecord_id		= 0;
	$gamesrecord_author	= "";
	$stylesrecord_id	= 0;
	$stylesrecord_author	= "";
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
	$layout_keygap		= 4;

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

	function doThisStyle($in_result)
	{
		global $style_filename, $style_name, $stylegroup_id;
		$style_row = mysqli_fetch_row($in_result);
		$style_filename = $style_row[0];
		$style_name = cleantextSVG($style_row[1]);
		$stylegroup_id = $style_row[2];
	}
	function doStyles($in_result)
	{
		global $style_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// style_id, style_name, style_whiteonblack, stylegroup_id
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
			// keystyle_group, key_number
			$keystyle_table[$temp_row[1]-1] = $temp_row;
		}
	}
	function doGames($in_result)
	{
		global $game_name, $game_seo;
		$game_row = mysqli_fetch_row($in_result);
		$game_name = cleantextSVG($game_row[0]);
		$game_seo = $game_row[1];
	}
	function doGamesSEO($in_result)
	{
		global $game_name, $game_id;
		$game_row = mysqli_fetch_row($in_result);
		$game_name = cleantextSVG($game_row[0]);
		$game_id = intval($game_row[1]);
	}
	function doPlatforms($in_result)
	{
		global $platform_name;
		$platform_row = mysqli_fetch_row($in_result);
		$platform_name = cleantextSVG($platform_row[0]);
	}
	function doGamesRecords($in_result)
	{
		// record_id, author_id
		global $gamesrecord_id, $gamesrecord_author;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		$gamesrecord_id = $gamesrecord_row[0];
		$gamesrecord_author = cleantextSVG(getAuthorName($gamesrecord_row[1]));
	}
	function doStylesRecords($in_result)
	{
		// record_id, author_id
		global $stylesrecord_id, $stylesrecord_author;
		$stylesrecord_row = mysqli_fetch_row($in_result);
		$stylesrecord_id = $stylesrecord_row[0];
		$stylesrecord_author = cleantextSVG(getAuthorName($stylesrecord_row[1]));
	}
	function doLayouts($in_result)
	{
		global $layout_platform, $layout_name, $layout_title, $layout_mouse, $layout_joystick, $layout_combos, $layout_notes, $layout_legend, $layout_author, $layout_description, $layout_keywords, $layout_keysnum;
		$layout_row		= mysqli_fetch_row($in_result);
		$layout_platform	= $layout_row[0];
		$layout_name		= cleantextSVG($layout_row[1]);
		$layout_title		= cleantextSVG($layout_row[2]);
		$layout_mouse		= cleantextSVG($layout_row[3]);
		$layout_joystick	= cleantextSVG($layout_row[4]);
		$layout_combos		= cleantextSVG($layout_row[5]);
		$layout_notes		= cleantextSVG($layout_row[6]);
		$layout_legend		= cleantextSVG($layout_row[7]);		// heading no longer exists in the database
		$layout_description	= cleantextSVG($layout_row[8]);
		$layout_keywords	= cleantextSVG($layout_row[9]);
		$layout_author		= cleantextSVG(getAuthorName($layout_row[10]));
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

	mysqli_close($con);

	// validity checks
	$game_seo		= $game_seo ? $game_seo : "unrecognized-game";
	$temp_game_name		= $game_name ? $game_name : "Unrecognized Game";
	$temp_layout_name	= $layout_name ? $layout_name : "Unrecognized Layout";
	$temp_style_name	= $style_name ? $style_name : "Unrecognized Style";
	$temp_platform_name	= $platform_name ? $platform_name : "Unrecognized Platform";
	$thispage_title_a	= $temp_game_name;
	$thispage_title_b	= " - " . $layout_title . " - " . $temp_platform_name . " " . $temp_layout_name . " - " . $temp_style_name;

	// validity checks (should check the layout here too... but)
	if (!checkStyle($style_id))
	{
		$style_id = 15;
		$fix_url = true;
	}

	$final_url = "http://isometricland.net/keyboard/keyboard-diagram-" . $game_seo . ".svg?sty=" . $style_id . "&lay=" . $layout_id . "&svg=" . $svg_bool;

	// fix URL
	if ($fix_url)
	{
		header("Location: " . $final_url);
	}
?>
<!--
This file was generated using Video Game Keyboard Diagrams by Michael Horvath.
http://isometricland.net/keyboard/keyboard.php
This work is licensed under the Creative Commons Attribution-ShareAlike 3.0
United States License. To view a copy of this license, visit
http://creativecommons.org/licenses/by-sa/3.0/us/ or send a letter to Creative
Commons, PO Box 1866, Mountain View, CA 94042, USA.
<?php
	if (($gamesrecord_author) && ($gamesrecord_author != "Michael Horvath"))
	{
		echo
"Binding scheme by: " . $gamesrecord_author . ".\n";
	}
	if (($layout_author) && ($layout_author != "Michael Horvath"))
	{
		echo
"Keyboard layout by: " . $layout_author . ".\n";
	}
	if (($stylesrecord_author) && ($stylesrecord_author != "Michael Horvath"))
	{
		echo
"Style design by: " . $stylesrecord_author . ".\n";
	}
?>
-->
<svg
	version="1.1"
	baseProfile="full"
	xmlns="http://www.w3.org/2000/svg"
	xmlns:xlink="http://www.w3.org/1999/xlink"
	xmlns:ev="http://www.w3.org/2001/xml-events"
	viewBox="-20 -20 1683 612"
	width="1683" height="612">
	<title><?php echo $thispage_title_a . $thispage_title_b; ?></title>
	<desc>Keyboard diagram for <?php echo $game_name; ?>.</desc>
	<metadata id="license"
		xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:cc="http://creativecommons.org/ns#">
		<rdf:RDF>
			<cc:Work rdf:about="">
				<dc:format>image/svg+xml</dc:format>
				<dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
			<cc:license rdf:resource="http://creativecommons.org/licenses/by-sa/3.0/" />
			</cc:Work>
			<cc:License rdf:about="http://creativecommons.org/licenses/by-sa/3.0/">
				<cc:permits rdf:resource="http://creativecommons.org/ns#Reproduction" />
				<cc:permits rdf:resource="http://creativecommons.org/ns#Distribution" />
				<cc:requires rdf:resource="http://creativecommons.org/ns#Notice" />
				<cc:requires rdf:resource="http://creativecommons.org/ns#Attribution" />
				<cc:permits rdf:resource="http://creativecommons.org/ns#DerivativeWorks" />
				<cc:requires rdf:resource="http://creativecommons.org/ns#ShareAlike" />
			</cc:License>
			<rdf:Description about=""
				dc:title="<?php echo $thispage_title_a . $thispage_title_b; ?>"
				dc:description="Keyboard diagram for <?php echo $game_name; ?>."
				dc:publisher="Video Game Keyboard Diagrams"
				dc:date="<?php echo date("Y-m-d H:i:s"); ?>"
				dc:format="image/svg+xml"
				dc:language="en" >
				<dc:creator>
					<rdf:Bag>
<?php
	if ($gamesrecord_author)
	{
		echo
"						<rdf:li>" . $gamesrecord_author . "</rdf:li>\n";
	}
	if (($layout_author) && ($layout_author != $gamesrecord_author))
	{
		echo
"						<rdf:li>" . $layout_author . "</rdf:li>\n";
	}
	if (($stylesrecord_author) && ($stylesrecord_author != $gamesrecord_author) && ($stylesrecord_author != $layout_author))
	{
		echo
"						<rdf:li>" . $stylesrecord_author . "</rdf:li>\n";
	}
?>
					</rdf:Bag>
				</dc:creator>
			</rdf:Description>
		</rdf:RDF>
	</metadata>
	<style type="text/css">
/* <![CDATA[ */
<?php include("svg_" . $style_filename . ".css"); ?>
/* ]]> */
	</style>
	<defs>
		<filter id="filt_1" x="-1" y="-1" width="72" height="72">
			<feOffset result="offOut" in="SourceAlpha" dx="1" dy="1" />
			<feGaussianBlur result="blurOut" in="offOut" stdDeviation="1" />
			<feBlend in="SourceGraphic" in2="blurOut" mode="normal" />
		</filter>
		<linearGradient id="grad_1" x1="0" x2="0" y1="0" y2="1">
			<stop offset="0.0" stop-color="white" stop-opacity="0.0" />
			<stop offset="1.0" stop-color="white" stop-opacity="1.0" />
		</linearGradient>
		<linearGradient id="grad_2" x1="0" x2="0" y1="0" y2="1">
			<stop offset="0.0" stop-color="white" stop-opacity="0.2" />
			<stop offset="0.5" stop-color="white" stop-opacity="0.2" />
			<stop offset="0.5" stop-color="white" stop-opacity="0.0" />
			<stop offset="1.0" stop-color="white" stop-opacity="0.0" />
		</linearGradient>
<?php
	if ($style_id == 9)
	{
		echo
'		<pattern id="pat01" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAE0lEQVQImWP4vx8CGaD0fwayRADXsTfBHa7CGAAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat02" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAIElEQVQImWP4/////f///99ngNL/GaD0fwYo/Z+BCDUAr8A9wZ1do0gAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat03" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAKElEQVQImUXLwREAIBCDwGjjxsrX1418gRRoFCqUsiQ3J9kZRv149gc33Svfk/J4xAAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat04" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAJUlEQVQImTXGsQEAMAjDMPf/o9LPzEDQJOLCXDrssMNuY3wCwB+U5TfJB20x8gAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat05" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAHUlEQVQImWNumC84X3C+4HzG+wwQwLSQAQJJEgEARWcORcItfCQAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat06" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAHUlEQVQImWNssD9of9D+oD3jfgYIYDrIAIEkiQAAiQcP0w/Ue9AAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat07" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAGElEQVQImWNsYIAAJijNwPgfXYSlkbAaAH1lAw9WJMEiAAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat08" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFklEQVQImWP8z9DIUM/QyMDEAAXkMQCuBAKQ/EVNLQAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat09" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAALUlEQVQImWNo+P+/oeH//wYmhkaGeoZGhnqmeiiL8T+Uwczo4HDwoIPDQTxqAJCDFk4qwQYgAAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat10" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAKElEQVQImWP+z+iwv4HRYT8zI0M9QyNDPQMTlG5k/A+hGZigdD0eNQCYExGRPdzmZgAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat11" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAHUlEQVQImWP8z9DIUM/QyMDEAAUsEH49QoSRCDUAgAMJE1nslWwAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat12" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAI0lEQVQImWNsYKhnaGSoZ2BsYIAAxv8MjQz1DI0IERYGwmoAoR0KihxALMEAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat13" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAJklEQVQImWNucNjP6OCwn5HxfyMDQ30jAwMzI0P9wYMM9QdJEgEAMlcWl9fR1DgAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat14" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAHElEQVQImWP+z7ifcT/jfkaGhv8QyAClG0gSAQAFxi0o8hVkgQAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat15" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAGUlEQVQImWP43/D///+G//8ZINT/BgayRADd6TfR4133+AAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat16" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAI0lEQVQImX2KMRIAAASA4uW8PIPBpq0uSi01ZElooLmyDs8zwm4OB6wkBqMAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat17" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAI0lEQVQImWNo+A8BjP8ZIICJgYGBoZGBgQEuwszoAGHgUQMAq/IMxKod1SMAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat18" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAGElEQVQImWNsYIAAxv9QBhMDOoOlkbAaAI9BAw/yqYY5AAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat19" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAF0lEQVQImWP4//9/w////xuYGKCAPAYA2rcHCfGqMmMAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat20" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAIElEQVQImWNo+P//f8P///8Z/kNZDP+hLAaoTAMDEWoA2fE30f0SY/YAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat21" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAH0lEQVQImY3KIQEAMAwEsZNe5xl5AUMhCYdramrq4zzZ8TfREKxKRAAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat22" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAHUlEQVQImWP4//9/w////xuYGKAAzmBsgLGIUAMA0YgO/7MiaWoAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat23" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAAAAADhZOFXAAAACXBIWXMAAAsSAAALEgHS3X78AAAAKElEQVQImXWKsQ0AMAyDbOVxf+bTyJClS8UEYlLXdQUEiMKhc3jK91mRSzFflGM2CwAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="pat24" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAIAAABLbSncAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAOElEQVQImXWOuxEAIAxCn+6S/fchw2CRxtODhk8BIMkfJDH0pLa5zS2WbQDobqCqxm4SUlUeT3cPx7qQPS4vVDYAAAAASUVORK5CYII=" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry00" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFUlEQVQYlWNkYGBoYMADmPBJDh8FAItIAJCfGlr0AAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry01" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFUlEQVQYlWMUEBBoYMADmPBJDh8FALxoAMDTemTaAAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry02" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFUlEQVQYlWNUUFBoYMADmPBJDh8FAO2IAPDmRQ9/AAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry03" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFUlEQVQYlWM0MDBoYMADmPBJDh8FAB63ASBvBuXXAAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry04" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFUlEQVQYlWN0cHBoYMADmPBJDh8FAE/XAVDN0NRiAAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry05" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFUlEQVQYlWMMCAhoYMADmPBJDh8FAID3AYCtiCn5AAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry06" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFUlEQVQYlWNMSEhoYMADmPBJDh8FALIXAbCxJBqxAAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry07" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFUlEQVQYlWMsKChoYMADmPBJDh8FAOM3AeA83He7AAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry08" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFUlEQVQYlWOsr6//z4AHMOGTHD4KAJJQAozLinLVAAAAAElFTkSuQmCC" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry10" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFklEQVQYlWNcsGBBAwMewIRPcvgoAAB2pgJw6td3owAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry12" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFklEQVQYlWM8cOBAAwMewIRPcvgoAADY5gLQiqFhGQAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
		<pattern id="gry16" patternUnits="userSpaceOnUse" width="8px" height="8px"><image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsSAAALEgHS3X78AAAAFklEQVQYlWP8//9/AwMewIRPcvgoAACaYwONca/KLAAAAABJRU5ErkJggg==" x="0" y="0" width="8px" height="8px" /></pattern>
';
	}
?>
	</defs>
<?php
	echo
"	<rect id=\"bkgrec\" x=\"-20\" y=\"-20\" width=\"1683\" height=\"611\" fill=\"none\" stroke=\"none\"/>\n";
	// validity checks
	if ($gamesrecord_id == 0)
	{
		echo
"	<text y=\"0\">No bindings found for game \"" . $temp_game_name . "\" on layout \"" . $temp_platform_name . " " . $temp_layout_name . "\".</text>";
	}
	// validity checks
	if ($stylesrecord_id == 0)
	{
		echo
"	<text y=\"20\">No configurations found for style \"" . $temp_style_name . "\" on layout \"" . $temp_platform_name . " " . $temp_layout_name . "\".</text>";
	}
	// keys
	if (($gamesrecord_id > 0) && ($stylesrecord_id > 0))
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
				$pos_wid	= $position_row[2] - $layout_keygap;		//4
				$pos_hgh	= $position_row[3] - $layout_keygap;
				$cap_low	= cleantextSVG($position_row[4]);
				$cap_hgh	= cleantextSVG($position_row[5]);
				$cap_rgt	= cleantextSVG($position_row[6]);
				$cap_opt	= $position_row[8];
				$img_wid	= 48;
				$img_hgh	= 48;
				$img_pos_x	= $layout_keygap/2 + $pos_wid/2 - $img_wid/2 - 1/2;
				$img_pos_y	= $layout_keygap/2 + $pos_hgh/2 - $img_hgh/2 - 1/2;

				if (array_key_exists($i, $binding_table))
				{
					$binding_row	= $binding_table[$i];
					$bkg_nor = getcolor($binding_row[0]);
					$key_nor = splittext(cleantextSVG($binding_row[1]));
					$bkg_shf = getcolor($binding_row[2]);
					$key_shf = splittext(cleantextSVG($binding_row[3]));
					$bkg_ctl = getcolor($binding_row[4]);
					$key_ctl = splittext(cleantextSVG($binding_row[5]));
					$bkg_alt = getcolor($binding_row[6]);
					$key_alt = splittext(cleantextSVG($binding_row[7]));
					$bkg_agr = getcolor($binding_row[8]);
					$key_agr = splittext(cleantextSVG($binding_row[9]));
					$bkg_xtr = getcolor($binding_row[10]);
					$key_xtr = splittext(cleantextSVG($binding_row[11]));
					$img_fil = $binding_row[12];
					$img_uri = $binding_row[14];
				}
				else
				{
					$bkg_nor = "non";
					$key_nor = [];
					$bkg_shf = "non";
					$key_shf = [];
					$bkg_ctl = "non";
					$key_ctl = [];
					$bkg_alt = "non";
					$key_alt = [];
					$bkg_agr = "non";
					$key_agr = [];
					$bkg_xtr = "non";
					$key_xtr = [];
					$img_fil = null;
					$img_uri = null;
				}

				$top_nor = $pos_hgh - 4;
				if ($cap_opt == true)
				{
					$top_nor += 14;
				}

				// mask
				if (($style_id == 5) || ($style_id == 6))	// Dark Gradient & Light Gradient
				{
					echo
"	<mask id=\"mask_" . $i . "\">\n" .
"		<rect x=\"0\" y=\"0\" width=\"" . ($pos_wid+1) . "\" height=\"" . ($pos_hgh+1) . "\" fill=\"url(#grad_1)\"/>\n" .
"	</mask>\n";
				}

				echo
"	<svg class=\"key\" x=\"" . ($pos_lft-0.5) . "\" y=\"" . ($pos_top-0.5) . "\" width=\"" . ($pos_wid+1) . "\" height=\"" . ($pos_hgh+1) . "\">\n";

				// rects & image
				if (($style_id == 5) || ($style_id == 6))	// Dark Gradient & Light Gradient
				{
					echo
"		<rect class=\"caprec rec" . $bkg_nor . "\" x=\"0.5\" y=\"0.5\" rx=\"4\" ry=\"4\" width=\"" . ($pos_wid) . "\" height=\"" . ($pos_hgh) . "\" mask=\"url(#mask_" . $i . ")\"/>\n";
				}
				else
				{
					echo
"		<rect class=\"caprec rec" . $bkg_nor . " rec" . $key_sty . "\" x=\"0.5\" y=\"0.5\" rx=\"4\" ry=\"4\" width=\"" . ($pos_wid) . "\" height=\"" . ($pos_hgh) . "\"/>\n";
				}
				if ($img_fil)
				{
					echo
"		<image x=\"" . $img_pos_x . "\" y=\"" . $img_pos_y . "\" width=\"" . $img_wid . "\" height=\"" . $img_hgh . "\" xlink:href=\"" . $img_uri . "\"/>\n";
				}
				if (($style_id == 16) || ($style_id == 18))	// CIELCh Shiny
				{
					echo
"		<rect x=\"0.5\" y=\"0.5\" rx=\"4\" ry=\"4\" width=\"" . ($pos_wid) . "\" height=\"" . ($pos_hgh) . "\" fill=\"url(#grad_2)\"/>\n";
				}

				// bindings backgrounds
				if ($style_id == 9)
				{
					$jcount		= 0;
					for ($j = 0; $j < count($key_shf); $j++)
					{
						echo
"		<rect class=\"bakshf\" x=\"1.0\" y=\"" . ($jcount++ * 12 + 3) . "\" width=\"" . ($pos_wid-1) . "\" height=\"13\" rx=\"1\" ry=\"1\"></rect>\n";
					}
					for ($j = 0; $j < count($key_ctl); $j++)
					{
						echo
"		<rect class=\"bakctl\" x=\"1.0\" y=\"" . ($jcount++ * 12 + 3) . "\" width=\"" . ($pos_wid-1) . "\" height=\"13\" rx=\"1\" ry=\"1\"></rect>\n";
					}
					for ($j = 0; $j < count($key_alt); $j++)
					{
						echo
"		<rect class=\"bakalt\" x=\"1.0\" y=\"" . ($jcount++ * 12 + 3) . "\" width=\"" . ($pos_wid-1) . "\" height=\"13\" rx=\"1\" ry=\"1\"></rect>\n";
					}
					for ($j = 0; $j < count($key_agr); $j++)
					{
						echo
"		<rect class=\"bakagr\" x=\"1.0\" y=\"" . ($jcount++ * 12 + 3) . "\" width=\"" . ($pos_wid-1) . "\" height=\"13\" rx=\"1\" ry=\"1\"></rect>\n";
					}
					for ($j = 0; $j < count($key_xtr); $j++)
					{
						echo
"		<rect class=\"bakxtr\" x=\"1.0\" y=\"" . ($jcount++ * 12 + 3) . "\" width=\"" . ($pos_wid-1) . "\" height=\"13\" rx=\"1\" ry=\"1\"></rect>\n";
					}
				}

				// caps
				if (($cap_opt == false) && ($cap_low != ""))
				{
					echo
"		<text class=\"caplow txt" . $bkg_nor . " txt" . $key_sty . "\" x=\"2.5\" y=\"" . ($pos_hgh-3.5) . "\">" . $cap_low . "</text>\n";
				}
				if ($cap_hgh != "")
				{
					echo
"		<text class=\"caphgh txt" . $bkg_nor . " txt" . $key_sty . "\" x=\"2.5\" y=\"13.5\">" . $cap_hgh . "</text>\n";
				}
				if ($cap_rgt != "")
				{
					echo
"		<text class=\"caprgt txt" . $bkg_nor . " txt" . $key_sty . "\" x=\"" . ($pos_wid-2.5) . "\" y=\"13.5\">" . $cap_rgt . "</text>\n";
				}
				for ($j = 0; $j < count($key_nor); $j++)
				{
					echo
"		<text class=\"keynor txt" . $bkg_nor . " txt" . $key_sty . " ideo\" x=\"2.5\" y=\"" . ($top_nor+0.5) . "\" dy=\"" . (($j+1) * -14) . "\">" . $key_nor[count($key_nor)-($j+1)] . "</text>\n";
				}

				// bindings text
				$jcount		= 0;
				for ($j = 0; $j < count($key_shf); $j++)
				{
					echo
"		<text class=\"keyshf hang\" x=\"" . ($pos_wid-2.5) . "\" y=\"" . ($jcount++ * 12 + 13) . "\">" . $key_shf[$j] . "</text>\n";

				}
				for ($j = 0; $j < count($key_ctl); $j++)
				{
					echo
"		<text class=\"keyctl hang\" x=\"" . ($pos_wid-2.5) . "\" y=\"" . ($jcount++ * 12 + 13) . "\">" . $key_ctl[$j] . "</text>\n";
				}
				for ($j = 0; $j < count($key_alt); $j++)
				{
					echo
"		<text class=\"keyalt hang\" x=\"" . ($pos_wid-2.5) . "\" y=\"" . ($jcount++ * 12 + 13) . "\">" . $key_alt[$j] . "</text>\n";
				}
				for ($j = 0; $j < count($key_agr); $j++)
				{
					echo
"		<text class=\"keyagr hang\" x=\"" . ($pos_wid-2.5) . "\" y=\"" . ($jcount++ * 12 + 13) . "\">" . $key_agr[$j] . "</text>\n";
				}
				for ($j = 0; $j < count($key_xtr); $j++)
				{
					echo
"		<text class=\"keyxtr hang\" x=\"" . ($pos_wid-2.5) . "\" y=\"" . ($jcount++ * 12 + 13) . "\">" . $key_xtr[$j] . "</text>\n";
				}

				echo
"	</svg>\n";
			}
		}
	}
?>
	<svg class="key legkey" x="1.5" y="501.5" width="69" height="69">
		<rect class="caprec recnon" x="0.5" y="0.5" rx="4" ry="4" width="68" height="68"/>
		<rect class="bakshf" x="1.0" y="3" width="67" height="12" rx="1" ry="1"></rect>
		<text class="keyshf hang" x="65.5" y="13">Shift</text>
		<rect class="bakctl" x="1.0" y="15" width="67" height="12" rx="1" ry="1"></rect>
		<text class="keyctl hang" x="65.5" y="25">Ctrl</text>
		<rect class="bakalt" x="1.0" y="27" width="67" height="12" rx="1" ry="1"></rect>
		<text class="keyalt hang" x="65.5" y="37">Alt</text>
		<text class="keynor txtnon ideo" x="2.5" y="50.5">Caption</text>
		<text class="caplow txtnon" x="2.5" y="64.5">Lowkey</text>
		<text class="caphgh txtnon" x="2.5" y="13.5">Upkey</text>
	</svg>
	<svg class="leg" x="101.5" y="501.5" width="1000" height="300">
<?php	// legend
	if ($stylegroup_id == 1)
	{
		$row_count = 0;
		for ($i = 0; $i < count($legend_table); $i++)
		{
			$legend_row = $legend_table[$i];
			if ($legend_row[0] != null)
			{
				$leg_grp = getcolor($legend_row[0]);
				$leg_dsc = cleantextSVG($legend_row[1]);
				$row_div = floor($row_count/3);
				$row_mod = $row_count % 3;
				echo
"		<rect class=\"caprec rec" . $leg_grp . "\" x=\"" . ($row_div*200+0.5) . "\" y=\"" . ($row_mod*20+0.5) . "\" width=\"16\" height=\"16\"/>\n" .
"		<text class=\"legtxt\" x=\"" . ($row_div*200+20.5) . "\" y=\"" . ($row_mod*20+14.5) . "\">" . $leg_dsc . "</text>\n";
				$row_count += 1;
			}
		}
	}
?>
	</svg>
</svg>
