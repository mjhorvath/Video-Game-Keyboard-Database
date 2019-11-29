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
	include($path_root2	. "ssi/recaptchakey.php");

	$path_file		= "./keyboard-submit.php";
	$write_maximal_keys	= true;
	$debug_text		= "";
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
	$stylegroup_table	= [];
	$errors_table		= [];
	$gamesrecord_id		= 0;
	$gamesrecord_authors	= [];
	$stylesrecord_id	= 0;
	$stylesrecord_authors	= [];
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
	$platform_id		= 0;
	$format_name		= "";
	$layout_name		= "";
	$layout_authors		= [];
	$layout_keysnum		= 0;
	$layout_keygap		= 4;
	$layout_padding		= 18;
	$layout_fullsize_width		= 1200;
	$layout_fullsize_height		= 400;
	$layout_tenkeyless_width	= 1200;
	$layout_tenkeyless_height	= 400;
	$layout_legend_padding		= 36;
	$layout_legend_height		= 72;
	$layout_min_horizontal	= 0;
	$layout_max_horizontal	= 0;
	$layout_min_vertical	= 0;
	$layout_max_vertical	= 0;
	$layout_legend_top	= 0;

	// open MySQL connection
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
	selThisLanguageStrings();
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
	selLegendColors();
	selKeyStyles();
	selKeyStyleClasses();

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

	// color select options
	$option_string = "<option class=\"optnon\">non</option>";
	$color_count = count($color_array);
	for ($i = 0; $i < $color_count; $i++)
	{
		$option_string .= "<option class=\"opt" . $color_array[$i] . "\">" . $color_array[$i] . "</option>";
	}

	echo
"<!DOCTYPE HTML>
<html lang=\"" . $language_code . "\">
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
		<title>" . cleantextHTML($page_title_a . $temp_separator . $page_title_b) . "</title>
		<link rel=\"canonical\" href=\"" . $can_url . "\"/>
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_root2 . "favicon.png\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_root2 . "style_normalize.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style_common.css\"/>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\""	. cleantextHTML($language_description		. $temp_game_name . ". ("	. $temp_style_name . ", "	. $temp_layout_name . ", "	. $temp_format_name)	. ")\"/>
		<meta name=\"keywords\" content=\""	. cleantextHTML($language_keywords . ","	. $temp_game_name . ","		. $temp_style_name . ","	. $temp_layout_name . ","	. $temp_format_name)	. "\"/>
";
	echo writeAnalyticsTracking();
	echo
"		<style type=\"text/css\">\n";
	include($path_lib2 . "submit_" . $style_filename . ".css");
	echo
