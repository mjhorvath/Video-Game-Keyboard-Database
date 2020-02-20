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
	include($path_ssi2 . "recaptchakey.php");

	$path_file		= "./chart-submit.php";		// this file
	$commandouter_table	= [];
	$commandouter_count	= 0;
	$commandlabel_table	= [];
	$commandlabel_count	= 0;
	$write_maximal_keys	= true;			// should make this user-configurable
	$position_table		= [];			// populated by selPositionsChart()
	$keystyle_table		= [];			// populated by selKeyStylesChart()
	$binding_table		= [];			// populated by selBindingsChart()
	$legend_table		= [];			// populated by selLegendsChart()
	$author_table		= [];			// populated by selAuthorsChart()
	$style_table		= [];			// utilized by "footer-chart.php"
	$stylegroup_table	= [];			// utilized by "footer-chart.php"
	$gamesrecord_id		= 0;			// set by selThisGamesRecordChart()
	$gamesrecord_authors	= [];			// populated by selContribsGamesChart(), utilized by "footer-chart.php"
	$stylesrecord_id	= 0;			// set by selThisStylesRecordChart()
	$stylesrecord_authors	= [];			// populated by selContribsStylesChart(), utilized by "footer-chart.php"
	$style_filename		= "";			// set by selThisStyleChart()
	$style_name		= "";			// set by selThisStyleChart() and checkURLParameters(), utilized by checkForErrors()
	$game_name		= "";			// set by checkURLParameters(), utilized by checkForErrors()
	$platform_name		= "";			// set by checkURLParameters(), utilized by checkForErrors()
	$platform_id		= 0;			// set by checkURLParameters(), utilized by checkForErrors()
	$format_name		= "";			// set by checkURLParameters(), utilized by checkForErrors()
	$layout_name		= "";			// set by checkURLParameters(), utilized by checkForErrors()
	$layout_authors		= [];			// populated by selContribsLayoutsChart(), utilized by "footer-chart.php"
	$layout_keysnum		= 0;			// reset by selThisLayoutChart(), hopefully obsolete
	$layout_keygap		= 4;			// reset by selThisLayoutChart()
	$layout_padding		= 18;			// reset by selThisLayoutChart()
	$layout_fullsize_width		= 1200;		// reset by selThisLayoutChart()
	$layout_fullsize_height		= 400;		// reset by selThisLayoutChart()
	$layout_tenkeyless_width	= 1200;		// reset by selThisLayoutChart()
	$layout_tenkeyless_height	= 400;		// reset by selThisLayoutChart()
	$layout_legend_padding		= 36;		// reset by selThisLayoutChart()
	$layout_legend_height		= 72;		// reset by selThisLayoutChart()
	$layout_min_horizontal	= 0;			// reset by selThisLayoutChart()
	$layout_max_horizontal	= 0;			// reset by selThisLayoutChart()
	$layout_min_vertical	= 0;			// reset by selThisLayoutChart()
	$layout_max_vertical	= 0;			// reset by selThisLayoutChart()
	$layout_legend_top	= 0;			// reset by selThisLayoutChart()
	$option_string		= "";
	$color_string		= "";
	$commandlabel_string	= "";
	$binding_string		= "";

	// open MySQL connection
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
	selPositionsChart();
	selThisLayoutChart();
	selThisPlatformChart();
	selThisGamesRecordChart();
	selThisStylesRecordChart();
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

	// close MySQL connection
	mysqli_close($con);

	checkForErrors();
	pageTitle();

	// layout outer bounds
	if ($ten_bool == 0)
	{
		$layout_min_horizontal	= -$layout_padding;
		$layout_max_horizontal	=  $layout_padding * 2 + $layout_tenkeyless_width;
		$layout_min_vertical	= -$layout_padding;
		$layout_max_vertical	=  $layout_padding * 2 + $layout_tenkeyless_height;
	}
	else if ($ten_bool == 1)
	{
		$layout_min_horizontal	= -$layout_padding;
		$layout_max_horizontal	=  $layout_padding * 2 + $layout_fullsize_width;
		$layout_min_vertical	= -$layout_padding;
		$layout_max_vertical	=  $layout_padding * 2 + $layout_fullsize_height;
	}

	// non!
	$option_string .= "<option class=\"optnon\">non</option>";
	$color_string .= "\t0:[0,'non',0],\n";
	foreach ($binding_color_table as $i => $binding_color_value)
	{
		$color_id	= $binding_color_value[0];
		$color_class	= $binding_color_value[1];
		$color_sort	= $binding_color_value[2];	// not used right now
		$option_string .= "<option class=\"opt" . $color_class . "\">" . $color_class . "</option>";
		$color_string .= "\t" . ($i+1) . ":[" . $color_id . ",'" . $color_class . "'," . $color_sort. "],\n";
	}
	$color_string = rtrim($color_string, ",\n");
	$color_string .= "\n";

	foreach ($commandlabel_table as $i => $commandlabel_value)
	{
		// commandtype_id, commandlabel_string, commandtype_abbrv, commandtype_input, commandtype_keygroup
		$commandtype_id		= $commandlabel_value[0];
		$commandtype_abbrv	= $commandlabel_value[2];
		$commandtype_input	= $commandlabel_value[3];
		$commandtype_keygroup	= $commandlabel_value[4];
		$commandlabel_string .= "\t" . $i . ":[" . $commandtype_id . ",'" . $commandtype_abbrv . "'," . $commandtype_input . "," . $commandtype_keygroup . "],\n";
	}
	$commandlabel_string = rtrim($commandlabel_string, ",\n");
	$commandlabel_string .= "\n";

	// cleaning!
	if (($gamesrecord_id > 0) && ($stylesrecord_id > 0))
	{
		foreach ($position_table as $i => $position_row)
		{
			$low_nor	= "'" . cleantextJS($position_row[ 4])	. "'";
			$upp_nor	= "'" . cleantextJS($position_row[ 5])	. "'";
			$low_agr	= "'" . cleantextJS($position_row[ 6])	. "'";
			$upp_agr	= "'" . cleantextJS($position_row[ 7])	. "'";
			if (array_key_exists($i, $binding_table))
			{
				$binding_row = $binding_table[$i];
				$cap_nor = "'" . cleantextJS($binding_row[ 1])	. "'";
				$cap_shf = "'" . cleantextJS($binding_row[ 3])	. "'";
				$cap_ctl = "'" . cleantextJS($binding_row[ 5])	. "'";
				$cap_alt = "'" . cleantextJS($binding_row[ 7])	. "'";
				$cap_agr = "'" . cleantextJS($binding_row[ 9])	. "'";
				$cap_xtr = "'" . cleantextJS($binding_row[11])	. "'";
				$img_fil = "'" . $binding_row[12]		. "'";
				$img_uri = "'" . $binding_row[13]		. "'";
				$bkg_nor = $binding_row[ 0];
				$bkg_shf = $binding_row[ 2];
				$bkg_ctl = $binding_row[ 4];
				$bkg_alt = $binding_row[ 6];
				$bkg_agr = $binding_row[ 8];
				$bkg_xtr = $binding_row[10];
			}
			else
			{
				$cap_nor = "''";
				$cap_shf = "''";
				$cap_ctl = "''";
				$cap_alt = "''";
				$cap_agr = "''";
				$cap_xtr = "''";
				$img_fil = "''";
				$img_uri = "''";
				$bkg_nor = 0;
				$bkg_shf = 0;
				$bkg_ctl = 0;
				$bkg_alt = 0;
				$bkg_agr = 0;
				$bkg_xtr = 0;
			}
			$binding_string .=
			"\t" . $i . ":[" .
			$low_nor . "," .
			$upp_nor . "," .
			$low_agr . "," .
			$upp_agr . "," .
			$cap_nor . "," .
			$cap_shf . "," .
			$cap_ctl . "," .
			$cap_alt . "," .
			$cap_agr . "," .
			$cap_xtr . "," .
			$img_fil . "," .
			$img_uri . "," .
			$bkg_nor . "," .
			$bkg_shf . "," .
			$bkg_ctl . "," .
			$bkg_alt . "," .
			$bkg_agr . "," .
			$bkg_xtr . "],\n";
		}
		$binding_string = rtrim($binding_string, ",\n");
		$binding_string .= "\n";
	}

	// cleaning!
	echo
