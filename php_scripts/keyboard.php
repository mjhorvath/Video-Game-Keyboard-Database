<?php
	header("Content-Type: text/html; charset=utf8");
	$path_root	= "../";
	$page_title	= "Video Game Keyboard Diagrams";
	$page_desc	= "Visual keyboard hotkey & binding diagrams for video games and other software.";
	$page_keywords	= "visual,keyboard,keys,diagrams,charts,overlay,shortcuts,bindings,mapping,maps,controls,hotkeys,database,print,printable,video game,software,guide,reference";
	$page_onload	= "Select_Init();";
	$foot_array	= ["copyright","accordion","license_kbd"];
	$java_array	= ["keyboard-js.php", $path_root . "java/jquery-1.4.2.min.js", $path_root . "java/jquery-accordionmenu.js"];
	$stys_array	= [$path_root . "style_accordion.css",$path_root . "style_radio.css",$path_root . "style_keyboard.css"];
	$analytics	= true;
	$is_short	= false;
	include($path_root . "ssi/normalpage.php");
	print($page_top);
?>
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

	include("./keyboard-connection.php");
	include("./keyboard-common.php");

	$con = mysqli_connect($con_website, $con_username, $con_password, $con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");

	$genre_array		= [];
	$genre_order_array	= [];
	$game_array		= [];
	$stylegroup_array	= [];
	$style_array		= [];
	$stylerecord_array	= [];
	$layout_array		= [];
	$platform_array		= [];
	$platform_order_array	= [];
/*
	function doGenres($in_result)
	{
		global $genre_array, $genre_order_array, $game_array;
		$genre_count = 1;
		while ($genre_row = mysqli_fetch_row($in_result))
		{
			// genre_name, genre_displayorder
			$genre_array[] = $genre_row[0];
			$genre_order_array[$genre_row[1]-1] = $genre_count;	// not sure this is working correctly, need to test it
			$game_array[] = [[],[]];
			$genre_count += 1;
		}
	}
*/
	function doGenres($in_result)
	{
		global $genre_array, $genre_order_array, $game_array;
		while ($genre_row = mysqli_fetch_row($in_result))
		{
			// genre_name, genre_displayorder
			$genre_array[] = $genre_row[0];
			$genre_order_array[] = $genre_row[1];	// not sure this is working correctly, need to test it
			$game_array[] = [[],[]];
		}
	}
	function sortGames()
	{
		global $genre_order_array, $genre_array, $game_array;
		array_multisort($genre_order_array, $genre_array, $game_array);
	}
	function doGames($in_result)
	{
		global $game_array;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// genre_id, game_id, game_name, game_friendlyurl
			$genre_id = $game_row[0];
			$game_array[$genre_id-1][0][] = $game_row[1];
			$game_array[$genre_id-1][1][] = $game_row[2];
			$game_array[$genre_id-1][2][] = $game_row[3];
		}
	}
	function doStylegroups($in_result)
	{
		global $stylegroup_array, $style_array;
		while ($stylegroup_row = mysqli_fetch_row($in_result))
		{
			// stylegroup_id, stylegroup_name
			$stylegroup_array[$stylegroup_row[0]-1] = $stylegroup_row[1];
			$style_array[$stylegroup_row[0]-1] = [[],[]];
		}
	}
	function doStyles($in_result)
	{
		global $style_array;
		while ($style_row = mysqli_fetch_row($in_result))
		{
			// stylegroup_id, style_id, style_name
			$style_array[$style_row[0]-1][0][] = $style_row[1];
			$style_array[$style_row[0]-1][1][] = $style_row[2];
		}
	}
	function doPlatforms($in_result)
	{
		global $platform_array, $layout_array;
		$platform_table = [];
		while ($platform_row = mysqli_fetch_row($in_result))
		{
			// platform_id, platform_name, platform_displayorder
			$platform_id = $platform_row[0];
			$platform_name = $platform_row[1];
			$platform_displayorder = $platform_row[2];
			$platform_array[$platform_id-1] = $platform_name;
			$layout_array[$platform_id-1] = [[],[]];
			$platform_order_array[$platform_id-1] = $platform_displayorder;
		}
		array_multisort($platform_order_array, $platform_array, $layout_array);
	}
	function doLayouts($in_result)
	{
		global $layout_array;
		while ($layout_row = mysqli_fetch_row($in_result))
		{
			// platform_id, layout_id, layout_name
			$layout_array[$layout_row[0]-1][0][] = $layout_row[1];
			$layout_array[$layout_row[0]-1][1][] = $layout_row[2];
		}
	}

