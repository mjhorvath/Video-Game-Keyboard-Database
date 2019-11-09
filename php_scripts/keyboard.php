<?php
	header("Content-Type: text/html; charset=utf8");
	$path_root	= "../";
	$page_title	= "Video Game Keyboard Diagrams";
	$page_desc	= "Visual keyboard hotkey & binding diagrams for video games and other software.";
	$page_keywords	= "visual,keyboard,keys,diagrams,charts,overlay,shortcuts,bindings,mapping,maps,controls,hotkeys,database,print,printable,video game,software,guide,reference";
	$page_onload	= "Select_Init();";
	$foot_array	= ["copyright","accordion","license_kbd"];
	$java_array	= ["keyboard-js.php", $path_root . "java/jquery-1.4.2.min.js", $path_root . "java/jquery-accordionmenu.js"];
	$stys_array	= ["style_accordion.css","style_radio.css","style_frontend.css"];
	$analytics	= true;
	$is_short	= false;
	include($path_root . "ssi/normalpage.php");
	print($page_top);
?>
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

	include($path_root. "ssi/keyboard-connection.php");
	include("./keyboard-common.php");
	include("./keyboard-queries.php");

	$con = mysqli_connect($con_website, $con_username, $con_password, $con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");

	$genre_array		= [];
	$game_array		= [];
	$stylegroup_array	= [];
	$style_array		= [];
	$layout_array		= [];
	$platform_array		= [];
	$platform_order_array	= [];

	// MySQL queries
	selGenresFront();
	selGamesFront();
	selStylegroupsFront();
	selStylesFront();
	selPlatformsFront();
	selLayoutsFront();
	sortGamesFront();

	mysqli_close($con);
?>
<img id="waiting" src="animated_loading_icon.webp" alt="loading" style="position:fixed;display:block;z-index:10;width:100px;height:100px;left:50%;top:50%;margin-top:-50px;margin-left:-50px;"/>
<form name="keyboard_select">
	<input type="hidden" name="lay" value=""/>
	<input type="hidden" name="gam" value=""/>
	<input type="hidden" name="sty" value=""/>
	<input type="hidden" name="seo" value=""/>
	<div style="margin:auto;text-align:center;">
		<div class="acc_div">
			<div id="lay_check" class="acc_check">&#x2714;</div>
			<div id="lay_xmark" class="acc_xmark">&#x2718;</div>
			<h2>1. Select a Keyboard:</h2>
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
//		array_multisort($name_array, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $id_array);
		for ($j = 0; $j < count($id_array); $j++)
		{
			$this_id = $id_array[$j];
			$this_name = $name_array[$j];
			echo
"						<li><a id=\"lay_" . ($this_id-1) . "\" menu=\"lay\" value=\"" . $this_id . "\" class=\"acc_nrm\" onclick=\"Set_Select_Value(this);Set_Game(" . ($this_id-1) . ",this);Set_Style(" . ($this_id-1) . ",this);\">" . $this_name . "</a></li>\n";
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
			<h2>2. Select a Theme:</h2>
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
//		array_multisort($name_array, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $id_array);
		for ($j = 0; $j < count($id_array); $j++)
		{
			echo
"						<li><a id=\"sty_" . ($id_array[$j] - 1) . "\" menu=\"sty\" value=\"" . $id_array[$j] . "\" class=\"acc_dis\" onclick=\"Set_Select_Value(this);\">" . $name_array[$j] . "</a></li>\n";
		}
		echo
"					</ul>
				</li>\n";
	}
?>
			</ul>
		</div>
		<div class="acc_div">
			<div id="gam_check" class="acc_check">&#x2714;</div>
			<div id="gam_xmark" class="acc_xmark">&#x2718;</div>
			<h2>3. Select a Game:</h2>
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
		array_multisort($name_array, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $id_array, $seourl_array);
		for ($j = 0; $j < count($id_array); $j++)
		{
			echo
"						<li><a id=\"gam_" . ($id_array[$j] - 1) . "\" menu=\"gam\" value=\"" . $id_array[$j] . "\" class=\"acc_dis\" onclick=\"Set_Select_Value(this);\">" . $name_array[$j] . "</a></li>\n";
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
			<label for="rad0" class="container">HTML/SVG
				<input id="rad0" type="radio" name="fmtradio" value="0" checked="checked"/>
				<span class="checkmark"></span>
			</label>
			<label for="rad1" class="container">SVG only
				<input id="rad1" type="radio" name="fmtradio" value="1"/>
				<span class="checkmark"></span>
			</label>
			<label for="rad2" class="container">MediaWiki
				<input id="rad2" type="radio" name="fmtradio" value="2"/>
				<span class="checkmark"></span>
			</label>
			<label for="rad3" class="container">Editor
				<input id="rad3" type="radio" name="fmtradio" value="3"/>
				<span class="checkmark"></span>
			</label>
			<label for="rad4" class="container"><s>PDF</s> [TBD]
				<input id="rad4" type="radio" name="fmtradio" value="4" disabled="disabled"/>
				<span class="checkmark"></span>
			</label>
		</div>
		<div class="acc_div">
			<div id="but_check" class="acc_check">&#x2714;</div>
			<div id="but_xmark" class="acc_xmark">&#x2718;</div>
			<h2>5. Create the Diagram:</h2>
			<input id="butspawn" type="button" value="Create New Diagram" onclick="Check_Values_and_Spawn()"/>
			<p id="butready">All set! Now click the &quot;Create New Diagram&quot; button, above. This will spawn a new browser window with the keyboard diagram.</p>
			<p id="buterror">Try selecting a keyboard, theme, game and format, and create the diagram again!</p>
		</div>
	</div>
</form>
<hr/>
<h2>Description:</h2>
<p>This PHP form generates a keyboard control diagram in a new window. You can select from among hotkeys and bindings for various video games and other software. If you do not have a JavaScript-enabled browser, then you may refer to the <a href="keyboard-list.php">master list</a> table instead, as it should be viewable in a JavaScript-less browser. If you are looking for Apple or non-English bindings, you may <i>also</i> have an easier time searching the master list, as there are so few of them.</p>
<h2>Instructions:</h2>
<ol>
	<li>Select a keyboard (key positions).</li>
	<li>Select a theme (visual formatting).</li>
	<li>Select a game (key bindings).</li>
	<li>Select a format (output media type).</li>
	<li>Click on the 'Create New Diagram' button. A new window with your selected diagram will appear.</li>
	<li>View or print the page in the new window.</li>
</ol>
<p>The vast majority of the bindings are for the <i>US 104 Key (ANSI)</i> keyboard at this time. If you would like to see more bindings for the other keyboards, you are welcome to contribute! (More on that, below.)</p>
<h2>Licenses &amp; Submissions:</h2>
<p>The source code for this project is licensed under the <a rel="license" target="_blank" href="https://www.gnu.org/licenses/lgpl-3.0.en.html">GNU LGPLv3</a>. The content is licensed under the <a rel="license" target="_blank" href="http://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a>. Visit the <a href="https://github.com/mjhorvath/vgkd">GitHub repository</a> for the project's source code. The <a href="keyboard-log.php">change log</a> contains the project's update history and credits, as well as links to further reading. The <a href="https://github.com/mjhorvath/Video-Game-Keyboard-Diagrams/blob/master/TODOLIST.md">"to do" list</a> outlines some of the tasks I've planned for the future. (Completed tasks are marked with a plus '+' and incomplete tasks are marked with a minus '-'.)</p>
<p>To submit a new set of bindings, you can fill out <a href="<?php echo $path_root; ?>files/vgkd_binding_template_20180629.xlsx">this spreadsheet</a> and <a href="http://isometricland.net/email.php">email</a> me the contents (copy and paste) when you are done. Note that any content you submit falls under the <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a> license, as per the project as a whole. Your name will then appear at the bottom of each chart.</p>
<p>I have also recently started developing a form-based submission page. You can use it to submit changes to existing bindings by selecting the "Editor" option in Step 4, above. Or, you can get started making a brand new set of bindings with the "Blank Starter" game in the "Reference" category. There exist "Blank Starters" for every keyboard, though I personally still prefer using the spreadsheet for this purpose.</p>
<h2>MediaWiki, SVG &amp; PDF:</h2>
<p>I have created templates for MediaWiki that do basically the same thing as the other charts available on this site. You can find the templates as well as instructions on how to use them at <a target="_blank" href="http://strategywiki.org/wiki/Template:Kbdchart">StrategyWiki</a> and <a target="_blank" href="http://templates.wikia.com/wiki/Template:Kbdchart">Wikia</a>. By selecting the "MediaWiki" format type, you can generate the code you will need to fill the template with data and display a keyboard diagram on a MediaWiki wiki. On the destination wiki page, you may also want to wrap the chart in a scrollable DIV element, since the chart is wider than a typical browser window.</p>
<p>I have also created SVG versions of the charts, which you can also select in the "Formats" menu above. I have not migrated over to using SVG images exclusively yet, because they are less compatible with older browsers, and I have not figured out how I want to implement the mouse and joystick controls listings, yet. (I have not yet figured out how to create containers that expand, wrap and scale automatically as the volume of text inside increases.)</p>
<p>PDF versions of the charts will hopefully be added at some point in the future.</p>
<a name="print_tips"></a>
<h2>Printing Tips:</h2>
<ol>
	<li>When printing, most likely the chart will not fit within a single letter- or legal-sized page, even when selecting 'Landscape' mode instead of 'Portrait' mode in your printer settings. Luckily, your browser or printer may have a 'shrink to fit' feature that you can take advantage of to automatically adjust the size of the printed page output. Unfortunately, Google Chrome is missing a 'shrink to fit' feature by default, so I recommend investigating one of the workarounds discussed on Super User, <a target="_blank" href="https://superuser.com/questions/979741/how-can-i-make-chrome-shrink-to-fit-on-printing">here</a>.</li>
	<li>Remember also to enable printing of background colors and images. This setting is found in 'Page Setup' (Mozilla Firefox and Internet Explorer) or within the print dialog itself (Google Chrome). Sadly, this option does not exist at all in Microsoft Edge. I recommend using a different browser.</li>
	<li>If the colors or keyboard theme are not to your liking, select a different "Theme" from among the options at the top of this page, then try generating the chart again.</li>
	<li>Printing at 90dpi (dots-per-inch) and 100% scaling should result in a printed page that closely approximates the size and dimensions of many real physical keyboards. Of course, not every keyboard is exactly the same, even when adhering to some of the standard dimensions, so YMMV.</li>
	<li>On Windows, some Web browers (Google Chrome for instance) use your desktop DPI scaling settings to adjust the size of on-screen HTML elements, resulting in a document that can appear larger or smaller than normal. I'm not 100% sure this affects the printed page output, however.</li>
	<li>Note, that the darker themes will use up a lot of ink if you print them.</li>
</ol>
<?php print($page_bot); ?>
