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

	$page_title		= "Video Game Keyboard Diagrams";
	$path_vgkb		= "http://isometricland.net/keyboard/";
	$path_file		= "./keyboard.php";	// this file
	$path_root1		= "../";		// for HTML and JS files
	$path_lib1		= "./lib/";		// for HTML and JS files
	$path_java1		= "../java/";		// for HTML and JS files
	$path_ssi1		= "../ssi/";		// for HTML and JS files
	$path_root2		= "../../";		// for PHP files
	$path_lib2		= "./";			// for PHP files
	$path_java2		= "../../java/";	// for PHP files
	$path_ssi2		= "../../ssi/";		// for PHP files

	include($path_ssi1	. "plugin-analyticstracking.php");
	include($path_ssi1	. "keyboard-connection.php");
	include($path_lib1	. "scripts-common.php");
	include($path_lib1	. "queries-common.php");
	include($path_lib1	. "queries-frontend.php");
	include($path_lib1	. "queries-js.php");

	// accordion lists
	$genre_table		= [];
	$genre_game_table	= [];
	$stylegroup_table	= [];
	$stylegroup_style_table	= [];
	$platform_table		= [];
	$platform_layout_table	= [];
	$platform_order_table	= [];

	// javascript output
	$seourl_table		= [];
	$seourl_string		= "";
	$layout_game_table	= [];
	$layout_game_string	= "";
	$layout_style_table	= [];
	$layout_style_string	= "";
	$game_table		= [];
	$game_string		= "";
	$style_table		= [];
	$style_string		= "";
	$layout_table		= [];
	$layout_string		= "";

	// open MySQL connection
	$con = mysqli_connect($con_website, $con_username, $con_password, $con_database);
 	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}
	mysqli_query($con, "SET NAMES 'utf8'");

	// MySQL queries
	selURLQueriesAll();		// gather and validate URL parameters
	selDefaultsAll();			// get default values for entities if missing
	selGenresFront();
	selGamesFront();
	selStylegroupsFront();
	selStylesFront();
	selPlatformsFront();
	selLayoutsFront();
	selThisGameAutoinc();
	selThisLayoutAutoinc();
	selThisStyleAutoinc();
	selSeoUrls();
	selGameRecords();
	selStyleRecords();

	// close MySQL connection
	mysqli_close($con);

	// SEO URLs
	$seourl_count = 0;
	$seourl_total = count($seourl_table);
	foreach ($seourl_table as $i => $seourl_value)
	{
		$seourl_string .= "\t" . $i . ":\t'" . $seourl_value . "'";
		if ($seourl_count < $seourl_total - 1)
		{
			$seourl_string .= ",";
		}
		$seourl_string .= "\n";
		$seourl_count += 1;
	}

	// games
	$game_count = 0;
	$game_total = count($game_table);
	foreach ($game_table as $i => $game_value)
	{
		$game_string .= $i . ":1";
		if ($game_count < $game_total - 1)
		{
			$game_string .= ",";
		}
		$game_count += 1;
	}

	// styles
	$style_count = 0;
	$style_total = count($style_table);
	foreach ($style_table as $i => $style_value)
	{
		$style_string .= $i . ":1";
		if ($style_count < $style_total - 1)
		{
			$style_string .= ",";
		}
		$style_count += 1;
	}

	// layouts and sub-tables
	$layout_count = 0;
	$layout_total = count($layout_table);
	foreach ($layout_table as $i => $layout_value)
	{
		$layout_string .= $i . ":" . $layout_value;
		$layout_style_string .= "\t" . $i . ":\t{";
		if (array_key_exists($i, $layout_style_table))
		{
			$style_inner_table = $layout_style_table[$i];
			$style_inner_count = 0;
			$style_inner_total = count($style_inner_table);
			ksort($style_inner_table);
			foreach ($style_inner_table as $j => $style_inner_value)
			{
				$layout_style_string .= $j . ":1";
				if ($style_inner_count < $style_inner_total - 1)
				{
					$layout_style_string .= ",";
				}
				$style_inner_count += 1;
			}
		}
		$layout_style_string .= "}";
		$layout_game_string .= "\t" . $i . ":\t{";
		if (array_key_exists($i, $layout_game_table))
		{
			$game_inner_table = $layout_game_table[$i];
			$game_inner_count = 0;
			$game_inner_total = count($game_inner_table);
			ksort($game_inner_table);
			foreach ($game_inner_table as $j => $game_inner_value)
			{
				$layout_game_string .= $j . ":1";
				if ($game_inner_count < $game_inner_total - 1)
				{
					$layout_game_string .= ",";
				}
				$game_inner_count += 1;
			}
		}
		$layout_game_string .= "}";
		if ($layout_count < $layout_total - 1)
		{
			$layout_string		.= ",";
			$layout_style_string	.= ",";
			$layout_game_string	.= ",";
		}
		$layout_style_string	.= "\n";
		$layout_game_string	.= "\n";
		$layout_count += 1;
	}

	echo