//	error_log("get_genres_front");
	callProcedure0($con, "get_genres_front", "doGenres");
//	error_log("get_games_front");
	callProcedure0($con, "get_games_front", "doGames");
	sortGames();
//	error_log("get_stylegroups_front");
	callProcedure0($con, "get_stylegroups_front", "doStylegroups");
//	error_log("get_styles_front");
	callProcedure0($con, "get_styles_front", "doStyles");
//	error_log("get_platforms_front");
	callProcedure0($con, "get_platforms_front", "doPlatforms");
//	error_log("get_layouts_front");
	callProcedure0($con, "get_layouts_front", "doLayouts");

	mysqli_close($con);
?>
<img id="waiting" src="../images/loader_metal.gif" alt="loading" style="position:fixed;display:block;z-index:10;width:240px;height:240px;left:50%;top:50%;transform:translate(-50% -50%);"/>
<form name="keyboard_select">
	<input type="hidden" name="gam" value=""/>
	<input type="hidden" name="lay" value=""/>
	<input type="hidden" name="sty" value=""/>
	<input type="hidden" name="seo" value=""/>
	<div style="margin:auto;text-align:center;">
		<div class="acc_div">
			<div id="gam_check" class="acc_check">&#x2714;</div>
			<div id="gam_xmark" class="acc_xmark">&#x2718;</div>
			<h2>1. Select a Game:</h2>
			<ul id="gam_menu" class="acc_mnu">
<?php
	foreach ($genre_array as $i => $genre_value)
	{
		echo
"				<li>\n";
		if ($i == 0)
		{
			echo
"					<a menu=\"gam\"><span class=\"arrw_a\" style=\"display:none;\">&#9658;</span><span class=\"arrw_b\" style=\"display:inline;\">&#9660;</span> " . $genre_value . "</a>
					<ul style=\"height:15em;display:block;\">\n";
		}
		else
		{
			echo
"					<a menu=\"gam\"><span class=\"arrw_a\" style=\"display:inline;\">&#9658;</span><span class=\"arrw_b\" style=\"display:none;\">&#9660;</span> " . $genre_value . "</a>
					<ul style=\"height:15em;display:none;\">\n";
		}
		$id_array	= $game_array[$i][0];
		$name_array	= $game_array[$i][1];
		$seourl_array	= $game_array[$i][2];
		array_multisort($name_array, $id_array, $seourl_array);
		for ($j = 0; $j < count($id_array); $j++)
		{
			echo
"						<li><a id=\"gam_" . ($id_array[$j] - 1) . "\" menu=\"gam\" value=\"" . $id_array[$j] . "\" class=\"acc_nrm\" onclick=\"Set_Select_Value(this);Set_Layout(" . ($id_array[$j]-1) . ",this);\">" . $name_array[$j] . "</a></li>\n";
		}
		echo
"					</ul>
				</li>\n";
	}
?>
			</ul>
		</div>
		<div class="acc_div">
			<div id="lay_check" class="acc_check">&#x2714;</div>
			<div id="lay_xmark" class="acc_xmark">&#x2718;</div>
			<h2>2. Select a Keyboard:</h2>
			<ul id="lay_menu" class="acc_mnu">
<?php
	foreach ($platform_array as $i => $platform_value)
	{
		echo
"				<li>\n";
		if ($i == 0)
		{
			echo
"					<a menu=\"lay\"><span class=\"arrw_a\" style=\"display:none;\">&#9658;</span><span class=\"arrw_b\" style=\"display:inline;\">&#9660;</span> " . $platform_value . "</a>
					<ul style=\"height:21.6em;display:block;\">\n";
		}
		else
		{
			echo
"					<a menu=\"lay\"><span class=\"arrw_a\" style=\"display:inline;\">&#9658;</span><span class=\"arrw_b\" style=\"display:none;\">&#9660;</span> " . $platform_value . "</a>
					<ul style=\"height:21.6em;display:none;\">\n";
		}
		$id_array	= $layout_array[$i][0];
		$name_array	= $layout_array[$i][1];
//		array_multisort($name_array, $id_array);
		for ($j = 0; $j < count($id_array); $j++)
		{
			echo
"						<li><a id=\"lay_" . ($id_array[$j] - 1) . "\" menu=\"lay\" value=\"" . $id_array[$j] . "\" class=\"acc_dis\" onclick=\"Set_Select_Value(this);Set_Style(" . ($id_array[$j]-1) . ",this);\">" . $name_array[$j] . "</a></li>\n";
		}
		echo
"					</ul>
				</li>\n";
	}
