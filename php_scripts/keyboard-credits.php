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

	$path_file		= "./keyboard-credits.php";	// this file
	$path_root1		= "../";		// for HTML and JS files
	$path_lib1		= "./lib/";		// for HTML and JS files
	$path_java1		= "../java/";		// for HTML and JS files
	$path_ssi1		= "../ssi/";		// for HTML and JS files
	$path_root2		= "../../";		// for PHP files
	$path_lib2		= "./";			// for PHP files
	$path_java2		= "../../java/";	// for PHP files
	$path_ssi2		= "../../ssi/";		// for PHP files

	include($path_ssi1	. "plugin-analyticstracking.php");
	include($path_lib1	. "scripts-common.php");

	echo
"<!DOCTYPE HTML>
<html lang=\"en\">
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
		<title>VGKD - Credits</title>
		<link rel=\"canonical\" href=\"http://isometricland.net/keyboard/keyboard-credits.php\"/>
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_lib1 . "favicon.png\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_root1 . "style_normalize.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style-common.css\"/>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\"\"/>
		<meta name=\"keywords\" content=\"\"/>\n";
	echo writeAnalyticsTracking();
	echo
"	</head>
	<body>
		<header>
			<h2>VGKD - Credits</h2>
		</header>
		<main>\n";
?>
<p>Note that if you have submitted any layouts, bindings or styles, then your name will also appear at the bottom of each relevant diagram.</p>
<h4>Binding schemes</h4>
<ul>
	<li>Agnes Beste</li>
	<li>Taz</li>
	<li>Josiah Stearns</li>
	<li>Cris</li>
	<li>Daniel</li>
	<li>etejam</li>
	<li>Luke Fanning</li>
	<li>Hunter Mansanas</li>
	<li>Lucas</li>
	<li>Abel Cloots</li>
</ul>
<h4>Visual themes</h4>
<ul>
	<li>Kozierok style from <i>Keyboard Key Groupings</i> by Charles M. Kozierok (<a target="_blank" href="http://209.68.14.80/ref/kb/group.htm">link</a>)</li>
	<li>Savard style from <i>Scan Codes Demystified</i> by John J. G. Savard (<a target="_blank" href="http://www.quadibloc.com/comp/scan.htm">link</a>)</li>
	<li>Hello Kitty style from <i>Hello Kitty Keyboard</i> by DreamKitty.com (<a target="_blank" href="http://www.dreamkitty.com/Merchant2/merchant.mv?Screen=PROD&Store_Code=DK2000&Product_Code=K-FB109141&Category_Code=HK">link</a>)</li>
	<li>Doraemon style from <i>Doraemon Keyboard</i> by DreamKitty.com (<a target="_blank" href="http://www.dreamkitty.com/Merchant5/merchant.mvc?Screen=PROD&Store_Code=DK2000&Product_Code=O-FB761011&Category_Code=">link</a>)</li>
	<li>FunKeyBoard style from <i>FunKeyBoard</i> by Chester Creek Technologies (<a target="_blank" href="http://www.venturaes.com/index_new.asp?http://www.venturaes.com/chestercreek/index.html">link</a>)</li>
</ul>
<h4>Scripts</h4>
<ul>
	<li>&quot;Simple JQuery Accordion Menu&quot; by Marco van Hylckama Vlieg (<a target="_blank" href="http://www.i-marco.nl/weblog/">link</a>)</li>
	<li>CSS checkbox code by W3Schools (<a target="_blank" href="https://www.w3schools.com/howto/howto_css_custom_checkbox.asp">link</a>)</li>
	<li>JavaScript table sorting routine by W3Schools (<a target="_blank" href="https://www.w3schools.com/howto/howto_js_sort_table.asp">link</a>)</li>
	<li>Recaptcha script from Stack Overflow (<a target="_blank" href="https://stackoverflow.com/questions/30006081/recaptcha-2-0-with-ajax">link</a>)</li>
	<li>&quot;normalize.css&quot; by Nicolas Gallagher (<a target="_blank" href="github.com/necolas/normalize.css">link</a>)</li>
	<li>&quot;jQuery JavaScript Library v1.4.2&quot; by John Resig (<a target="_blank" href="http://jquery.com/">link</a>)</li>
</ul>
<h4>Icon graphics</h4>
<ul>
	<li>&quot;Spreadsheet&quot; by Arthur Shlain (<a href="https://thenounproject.com/ArtZ91/collection/useful-icons-user-interface/?i=360259">link</a>)</li>
	<li>&quot;Keyboard&quot; by Alexey Ivanov (<a href="https://thenounproject.com/search/?q=keyboard&creator=17566&i=17427">link</a>)</li>
	<li>&quot;Information&quot; by AnsteyDesign (<a target="_blank" href="https://thenounproject.com/">link</a>)</li>
	<li>&quot;Edit&quot; by Garrett Knoll (<a target="_blank" href="https://thenounproject.com/">link</a>)</li>
	<li>&quot;Plus&quot; by P.J. Onori (<a target="_blank" href="https://thenounproject.com/">link</a>)</li>
	<li>&quot;Delete&quot; by P.J. Onori (<a target="_blank" href="https://thenounproject.com/">link</a>)</li>
	<li>&quot;CC BY-SA 3.0&quot; icon (<a target="_blank" href="https://commons.wikimedia.org/wiki/File:CC-BY-SA_icon_white.svg">link</a>)</li>
	<li>&quot;GNU LGPLv3&quot; icon (<a target="_blank" href="https://commons.wikimedia.org/wiki/File:License_icon-lgpl-88x31.png">link</a>)</li>
</ul>
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