"<!DOCTYPE HTML>
<html lang=\"en\">
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
		<title>" . $page_title . "</title>
		<link rel=\"canonical\" href=\"http://isometricland.net/keyboard/keyboard.php\"/>
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_lib1 . "favicon.png\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_root1 . "style_normalize.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style-common.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style-accordion.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style-radio.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style-frontend.css\"/>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\"Visual keyboard hotkey & binding diagrams for video games and other software.\"/>
		<meta name=\"keywords\" content=\"visual,keyboard,keys,diagrams,charts,overlay,shortcuts,bindings,mapping,maps,controls,hotkeys,database,print,printable,video game,software,guide,reference\"/>
		<script src=\"" . $path_lib1  . "java-common.js\"></script>
		<script src=\"" . $path_lib1  . "java-frontend.js\"></script>
		<script src=\"" . $path_java1 . "jquery-1.4.2.min.js\"></script>
		<script src=\"" . $path_java1 . "jquery-accordionmenu.js\"></script>
		<script src=\"" . $path_lib1  . "java-cubesnake.js\"></script>\n";
	echo writeAnalyticsTracking();
	echo
"		<script>
var game_table = {" . $game_string . "};
var style_table = {" . $style_string . "};
var layout_table = {" . $layout_string . "};
var layout_game_table =
{
" . $layout_game_string .
"};
var layout_style_table =
{
" . $layout_style_string . 
"};
// could replace this with a string conversion script
// though such a script would still require the original titles as input
var seourl_table =
{
" . $seourl_string . 
"};
		</script>
	</head>
	<body onload=\"Select_Init();cube_snake_init();\">
		<header>\n";
	include($path_lib1 . "page-header.php");	// not working in Android Chrome
	echo
"			<h2>" . $page_title . "</h2>
		</header>
		<main>\n";
?>
<div id="snake_pane"></div>
<img id="waiting" src="<?php echo $path_lib1; ?>animated-loading-icon.webp" alt="loading" style="position:fixed;display:block;z-index:10;width:100px;height:100px;left:50%;top:50%;margin-top:-50px;margin-left:-50px;"/>
<form name="keyboard_select">
	<input type="hidden" name="lay" value=""/>
	<input type="hidden" name="gam" value=""/>
	<input type="hidden" name="sty" value=""/>
	<div>
		<div class="acc_div">
			<div class="acc_wrap">
				<h3 class="acc_head">1. Select a Keyboard:</h3>
				<div id="lay_check" class="acc_check">&#x2714;</div>
				<div id="lay_xmark" class="acc_xmark">&#x2718;</div>
			</div>
			<ul id="lay_menu" class="acc_mnu">
<?php
	foreach ($platform_table as $i => $platform_value)
	{
		echo
"				<li>\n";
		if ($i == ($default_platform_id-1))
		{
			echo
"					<a menu=\"lay\"><span class=\"arrw_a\" style=\"display:none;\">&#9658;</span><span class=\"arrw_b\" style=\"display:inline;\">&#9660;</span> " . $platform_value . "</a>
					<ul style=\"height:22em;display:block;\">\n";
		}
		else
		{
			echo
"					<a menu=\"lay\"><span class=\"arrw_a\" style=\"display:inline;\">&#9658;</span><span class=\"arrw_b\" style=\"display:none;\">&#9660;</span> " . $platform_value . "</a>
					<ul style=\"height:22em;display:none;\">\n";
		}
		$inner_array = $platform_layout_table[$i];
		usort($inner_array, "usortByMember2");
		foreach ($inner_array as $j => $inner_value)
		{
			$this_layout_id		= $inner_value[1];
			$this_layout_name	= $inner_value[2];
			if ($this_layout_id == $default_layout_id)
			{
				$this_layout_name .= " &#10022;";
			}
			echo
"						<li><a id=\"lay_" . ($this_layout_id-1) . "\" menu=\"lay\" value=\"" . $this_layout_id . "\" class=\"acc_nrm\" onclick=\"Set_Select_Value(this);Set_Game(" . ($this_layout_id-1) . ",this);Set_Style(" . ($this_layout_id-1) . ",this);\">" . $this_layout_name . "</a></li>\n";
		}
		echo
"					</ul>
				</li>\n";
	}
?>
			</ul>
		</div>
		<div class="acc_div">
			<div class="acc_wrap">
				<h3 class="acc_head">2. Select a Theme:</h3>
				<div id="sty_check" class="acc_check">&#x2714;</div>
				<div id="sty_xmark" class="acc_xmark">&#x2718;</div>
			</div>
			<ul id="sty_menu" class="acc_mnu">
<?php
	foreach ($stylegroup_table as $i => $stylegroup_value)
	{
		echo
"				<li>\n";
		if ($i == ($default_stylegroup_id-1))
		{
			echo
"					<a menu=\"sty\"><span class=\"arrw_a\" style=\"display:none;\">&#9658;</span><span class=\"arrw_b\" style=\"display:inline;\">&#9660;</span> " . $stylegroup_value . "</a>
					<ul style=\"height:22em;display:block;\">\n";
		}
		else
		{
			echo
"					<a menu=\"sty\"><span class=\"arrw_a\" style=\"display:inline;\">&#9658;</span><span class=\"arrw_b\" style=\"display:none;\">&#9660;</span> " . $stylegroup_value . "</a>
					<ul style=\"height:22em;display:none;\">\n";
		}
		$inner_array = $stylegroup_style_table[$i];
		usort($inner_array, "usortByMember2");
		foreach ($inner_array as $j => $inner_value)
		{
			$this_style_id		= $inner_value[1];
			$this_style_name	= $inner_value[2];
			if ($this_style_id == $default_style_id)
			{
				$this_style_name .= " &#10022;";
			}
			echo
"						<li><a id=\"sty_" . ($this_style_id-1) . "\" menu=\"sty\" value=\"" . $this_style_id . "\" class=\"acc_dis\" onclick=\"Set_Select_Value(this);\">" . $this_style_name . "</a></li>\n";
		}
		echo
"					</ul>
				</li>\n";
	}
?>
			</ul>
		</div>
		<div class="acc_div">
			<div class="acc_wrap">
				<h3 class="acc_head">3. Select a Game:</h3>
				<div id="gam_check" class="acc_check">&#x2714;</div>
				<div id="gam_xmark" class="acc_xmark">&#x2718;</div>
			</div>
			<ul id="gam_menu" class="acc_mnu">
<?php
	foreach ($genre_table as $i => $genre_value)
	{
		echo
"				<li>\n";
		if ($i == ($default_genre_id-1))
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
		$inner_array = $genre_game_table[$i];
		usort($inner_array, "usortByMember2");
		foreach ($inner_array as $j => $inner_value)
		{
			$this_game_id	= $inner_value[1];
			$this_game_name	= $inner_value[2];
			if ($this_game_id == $default_game_id)
			{
				$this_game_name .= " &#10022;";
			}
			echo
"						<li><a id=\"gam_" . ($this_game_id-1) . "\" menu=\"gam\" value=\"" . $this_game_id . "\" class=\"acc_dis\" onclick=\"Set_Select_Value(this);\">" . $this_game_name . "</a></li>\n";
		}
		echo
"					</ul>
				</li>\n";
	}
?>
			</ul>
		</div>
		<div class="acc_div">
			<div class="acc_wrap">
				<h3 class="acc_head">4. Select a Format:</h3>
				<div id="fmt_check" class="acc_check">&#x2714;</div>
				<div id="fmt_xmark" class="acc_xmark">&#x2718;</div>
			</div>
			<label class="container">HTML/SVG&nbsp;&#10022;
				<input id="fmtrad0" type="radio" name="fmt_radio" value="0" onchange="enableResetButton();" checked="checked"/>
				<span class="checkmark"></span>
			</label>
			<label class="container">SVG only
				<input id="fmtrad1" type="radio" name="fmt_radio" value="1" onchange="enableResetButton();"/>
				<span class="checkmark"></span>
			</label>
			<label class="container">MediaWiki
				<input id="fmtrad2" type="radio" name="fmt_radio" value="2" onchange="enableResetButton();"/>
				<span class="checkmark"></span>
			</label>
			<label class="container">Editor
				<input id="fmtrad3" type="radio" name="fmt_radio" value="3" onchange="enableResetButton();"/>
				<span class="checkmark"></span>
			</label>
			<label class="container"><s>PDF</s>
				<input id="fmtrad4" type="radio" name="fmt_radio" value="4" onchange="enableResetButton();" disabled="disabled"/>
				<span class="checkmark"></span>
			</label>
		</div>
		<div class="acc_div">
			<div class="acc_wrap">
				<h3 class="acc_head">5. Numeric keypad:</h3>
				<div id="ten_check" class="acc_check">&#x2714;</div>
				<div id="ten_xmark" class="acc_xmark">&#x2718;</div>
			</div>
			<label class="container">Show&nbsp;&#10022;
				<input id="tenrad1" type="radio" name="ten_radio" value="1" onchange="enableResetButton();" checked="checked"/>
				<span class="checkmark"></span>
			</label>
			<label class="container">Hide
				<input id="tenrad0" type="radio" name="ten_radio" value="0" onchange="enableResetButton();"/>
				<span class="checkmark"></span>
			</label>
		</div>
		<div class="acc_div">
			<div class="acc_wrap">
				<h3 class="acc_head">6. Create the Diagram:</h3>
				<div id="but_check" class="acc_check">&#x2714;</div>
				<div id="but_xmark" class="acc_xmark">&#x2718;</div>
			</div>
			<input id="but_spawn" type="button" value="Create New Diagram" onclick="Check_Values_and_Spawn();"/>
			<input id="but_reset" type="button" value="Reset" disabled="disabled" onclick="Reset_Page();"/>
			<p id="but_ready">All set! Now click the &quot;Create New Diagram&quot; button, above. This will spawn a new browser window containing the keyboard diagram.</p>
			<p id="but_error">Try again to select a keyboard, theme and game, then click the &quot;Create New Diagram&quot; button once more!</p>
		</div>
	</div>
</form>
<hr/>
<h3>Description:</h3>
<p>This PHP form generates a keyboard control diagram in a new window. You can select from among hotkeys and bindings for various video games and other software. If you do not have a JavaScript-enabled browser, then you may refer to the <a href="keyboard-list.php">master table</a> table instead. If you are looking for Apple-branded or non-English bindings, you may <i>also</i> benefit from searching the master table, since there are unfortunately so few of them.</p>
<h3>Instructions:</h3>
<ol>
	<li>Select a keyboard (key positions).</li>
	<li>Select a theme (visual formatting).</li>
	<li>Select a game (key bindings).</li>
	<li>Select a format (output media type).</li>
	<li>Toggle the numeric keypad on/off.</li>
	<li>Click on the &quot;Create New Diagram&quot; button. A new window with your selected diagram will appear.</li>
	<li>View or print the page in the new window.</li>
</ol>
<p>Items marked with a star (&#10022;) are the &quot;default&quot; or most common options.</p>
<p>The vast majority of the bindings are for the <i>US 104 Key (ANSI)</i> keyboard at this time. If you would like to see more bindings for the other keyboards, you are welcome to contribute! (More on that, below.)</p>
<h3>Licenses &amp; Submissions:</h3>
<p>The source code for this project is licensed under the <a rel="license" target="_blank" href="https://www.gnu.org/licenses/lgpl-3.0.en.html">GNU LGPLv3</a>. The content is licensed under the <a rel="license" target="_blank" href="http://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a>. Visit the <a target="_blank" href="https://github.com/mjhorvath/vgkd">GitHub repository</a> for the project's source code. The <a href="keyboard-log.php">change log</a> contains the project's update history and credits, as well as links to further reading. The <a href="https://github.com/mjhorvath/Video-Game-Keyboard-Diagrams/wiki/To-Do-List">&quot;to do&quot; list</a> outlines some of the tasks I've planned for the future.</p>
<p>To submit a new set of bindings or a layout, you can fill out <a target="_blank" href="https://github.com/mjhorvath/Video-Game-Keyboard-Diagrams/blob/master/scripts_sql/vgkd_bindings_template_insert_into.xlsm">this</a> and <a target="_blank" href="https://github.com/mjhorvath/Video-Game-Keyboard-Diagrams/blob/master/scripts_sql/vgkd_layouts_template_insert_into.xlsx">this</a> spreadsheet and <a target="_blank" href="http://isometricland.net/email/email.php">email</a> me the contents by copying and pasting the data into the email form. Note that any content you submit falls under the <a rel="license" target="_blank" href="http://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a> license, as per the project as a whole. Your name will then appear at the bottom of each chart you contributed to.</p>
<p>I have also developed a form-based submission page. You can use it by selecting the &quot;Editor&quot; option in Step 4, above. To update a game's binding simply select it from the &quot;Games&quot; menu above. To start a brand new submission, select the &quot;Blank Starter&quot; item in the &quot;Reference&quot; category of the &quot;Games&quot; menu. (<a href="keyboard-diagram-blank-starter.php?sty=15&lay=1&fmt=3&ten=1">Here is a direct link for the most common keyboard</a>.) There are &quot;Blank Starters&quot; for every keyboard. That being said, I personally still prefer using the spreadsheet for creating new bindings.</p>
<h3>MediaWiki, SVG &amp; PDF:</h3>
<p>I have created templates for MediaWiki wikis that do basically the same thing as the charts available on this site. You can find templates as well as instructions on how to use them at <a target="_blank" href="http://strategywiki.org/wiki/Template:Kbdchart">StrategyWiki</a> and <a target="_blank" href="http://templates.wikia.com/wiki/Template:Kbdchart">Fandom</a>. There is a test case <a target="_blank" href="https://templates.fandom.com/wiki/User:Mikali_Homeworld/Kbdchart_example">located here</a>. By selecting the &quot;MediaWiki&quot; format type in the menu above, you can generate the code you will need in order to populate this template with data. Note that on the destination wiki page, you may also want to wrap the charts in scrollable DIV elements, since the charts are typically wider than a browser window.</p>
<p>I have also created SVG versions of the charts that you can select in the &quot;Format&quot; menu on the front page. I have not migrated over to using SVG for everything yet since it is less compatible with older browsers. Further, I have not yet decided how I want to implement the mouse and joystick controls charts, and this will affect my future choices. (In particular, I have not yet figured out how to create text containers in SVG that expand, wrap and scale automatically as the number of characters inside them increases.)</p>
<p>PDF versions of the charts will hopefully be implemented at some point in the future. Right now the PDF option is still disabled in the menu. That being said, there are virtual printer drivers such as &quot;Microsoft Print to PDF&quot; or &quot;Adobe Print to PDF&quot; that will allow you to print any document to PDF, even from your Web browser.</p>
<a name="print_tips"></a>
<h3>Printing Tips:</h3>
<ol>
	<li>When printing, the chart may not fit within a single letter- or legal-sized page, even when selecting &quot;Landscape&quot; mode instead of &quot;Portrait&quot; mode in your printer dialog settings. Luckily, your browser or printer may have a &quot;shrink to fit&quot; feature that you can take advantage of to automatically adjust the size of the printed page output. Unfortunately, Google Chrome is missing a &quot;shrink to fit&quot; feature by default, so I recommend investigating one of the workarounds discussed on Super User, <a target="_blank" href="https://superuser.com/questions/979741/how-can-i-make-chrome-shrink-to-fit-on-printing">here</a>.</li>
	<li>You may save a little more space by also hiding the numeric keypad. Be aware that many games have commands bound to these keys!</li>
	<li>I recommend not enabling the printing of background colors and images in your printer settings, as this will consume a lot of ink. This setting is found in &quot;Page Setup&quot; (Mozilla Firefox and Internet Explorer) or within the print dialog itself (Google Chrome). The setting appears to not exist in Microsoft Edge.</li>
	<li>If the colors or keyboard theme are not to your liking, select a different &quot;Theme&quot; from among the options at the top of this page, then try generating the chart again.</li>
	<li>Printing at 96 DPI (dots-per-inch) and 100% scaling should result in a printed page that closely approximates the size and dimensions of many real physical keyboards. Of course, not every keyboard is exactly the same, so YMMV.</li>
	<li>In MS Windows, some Web browers (Google Chrome for instance) use your desktop DPI scaling settings to adjust the size of on-screen HTML elements, resulting in a document that appears larger or smaller than normal. I am not exactly sure what effect this has on the printed page output, however. It may vary from browser to browser. I am not sure what happens in other operating systems either.</li>
	<li>Note, that the darker themes in general will waste a lot of ink. You might want to select a lighter theme when printing.</li>
</ol>
<?php
	echo
"		</main>
		<footer>\n";
	include($path_lib1 . "footer-normal.php");
	echo
"		</footer>
	</body>
</html>\n";
?>