"		</style>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style_submit.css\"/>
		<script src=\"" . $path_root2 . "java/jquery-3.3.1.min.js\"></script>
		<script src=\"" . $path_lib1 . "keyboard-submit.js\"></script>
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
var color_table = ['non',";
	$color_count = count($color_array);
	for ($i = 0; $i < $color_count; $i++)
	{
		echo "'" . $color_array[$i] . "'";
		if ($i < $color_count - 1)
			echo ",";
	}
	echo "];
var class_table = [];
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
					$bkg_nor = intval($binding_row[ 0]);
					$bkg_shf = intval($binding_row[ 2]);
					$bkg_ctl = intval($binding_row[ 4]);
					$bkg_alt = intval($binding_row[ 6]);
					$bkg_agr = intval($binding_row[ 8]);
					$bkg_xtr = intval($binding_row[10]);
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
				echo
				"	[" .
				$low_nor . ", " .
				$upp_nor . ", " .
				$low_agr . ", " .
				$upp_agr . ", " .
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
		<img id="waiting" src="<?php echo $path_lib1; ?>animated_loading_icon.webp" alt="loading" style="position:fixed;display:block;z-index:10;width:100px;height:100px;left:50%;top:50%;margin-top:-50px;margin-left:-50px;"/>
		<div id="butt_min" class="side_butt" title="Toggle Side Panel" onclick="toggle_left_pane(0);"><img src="<?php echo $path_lib1; ?>icon_min.png"/></div>
		<div id="butt_max" class="side_butt" title="Toggle Side Panel" onclick="toggle_left_pane(1);"><img src="<?php echo $path_lib1; ?>icon_max.png"/></div>
		<div id="pane_lft">
			<div id="tbar_lft">
				<div id="butt_inp" class="tabs_butt" title="Toggle Input Panel" onclick="switch_left_pane(0);"><img src="<?php echo $path_lib1; ?>icon_inp.png"/></div>
				<div id="butt_hlp" class="tabs_butt" title="Toggle Info Panel"  onclick="switch_left_pane(1);"><img src="<?php echo $path_lib1; ?>icon_hlp.png"/></div>
			</div>
			<div id="pane_inp" style="display:block;">
<?php echo $debug_text; ?>
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
					<div id="email_table" class="emltbl inbtop" style="margin:auto;">
						<div class="emlrow"><div class="emlcll"><label for="email_1">Name: </label></div><div class="emlcll"><input class="email_input"  type="text" name="email_1" id="email_1" onchange="flag_eml_dirty();" placeholder="First and last name"  required="required" autocomplete="off" data-lpignore="true"/></div></div>
						<div class="emlrow"><div class="emlcll"><label for="email_2">Email:</label></div><div class="emlcll"><input class="email_input" type="email" name="email_2" id="email_2" onchange="flag_eml_dirty();" placeholder="Return email address" required="required" autocomplete="off" data-lpignore="true"/></div></div>
						<div class="emlrow"><div class="emlcll"><label for="email_3">Messg:</label></div><div class="emlcll"><textarea class="email_textarea"        name="email_3" id="email_3" onchange="flag_eml_dirty();" placeholder="Message to admin"     required="required" autocomplete="off"></textarea></div></div>
					</div>
					<div id="email_recaptcha" class="g-recaptcha" data-callback="flag_cap_dirty" data-sitekey="<?php echo writeRecaptchaKey(); ?>"></div>
					<p style="text-align:left;">For human verification purposes, please click the checkbox labeled "I'm not a robot".</p>
					<input name="email_4" id="email_4" type="hidden" value=""/>
					<input name="email_5" id="email_5" type="hidden" value=""/>
					<input name="email_6" id="email_6" type="hidden" value=""/>
					<input name="email_7" id="email_7" type="hidden" value=""/>
					<input name="email_8" id="email_8" type="hidden" value=""/>
					<input name="email_9" id="email_9" type="hidden" value=""/>
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
				<div id="butt_kbd" class="tabs_butt" title="Toggle Keyboard Panel"    onclick="switch_right_pane(0);"><img src="<?php echo $path_lib1; ?>icon_kbd.png"/></div>
				<div id="butt_csv" class="tabs_butt" title="Toggle Spreadsheet Panel" onclick="switch_right_pane(1);"><img src="<?php echo $path_lib1; ?>icon_spd.png"/></div>
			</div>
			<div id="pane_kbd" style="display:block;">
				<header>
					<div class="boxdiv">
						<!-- I don't like how these float/stack when the window size is very small. -->
						<span>Game Title:</span>
						<input style="font-size:x-large;" id="game_tit" type="text" size="25" maxlength="100" placeholder="Game Title" title="Game Title" autocomplete="off" onchange="flag_doc_dirty();" value="<?php echo $page_title_a; ?>"/>
						<input style="font-size:x-large;" id="game_url" type="text" size="25" maxlength="100" placeholder="URL String" title="URL String" autocomplete="off" onchange="flag_doc_dirty();" value="<?php echo $game_seo; ?>" disabled="disabled"/>
						<span style="float:right;"><?php echo cleantextHTML($temp_layout_name) . "<br>" . cleantextHTML($temp_style_name); ?></span>
					</div>
				</header>
				<div class="boxdiv" style="position:relative;width:<?php echo $layout_max_horizontal; ?>px;height:<?php echo $layout_max_vertical; ?>px;padding:0;">
					<form enctype="multipart/form-data" accept-charset="UTF-8" style="margin:0;padding:0;">
						<div id="keydiv" style="position:relative;left:<?php echo $layout_padding; ?>px;top:<?php echo $layout_padding; ?>px;width:<?php echo $layout_max_horizontal; ?>px;height:<?php echo $layout_max_vertical; ?>px;padding:0;margin:0;">
<?php
	// print error messages
	for ($i = 0; $i < count($errors_table); $i++)
	{
		echo
"							<h3>" . cleantextHTML($errors_table[$i]) . "</h3>";
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
				$low_nor	= cleantextHTML($position_row[4]);
				$upp_nor	= cleantextHTML($position_row[5]);
				$low_agr	= cleantextHTML($position_row[6]);
				$upp_agr	= cleantextHTML($position_row[7]);
				$key_num	= $position_row[8];
				$key_opt	= $position_row[9];
				$img_wid	= 48;
				$img_hgh	= 48;
				$img_pos_x	= $pos_wid/2 - $img_wid/2;
				$img_pos_y	= $pos_hgh/2 - $img_hgh/2;
				if (array_key_exists($i, $binding_table))
				{
					// should I use cleantextHTML here?
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
						$display = "none";
					}
					else
					{
						$display = "block";
					}
					echo
"								<img id=\"capimg_" . $i . "\" class=\"capimg\" style=\"left:" . $img_pos_x . "px;top:" . $img_pos_y . "px;width:" . $img_wid . "px;height:" . $img_hgh . "px;display:" . $display . ";\" src=\"" . $img_uri . "\"/>\n";
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
					<div id="table_legend" class="inbtop">
<?php
	for ($i = 0; $i < count($color_array); $i++)
	{
		$leg_value = "";
		$leg_color = getkeycolor($i+1);
		for ($j = 0; $j < count($legend_table); $j++)
		{
			$leg_group = $legend_table[$j][0];
			if (($leg_group-1) == $i)
			{
				$leg_value = cleantextHTML($legend_table[$j][1]);
				break;
			}
		}
		if ($i % 3 == 0)
		{
			echo
"						<div class=\"legtbl inbtop\">\n";
		}
		echo
"							<div class=\"legrow\"><div class=\"legcll legbox leg" . $leg_color . "\">" . $leg_color . "</div><div class=\"legcll legtxt\"><input id=\"form_cap" . $leg_color . "\" type=\"text\" size=\"15\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $leg_value . "\"/></div></div>\n";
		if ($i % 3 == 2)
		{
			echo
"						</div>\n";
		}
	}
?>
					</div>
				</div>
				<div id="flxdiv">
					<div class="inbtop comdiv">
						<h3><?php echo cleantextHTML($language_keyboard); ?></h3>
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
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $combo_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $combo_des . "\"/></div>
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
					<div class="inbtop comdiv">
						<h3><?php echo cleantextHTML($language_mouse); ?></h3>
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
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $mouse_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $mouse_des . "\"/></div>
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
					<div class="inbtop comdiv">
						<h3><?php echo cleantextHTML($language_joystick); ?></h3>
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
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $joystick_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $joystick_des . "\"/></div>
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
					<div class="inbtop comdiv">
						<h3><?php echo cleantextHTML($language_note); ?></h3>
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
								<div class=\"notcll txtone\"><textarea onchange=\"flag_doc_dirty();\">" . $note_des . "</textarea></div>
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
					<div class="inbtop comdiv">
						<h3><?php echo cleantextHTML($language_cheat); ?></h3>
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
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $cheat_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $cheat_des . "\"/></div>
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
					<div class="inbtop comdiv">
						<h3><?php echo cleantextHTML($language_console); ?></h3>
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
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $console_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $console_des . "\"/></div>
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
					<div class="inbtop comdiv">
						<h3><?php echo cleantextHTML($language_emote); ?></h3>
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
								<div class=\"notcll cllone\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $emote_com . "\"/></div>
								<div class=\"notcll clltwo\">=</div>
								<div class=\"notcll cllthr\"><input type=\"text\" maxlength=\"100\" autocomplete=\"off\" onchange=\"flag_doc_dirty();\" value=\"" . $emote_des . "\"/></div>
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
				<footer>
<?php include($path_lib2 . "keyboard-footer.php"); ?>
				</footer>
			</div>
			<div id="pane_tsv" style="display:none;">
				<h2>Tab-Separated Values (TSV)</h2>
				<p style="max-width:60em;">You should be able to copy and paste the following tab-separated values (TSV) into MS Excel or some other spreadsheet program and continue to edit the values from there. Alternatively, you could import the values directly into a SQL database using the <code>LOAD DATA</code> SQL command. (Make sure to delete the header row first, however.)</p>
				<p style="max-width:60em;">The string <code>\N</code> (with an upper-case &quot;n&quot; and one backslash) indicates a value of null, and may represent an auto-increment field in SQL. The string <code>\\n</code> (with a lower-case &quot;n&quot; and two backslashes) indicates a newline character.</p>
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