?>
			</ul>
		</div>
		<div class="acc_div">
			<div id="sty_check" class="acc_check">&#x2714;</div>
			<div id="sty_xmark" class="acc_xmark">&#x2718;</div>
			<h2>3. Select a Theme:</h2>
			<ul id="sty_menu" class="acc_mnu">
<?php
	foreach ($stylegroup_array as $i => $stylegroup_value)
	{
		echo
"				<li>\n";
		if ($i == 0)
		{
			echo
"					<a menu=\"sty\"><span class=\"arrw_a\" style=\"display:none;\">&#9658;</span><span class=\"arrw_b\" style=\"display:inline;\">&#9660;</span> " . $stylegroup_value . "</a>
					<ul style=\"height:21.6em;display:block;\">\n";
		}
		else
		{
			echo
"					<a menu=\"sty\"><span class=\"arrw_a\" style=\"display:inline;\">&#9658;</span><span class=\"arrw_b\" style=\"display:none;\">&#9660;</span> " . $stylegroup_value . "</a>
					<ul style=\"height:21.6em;display:none;\">\n";
		}
		$id_array	= $style_array[$i][0];
		$name_array	= $style_array[$i][1];
//		array_multisort($name_array, $id_array);
		for ($j = 0; $j < count($id_array); $j++)
		{
			echo
"						<li><a id=\"sty_" . ($id_array[$j] - 1) . "\" menu=\"sty\" value=\"" . $id_array[$j] . "\" class=\"acc_dis\" onclick=\"Set_Select_Value(this)\">" . $name_array[$j] . "</a></li>\n";
		}
		echo
"					</ul>
				</li>\n";
	}
?>
			</ul>
		</div>
		<div class="acc_div">
			<div id="fmt_check" class="acc_check">&#x2714;</div>
			<div id="fmt_xmark" class="acc_xmark">&#x2718;</div>
			<h2>4. Select a Format:</h2>
			<label for="rad0" class="container">HTML
				<input id="rad0" type="radio" name="fmtradio" value="0" checked="checked"/>
				<span class="checkmark"></span>
			</label>
			<label label for="rad1" class="container">SVG
				<input id="rad1" type="radio" name="fmtradio" value="1"/>
				<span class="checkmark"></span>
			</label>
			<label label for="rad2" class="container">MediaWiki
				<input id="rad2" type="radio" name="fmtradio" value="2"/>
				<span class="checkmark"></span>
			</label>
			<label label for="rad3" class="container">Editor
				<input id="rad3" type="radio" name="fmtradio" value="3"/>
				<span class="checkmark"></span>
			</label>
			<label label for="rad4" class="container"><s>PDF</s> [TBD]
				<input id="rad4" type="radio" name="fmtradio" value="4" disabled="disabled"/>
				<span class="checkmark"></span>
			</label>
		</div>
		<div class="acc_div">
			<div id="but_check" class="acc_check">&#x2714;</div>
			<div id="but_xmark" class="acc_xmark">&#x2718;</div>
			<h2>5. Create the Diagram:</h2>
			<input id="letsgo" type="button" value="All Set, Let's Go!" onclick="Check_Values_and_Spawn()"/>
			<p id="submit_warn_wrap" class="warn_no">Try selecting a game, keyboard, theme and format, and create the diagram again!</p>
		</div>
	</div>