"<!DOCTYPE HTML>
<html lang=\"" . $language_code . "\">
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
		<title>" . cleantextHTML($page_title_a . $temp_separator . $page_title_b) . "</title>
		<link rel=\"canonical\" href=\"" . $can_url . "\"/>
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_lib1 . "favicon.png\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_root2 . "style_normalize.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style-footer.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style-submit.css\"/>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\""	. cleantextHTML($language_description		. $temp_game_name . ". ("	. $temp_style_name . ", "	. $temp_layout_name . ", "	. $temp_format_name)	. ")\"/>
		<meta name=\"keywords\" content=\""	. cleantextHTML($language_keywords . ","	. $temp_game_name . ","		. $temp_style_name . ","	. $temp_layout_name . ","	. $temp_format_name)	. "\"/>\n";
	echo writeAnalyticsTracking();
	echo
"		<style type=\"text/css\">\n";
	include($path_lib2 . "submit-" . $style_filename . ".css");
	echo
"		</style>
		<script src=\"" . $path_root2 . "java/jquery-3.3.1.min.js\"></script>
		<script src=\"" . $path_lib1  . "java-common.js\"></script>
		<script src=\"" . $path_lib1  . "java-submit.js\"></script>
		<script src=\"" . $path_lib1  . "java-footer.js\"></script>
		<script src=\"https://www.google.com/recaptcha/api.js\"></script>
		<script>