</form>
<hr/>
<h2>Description:</h2>
<p>This PHP form generates a keyboard control diagram in a new window. You can select from among hotkeys and bindings for various video games and other software. If you do not have a JavaScript-enabled browser, then you may refer to the <a href="keyboard-list.php">master list</a> table instead, as it should be viewable in a JavaScript-less browser.</p>
<h2>Instructions:</h2>
<ol>
	<li>Select a game (key bindings).</li>
	<li>Select a keyboard (key positions).</li>
	<li>Select a theme (visual formatting).</li>
	<li>Select a format (output media type).</li>
	<li>Click on the 'Create Diagram' button. A new window with your selected diagram will appear.</li>
	<li>View or print the page in the new window.</li>
</ol>
<h2>Licenses &amp; Submissions:</h2>
<p>The source code for this project is licensed under the <a target="_blank" href="GNU General Public License">GPLv3</a>. The content is licensed under the <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a>. Visit the <a href="https://github.com/mjhorvath/vgkd">GitHub repository</a> for the project's source code. The <a href="keyboard-log.php">change log</a> contains the project's update history and credits, as well as links to further reading.</p>
<p>To submit a new set of bindings, you can fill out <a href="../files/binding_template_us104key.xlsx">this spreadsheet</a> and <a href="http://isometricland.net/email.php">email</a> me the contents (copy and paste) when you are done. Note that any content you submit falls under the <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a> license, as per the project as a whole. Your name will then appear at the bottom of each chart, as well as in the change log.</p>
<p>I have also recently started developing a form-based submission page. You can use it to submit changes to existing bindings by selecting the "Editor" option in Step 4, above. Or, you can get started making a brand new set of bindings with this <a href="keyboard-diagram-blank-sample.php?sty=15&lay=1&fmt=3">blank sample</a>.</p>
<p></p>
<h2>MediaWiki, SVG &amp; PDF:</h2>
<p>I have created templates for MediaWiki that do basically the same thing as the other charts available on this site. You can find the templates as well as instructions on how to use them at StrategyWiki and Wikia, <a target="_blank" href="http://strategywiki.org/wiki/Template:Kbdchart">here</a> and <a target="_blank" href="http://templates.wikia.com/wiki/Template:Kbdchart">here</a>. By selecting the "MediaWiki" format type, you can generate the code you will need to fill the template with data and display a keyboard diagram on a MediaWiki wiki. On the destination wiki page, you may also want to wrap the chart in a scrollable DIV element, since the chart is wider than a typical browser window.</p>
<p>I have also created SVG versions of the charts, which you can also select in the "Formats" menu above. I have not migrated over to using SVG images exclusively yet, because they are less compatible with older browsers, and I have not figured out how I want to implement the mouse and joystick controls listings, yet. (I have not yet figured out how to create containers that expand, wrap and scale automatically as the volume of text inside increases.)</p>
<p>PDF versions of the charts will hopefully be added at some point in the future.</p>
<a name="print_tips"></a>
<h2>Printing Tips:</h2>
<ol>
	<li>When printing, most likely the chart will not fit within a single letter- or legal-sized page, even when selecting 'Landscape' mode instead of 'Portrait' mode in your printer settings. Luckily, your browser or printer may have a 'shrink to fit' feature that you can take advantage of to automatically adjust the size of the printed page output. Unfortunately, Google Chrome is missing a 'shrink to fit' feature by default, so I recommend investigating one of the workarounds discussed on Super User, <a target="_blank" href="https://superuser.com/questions/979741/how-can-i-make-chrome-shrink-to-fit-on-printing">here</a>.</li>
	<li>Remember also to enable printing of background colors and images. This setting is found in 'Page Setup' (Mozilla Firefox and Internet Explorer) or within the print dialog itself (Google Chrome). Sadly, this option does not exist at all in Microsoft Edge. I recommend using a different browser.</li>
	<li>If the colors or keyboard theme are not to your liking, select a different "Theme" from among the options at the top of this page, then try generating the chart again.</li>
	<li>Printing at 96dpi (dots-per-inch) and 100% scaling should result in a printed page that closely approximates the size and dimensions of many real physical keyboards. Of course, not every keyboard is exactly the same, so YMMV.</li>
	<li>On Windows, some Web browers (Google Chrome for instance) use your desktop DPI scaling settings to adjust the size of on-screen HTML elements, resulting in a document that can appear larger or smaller than normal. I'm not 100% sure this affects the printed page output, however.</li>
	<li>Note, that the darker themes will use a lot of ink.</li>
</ol>
<?php print($page_bot); ?>