var record_id = " . $gamesrecord_id . ";
var color_table =\n{\n" . $color_string . "};
var commandlabel_table =\n{\n" . $commandlabel_string . "};
var binding_table =\n{\n" . $binding_string . "};
		</script>
	</head>\n";
?>
	<body onload="init_submissions();">
		<img id="waiting" src="<?php echo $path_lib1; ?>animated-loading-icon.webp" alt="loading" style="position:fixed;display:block;z-index:10;width:100px;height:100px;left:50%;top:50%;margin-top:-50px;margin-left:-50px;"/>
		<div id="butt_min" class="side_butt" title="Toggle Side Panel" onclick="toggle_left_pane(0);"><img src="<?php echo $path_lib1; ?>icon-min.png"/></div>
		<div id="butt_max" class="side_butt" title="Toggle Side Panel" onclick="toggle_left_pane(1);"><img src="<?php echo $path_lib1; ?>icon-max.png"/></div>
		<div id="pane_lft">
			<div id="tbar_lft">
				<div id="butt_inp" class="tabs_butt" title="Toggle Input Panel" onclick="switch_left_pane(0);"><img src="<?php echo $path_lib1; ?>icon-inp.png"/></div>
				<div id="butt_hlp" class="tabs_butt" title="Toggle Info Panel"  onclick="switch_left_pane(1);"><img src="<?php echo $path_lib1; ?>icon-hlp.png"/></div>
			</div>
			<div id="pane_inp" style="display:block;">
				<div id="table_inp" class="inptbl" style="margin:auto;">
					<div class="inprow"><div class="inphed">Param</div><div class="inphed">Value</div><div class="inphed">Color</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_keynum" title="Key ID#"			>keynum:</label></div><div class="inpcll"><input id="inp_keynum" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Key ID#"	disabled="disabled"/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_lownor" title="Normal lowercase"	>lownor:</label></div><div class="inpcll"><input id="inp_lownor" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Normal lower"	disabled="disabled"/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_uppnor" title="Normal uppercase"	>uppnor:</label></div><div class="inpcll"><input id="inp_uppnor" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Normal upper"	disabled="disabled"/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_lowagr" title="AltGr lowercase"		>lowagr:</label></div><div class="inpcll"><input id="inp_lowagr" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="AltGr lower"	disabled="disabled"/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_uppagr" title="AltGr uppercase"		>uppagr:</label></div><div class="inpcll"><input id="inp_uppagr" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="AltGr upper"	disabled="disabled"/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capnor" title="Normal caption"		>capnor:</label></div><div class="inpcll"><input id="inp_capnor" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Normal caption"	/></div><div class="inpcll"><select id="sel_capnor" class="selnon" size="1" autocomplete="off"><?php echo $option_string; ?></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capshf" title="Shift caption"		>capshf:</label></div><div class="inpcll"><input id="inp_capshf" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Shift caption"	/></div><div class="inpcll"><select id="sel_capshf" class="selnon" size="1" autocomplete="off"><?php echo $option_string; ?></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capctl" title="Control caption"		>capctl:</label></div><div class="inpcll"><input id="inp_capctl" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Control caption"/></div><div class="inpcll"><select id="sel_capctl" class="selnon" size="1" autocomplete="off"><?php echo $option_string; ?></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capalt" title="Alt caption"		>capalt:</label></div><div class="inpcll"><input id="inp_capalt" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Alt caption"	/></div><div class="inpcll"><select id="sel_capalt" class="selnon" size="1" autocomplete="off"><?php echo $option_string; ?></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capagr" title="AltGr caption"		>capagr:</label></div><div class="inpcll"><input id="inp_capagr" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="AltGr caption"	/></div><div class="inpcll"><select id="sel_capagr" class="selnon" size="1" autocomplete="off"><?php echo $option_string; ?></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_capxtr" title="Extra caption"		>capxtr:</label></div><div class="inpcll"><input id="inp_capxtr" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Extra caption"	/></div><div class="inpcll"><select id="sel_capxtr" class="selnon" size="1" autocomplete="off"><?php echo $option_string; ?></select></div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_imgfil" title="Image filename"		>imgfil:</label></div><div class="inpcll"><input id="inp_imgfil" class="inplft" type="text" size="11" maxlength="100" autocomplete="off" title="Image filename"	/></div><div class="inpcll">n/a</div></div>
					<div class="inprow"><div class="inpcll"><label for="inp_imguri" title="Image data-URI"		>imguri:</label></div><div class="inpcll"><input id="inp_imguri" class="inplft" type="text" size="11"                 autocomplete="off" title="Image data-URI"	/></div><div class="inpcll">n/a</div></div>
				</div>
				<div id="button_inp" style="margin:1em;">
					<button id="set_key_button" type="button" style="padding:0.3em 1em;" autocomplete="off" disabled="disabled" onclick="key_save_changes();" title="Save changes to key">Set Key</button><button id="unset_key_button" type="button" style="padding:0.3em 1em;" autocomplete="off" disabled="disabled" onclick="key_revert_changes();" title="Revert changes to key">Revert</button>
				</div>
				<p>Enter new lines by typing <code>\n</code>.</p>
				<hr/>
				<form id="email_form" method="post" enctype="multipart/form-data" accept-charset="UTF-8" action="">
					<div class="emltbl inbtop" style="margin:auto;">
						<div class="emlrow"><div class="emlcll"><label for="email_1">Name: </label></div><div class="emlcll"><input class="email_input"  type="text" name="email_1" id="email_1" onchange="flag_eml_dirty();" placeholder="First and last name"  required="required" autocomplete="off"/></div></div>
						<div class="emlrow"><div class="emlcll"><label for="email_2">Email:</label></div><div class="emlcll"><input class="email_input" type="email" name="email_2" id="email_2" onchange="flag_eml_dirty();" placeholder="Return email address" required="required" autocomplete="off"/></div></div>
						<div class="emlrow"><div class="emlcll"><label for="email_3">Messg:</label></div><div class="emlcll"><textarea class="email_textarea"        name="email_3" id="email_3" onchange="flag_eml_dirty();" placeholder="Message to admin"     required="required" autocomplete="off"></textarea></div></div>
					</div>
					<div class="emltbl inbtop" style="margin:auto;">
						<div class="emlrow"><div class="emlcll"><input type="checkbox" name="email_11" id="email_11" style="margin:0.2em;"/></div><div class="emlcll"><label for="email_11">This is a brand new schema versus an update to an existing schema</label></div></div>
					</div>
					<div id="email_recaptcha" class="g-recaptcha" data-callback="flag_cap_dirty" data-sitekey="<?php echo writeRecaptchaKey(); ?>"></div>
					<p style="text-align:left;">For human verification purposes, please click the checkbox labeled "I'm not a robot".</p>
					<input name="email_4"  id="email_4"  type="hidden" value=""/>
					<input name="email_5"  id="email_5"  type="hidden" value=""/>
					<input name="email_6"  id="email_6"  type="hidden" value=""/>
					<input name="email_7"  id="email_7"  type="hidden" value=""/>
					<input name="email_8"  id="email_8"  type="hidden" value=""/>
					<input name="email_9"  id="email_9"  type="hidden" value=""/>
					<input name="email_10" id="email_10" type="hidden" value=""/>
					<div><button id="set_doc_button" type="button" style="padding:0.3em 1em;" disabled="disabled" autocomplete="off" onclick="document_save_changes();" title="Submit changes to data" data-callback="recaptchaCallback">Submit Data</button><button id="unset_doc_button" type="button" style="padding:0.3em 1em;" disabled="disabled" autocomplete="off" onclick="document_revert_changes();" title="Reset data to original state" data-callback="recaptchaCallback">Reset</button></div>
				</form>
			</div>
			<div id="pane_hlp" style="display:none;">
				<h2>Help</h2>
				<p>Enter new lines by typing <code>\n</code>.</p>
				<p>Images must be PNG.</p>
				<p>Images must be 48x48px and 32 bits.</p>
				<p>Images must have both a file name and a data-URI.</p>
				<p>Image data-URIs must be less than 256KiB in size.</p>
				<p>You can use an online service such as <a target="_blank" href="https://websemantics.uk/tools/image-to-data-uri-converter/">Data-URI base64 converter</a> to convert PNG images to data-URIs.</p>
				<p>Here is a set of conventions with regard to group coloring that has worked out pretty well for me so far:</p>
				<div class="legtbl inbtop">
					<div class="legrow"><div class="legcll legbox legred">red</div><div class="legcll legtxt">Combat/Actions</div></div>
					<div class="legrow"><div class="legcll legbox legyel">yel</div><div class="legcll legtxt">Targeting/Unit selection/Inventory</div></div>
					<div class="legrow"><div class="legcll legbox leggrn">grn</div><div class="legcll legtxt">Movement/Navigation</div></div>
					<div class="legrow"><div class="legcll legbox legcyn">cyn</div><div class="legcll legtxt">Communication/Chat/Orders</div></div>
					<div class="legrow"><div class="legcll legbox legblu">blu</div><div class="legcll legtxt">Camera/Point of view</div></div>
					<div class="legrow"><div class="legcll legbox legmag">mag</div><div class="legcll legtxt">Game Interface/Controls/Menus</div></div>
				</div>
			</div>
		</div>
		<div id="pane_rgt">
			<div id="tbar_rgt">
				<div id="butt_kbd" class="tabs_butt" title="Toggle Keyboard Panel" onclick="switch_right_pane(0);"><img src="<?php echo $path_lib1; ?>icon-kbd.png"/></div>
				<div id="butt_sql" class="tabs_butt" title="Toggle SQL Panel"      onclick="switch_right_pane(1);"><img src="<?php echo $path_lib1; ?>icon-sql.png"/></div>
			</div>
			<div id="pane_kbd" style="display:block;">
				<header>
					<div class="boxdiv">
						<!-- I don't like how these float/stack when the window size is very small. -->
						<span style="font-size:large;">Game Title:</span>
						<input style="font-size:x-large;" id="game_tit" type="text" size="25" maxlength="100" placeholder="Game Title" title="Game Title" autocomplete="off" onchange="flag_doc_dirty();" value="<?php echo $page_title_a; ?>"/>
						<input style="font-size:x-large;" id="game_url" type="text" size="25" maxlength="100" placeholder="URL String" title="URL String" autocomplete="off" onchange="flag_doc_dirty();" value="<?php echo $game_seo; ?>" disabled="disabled"/>
						<span style="font-size:large;">GRID:<?php echo $gamesrecord_id; ?></span>
						<span style="float:right;"><?php echo cleantextHTML($temp_layout_name) . "<br>" . cleantextHTML($temp_style_name); ?></span>
					</div>
				</header>
				<div class="boxdiv" style="position:relative;width:<?php echo $layout_max_horizontal; ?>px;height:<?php echo $layout_max_vertical; ?>px;padding:0;">
					<form enctype="multipart/form-data" accept-charset="UTF-8" style="margin:0;padding:0;">
						<div id="keydiv" style="position:relative;left:<?php echo $layout_padding; ?>px;top:<?php echo $layout_padding; ?>px;width:<?php echo $layout_max_horizontal; ?>px;height:<?php echo $layout_max_vertical; ?>px;padding:0;margin:0;">
<?php
	// cleaning!
	// print error messages
	for ($i = 0; $i < count($errors_table); $i++)
	{
		echo
"							<h3>" . cleantextHTML($errors_table[$i]) . "</h3>\n";
	}
	// keys
	if (($gamesrecord_id > 0) && ($stylesrecord_id > 0))
	{
		foreach ($position_table as $i => $position_row)
		{
			// cleaning!
			// these get cleaned later by the print_key_html function
			// position_left, position_top, position_width, position_height, symbol_norm_low, symbol_norm_cap, symbol_altgr_low, symbol_altgr_cap, key_number, lowcap_optional, numpad
			$key_sty	= array_key_exists($i, $keystyle_table) ? getkeyclass($keystyle_table[$i][0]) : "";		// non!
			$pos_lft	= $position_row[ 0] + $layout_keygap/2;
			$pos_top	= $position_row[ 1] + $layout_keygap/2;
			$pos_wid	= $position_row[ 2] - $layout_keygap;		// 4px
			$pos_hgh	= $position_row[ 3] - $layout_keygap;		// 4px
			$low_nor	= $position_row[ 4];
			$upp_nor	= $position_row[ 5];
			$low_agr	= $position_row[ 6];
			$upp_agr	= $position_row[ 7];
			$key_num	= $position_row[ 8];
			$key_opt	= $position_row[ 9];
			$key_ten	= $position_row[10];
			$img_wid	= 48;
			$img_hgh	= 48;
			$img_pos_x	= $pos_wid/2 - $img_wid/2;
			$img_pos_y	= $pos_hgh/2 - $img_hgh/2;
			if (array_key_exists($i, $binding_table))
			{
				// cleaning!
				// these get cleaned later by the print_key_html function
				// normal_group, normal_action, shift_group, shift_action, ctrl_group, ctrl_action, alt_group, alt_action, altgr_group, altgr_action, extra_group, extra_action, image_file, image_uri, key_number
				$binding_row	= $binding_table[$i];
				$bkg_nor = getkeycolor($binding_row[ 0]);
				$cap_nor = $binding_row[ 1];
				$bkg_shf = getkeycolor($binding_row[ 2]);
				$cap_shf = $binding_row[ 3];
				$bkg_ctl = getkeycolor($binding_row[ 4]);
				$cap_ctl = $binding_row[ 5];
				$bkg_alt = getkeycolor($binding_row[ 6]);
				$cap_alt = $binding_row[ 7];
				$bkg_agr = getkeycolor($binding_row[ 8]);
				$cap_agr = $binding_row[ 9];
				$bkg_xtr = getkeycolor($binding_row[10]);
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
"							<div id=\"keyout_" . $i . "\" class=\"keyout cap" . $bkg_nor . " " . $key_sty . "\" style=\"left:" . $pos_lft . "px;top:" . $pos_top . "px;width:" . $pos_wid . "px;height:" . $pos_hgh . "px;\">\n";
			// icon images
			if (($img_uri != "") || ($write_maximal_keys == true))
			{
				if ($img_uri == "")
				{
					$img_display = "none";
				}
				else
				{
					$img_display = "block";
				}
				echo
"								<img id=\"capimg_" . $i . "\" class=\"capimg\" style=\"left:" . $img_pos_x . "px;top:" . $img_pos_y . "px;width:" . $img_wid . "px;height:" . $img_hgh . "px;display:" . $img_display . ";\" src=\"" . $img_uri . "\"/>\n";
			}
			// key captions
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
			// normal key labels
			if (($upp_nor != "") || ($write_maximal_keys == true))
			{
				print_key_html("uppnor_" . $i, "uppnor", null, $upp_nor);
			}
			if (($low_nor != "") || ($write_maximal_keys == true))
			{
				if ($key_opt == false)
				{
					print_key_html("lownor_" . $i, "lownor", null, $low_nor);
				}
				else
				{
					print_key_html("lownor_" . $i, "keynon", null, $low_nor);
				}
			}
			// altgr key labels
			if (($upp_agr != "") || ($write_maximal_keys == true))
			{
				print_key_html("uppagr_" . $i, "uppagr", null, $upp_agr);
			}
			if (($low_agr != "") || ($write_maximal_keys == true))
			{
				if ($key_opt == false)
				{
					print_key_html("lowagr_" . $i, "lownor", null, $low_agr);
				}
				else
				{
					print_key_html("lowagr_" . $i, "keynon", null, $low_agr);
				}
			}
			echo
"							</div>\n";
		}
	}
?>
						</div>
					</form>
				</div>
				<div class="boxdiv">
					<div class="inbtop" style="margin-right:1em;margin-bottom:1em;">
						<div class="keyout capnon" style="position:relative;left:2px;top:2px;width:68px;height:68px;">
							<div class="capshf">Shift</div>
							<div class="capctl">Ctrl</div>
							<div class="capalt">Alt</div>
							<div class="uppnor">Upcase</div>
							<div class="lownor">Lowcase</div>
							<div class="capopf">Caption</div>
						</div>
					</div>
					<div class="inbtop legflx">
<?php
	// non!
	// legend
	// check if this would be less complicated with flexbox
	// the math here is only correct if the number of colors is a multiple of 3
	$count_rows = 0;
	foreach ($binding_color_table as $i => $binding_color_value)
	{
		$leg_value = "";
		$leg_color = getkeycolor($i+1);
		if (array_key_exists($i, $legend_table))
			$leg_value = $legend_table[$i][1];
		if ($count_rows % 3 == 0)
		{
			echo
"						<div class=\"inbtop legtbl\">\n";
		}
		echo
"							<div class=\"legrow\"><div class=\"legcll legbox leg" . $leg_color . "\">" . $leg_color . "</div><div class=\"legcll legtxt\"><input id=\"form_cap" . $leg_color . "\" type=\"text\" size=\"15\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $leg_value . "\"/></div></div>\n";
		if ($count_rows % 3 == 2)
		{
			echo
"						</div>\n";
		}
		$count_rows += 1;
	}
?>
					</div>
				</div>
				<div class="comflx">
<?php
	// commands
	foreach ($commandlabel_table as $i => $commandlabel_value)
	{
		if (array_key_exists($i, $commandouter_table))
			$commandinner_table = $commandouter_table[$i];
		else
			$commandinner_table = [];
		$commandlabel_label = $commandlabel_value[1];
		$commandlabel_abbrv = $commandlabel_value[2];
		$commandlabel_input = $commandlabel_value[3];
		$commandlabel_group = $commandlabel_value[4];
		// cleaning!
		echo
"					<div class=\"inbtop comdiv\">
						<h3>" . cleantextHTML($commandlabel_label) . "</h3>
						<div id=\"table_" . $commandlabel_abbrv . "\" class=\"nottbl\">\n";
		foreach ($commandinner_table as $j => $commandinner_value)
		{
			$commandinner_input = $commandinner_value[1];
			$commandinner_combo = $commandinner_value[2];
			$commandinner_group = $commandinner_value[3];
			// does the command have a keygroup?
			if ($commandlabel_group == 1)
			{
				// non!
				$select_class = "selnon";
				$commandoption_string = "<option class=\"optnon\">non</option>";
				foreach ($binding_color_table as $i => $binding_color_value)
				{
					$color_id	= $binding_color_value[0];
					$color_class	= $binding_color_value[1];
					$color_sort	= $binding_color_value[2];	// not used right now
					if ($color_id == $commandinner_group)
					{
						$commandoption_string .= "<option class=\"opt" . $color_class . "\" selected=\"selected\">" . $color_class . "</option>";
						$select_class = "sel" . $color_class;
					}
					else
					{
						$commandoption_string .= "<option class=\"opt" . $color_class . "\">" . $color_class . "</option>";
					}
				}
				echo
"							<div class=\"notrow\">
								<div class=\"notcll grpone\"><select class=\"" . $select_class . "\" size=\"1\" autocomplete=\"off\" onchange=\"flag_doc_dirty();update_select_style(this);\">" . $commandoption_string . "</select></div>
								<div class=\"notcll grptwo\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $commandinner_input . "\"/></div>
								<div class=\"notcll grpthr\">=</div>
								<div class=\"notcll grpfor\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $commandinner_combo . "\"/></div>
								<div class=\"notcll grpfiv\"><button class=\"grpsub\" type=\"button\">-</button></div>
							</div>\n";
			}
			// does the command at least have two parts?
			elseif ($commandlabel_input == 1)
			{
				echo
"							<div class=\"notrow\">
								<div class=\"notcll inpone\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $commandinner_input . "\"/></div>
								<div class=\"notcll inptwo\">=</div>
								<div class=\"notcll inpthr\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $commandinner_combo . "\"/></div>
								<div class=\"notcll inpfor\"><button class=\"inpsub\" type=\"button\">-</button></div>
							</div>\n";
			}
			// "additional notes" are a special case requiring a textarea instead of input
			else
			{
				echo
"							<div class=\"notrow\">
								<div class=\"notcll txtone\"><textarea maxlength=\"1024\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\">" . $commandinner_combo . "</textarea></div>
								<div class=\"notcll txttwo\"><button class=\"txtsub\" type=\"button\">-</button></div>
							</div>\n";
			}
		}
		if ($commandlabel_group == 1)
		{
			echo
"							<div class=\"notrow\">
								<div class=\"notcll grpone\"></div>
								<div class=\"notcll grptwo\"></div>
								<div class=\"notcll grpthr\"></div>
								<div class=\"notcll grpfor\"></div>
								<div class=\"notcll grpfiv\"><button class=\"grpadd\" type=\"button\">+</button></div>
							</div>\n";
		}
		elseif ($commandlabel_input == 1)
		{
			echo
"							<div class=\"notrow\">
								<div class=\"notcll inpone\"></div>
								<div class=\"notcll inptwo\"></div>
								<div class=\"notcll inpthr\"></div>
								<div class=\"notcll inpfor\"><button class=\"inpadd\" type=\"button\">+</button></div>
							</div>\n";
		}
		else
		{
			echo
"							<div class=\"notrow\">
								<div class=\"notcll txtone\"></div>
								<div class=\"notcll txttwo\"><button class=\"txtadd\" type=\"button\">+</button></div>
							</div>\n";
		}
		echo
"						</div>
					</div>\n";
	}
 ?>
				</div>
				<footer>
<?php include($path_lib2 . "footer-chart.php"); ?>
				</footer>
			</div>
			<div id="pane_tsv" style="display:none;">
				<h2>SQL Code</h2>
				<p style="max-width:60em;">You should be able to copy and paste the following SQL code into a text file and execute the resulting script.</p>
				<p style="max-width:60em;">The string <code>\\n</code> (with a lower-case &quot;n&quot; and two backslashes) indicates a newline character.</p>
				<p style="max-width:60em;">If you run out of space, the text areas below can be resized by dragging on the bottom-right corners.</p>
				<h4>Legend</h4>
				<textarea id="legend_tsv" style="width:60em;height:20em;font-family:monospace;" wrap="off" autocomplete="off"></textarea>
				<p><button style="font-size:smaller;padding:0.3em 1em;" onclick="fill_legend();">Fetch Data</button><button style="font-size:smaller;padding:0.3em 1em;" onclick="clear_legend();">Clear Data</button><button style="font-size:smaller;padding:0.3em 1em;" onclick="text_select_and_copy('legend_tsv');">Select &amp; Copy</button></p>
				<h4>Commands</h4>
				<textarea id="command_tsv" style="width:60em;height:20em;font-family:monospace;" wrap="off" autocomplete="off"></textarea>
				<p><button style="font-size:smaller;padding:0.3em 1em;" onclick="fill_commands();">Fetch Data</button><button style="font-size:smaller;padding:0.3em 1em;" onclick="clear_commands();">Clear Data</button><button style="font-size:smaller;padding:0.3em 1em;" onclick="text_select_and_copy('command_tsv');">Select &amp; Copy</button></p>
				<h4>Bindings</h4>
				<textarea id="binding_tsv" style="width:60em;height:20em;font-family:monospace;" wrap="off" autocomplete="off"></textarea>
				<p><button style="font-size:smaller;padding:0.3em 1em;" onclick="fill_bindings();">Fetch Data</button><button style="font-size:smaller;padding:0.3em 1em;" onclick="clear_bindings();">Clear Data</button><button style="font-size:smaller;padding:0.3em 1em;" onclick="text_select_and_copy('binding_tsv');">Select &amp; Copy</button></p>
			</div>
		</div>
	</body>
</html>
