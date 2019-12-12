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

	$path_file		= "./keyboard-links.php";	// this file
	$path_root1		= "../";		// for HTML and JS files
	$path_lib1		= "./lib/";		// for HTML and JS files
	$path_java1		= "../java/";		// for HTML and JS files
	$path_ssi1		= "../ssi/";		// for HTML and JS files
	$path_root2		= "../../";		// for PHP files
	$path_lib2		= "./";			// for PHP files
	$path_java2		= "../../java/";	// for PHP files
	$path_ssi2		= "../../ssi/";		// for PHP files

	include($path_ssi1	. "analyticstracking.php");
	include($path_lib1	. "scripts-all.php");

	echo
"<!DOCTYPE HTML>
<html lang=\"en\">
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
		<title>VGKD - Links</title>
		<link rel=\"canonical\" href=\"http://isometricland.net/keyboard/keyboard-links.php\"/>
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
			<h2>VGKD - Links</h2>
		</header>
		<main>\n";
?>
<p>On this page I will keep a list of online discussions that are useful to this project.</p>
<h3>Discussion</h3>
<ul>
	<li>If you’re a fan of the same 150 games I am... (<a target="_blank" href="https://forum.quartertothree.com/t/if-youre-a-fan-of-the-same-150-games-i-am/127577">link</a>)</li>
	<li>Consequences of adding more URL query parameters? (<a target="_blank" href="https://webmasters.stackexchange.com/questions/126064/consequences-of-adding-more-url-query-parameters">link</a>)</li>
	<li>MySQL/PHP data sort order (<a target="_blank" href="https://stackoverflow.com/questions/59078964/mysql-php-data-sort-order">link</a>)</li>
	<li>How to map many old URLs to a new host with a redirect under Apache cPanel? (<a target="_blank" href="https://webmasters.stackexchange.com/questions/126332/how-to-map-many-old-urls-to-a-new-host-with-a-redirect-under-apache-cpanel">link</a>)</li>
</ul>
<h3>Further Reading</h3>
<ul>
	<li>ShortcutMapper (<a target="_blank" href="http://waldobronchart.github.io/ShortcutMapper/">link</a>)</li>
	<li>Keyboard Layout Editor (<a target="_blank" href="http://www.keyboard-layout-editor.com/">link</a>)</li>
	<li>KeyXL Keyboard Shortcuts (<a target="_blank" href="http://www.keyxl.com/">link</a>)</li>
	<li>AllHotkeys.com (<a target="_blank" href="http://allhotkeys.com/">link</a>)</li>
	<li>replacementdocs (<a target="_blank" href="http://www.replacementdocs.com/">link</a>)</li>
	<li>Character sets (<a target="_blank" href="http://www.alanwood.net/demos/wgl4.html">link</a>)</li>
	<li>REALDev - The Keyboard (<a target="_blank" href="http://classicteck.com/rbarticles/mackeyboard.php">link</a>)</li>
	<li>Key Support, Keyboard Scan Codes, and Windows (<a href="http://www.microsoft.com/whdc/archive/scancode.mspx">link</a>)</li>
	<li>Virtual-Key Codes (<a target="_blank" href="http://msdn.microsoft.com/en-us/library/ms645540.aspx">link</a>)</li>
	<li>Generate a Heatmap of your Keystrokes (<a target="_blank" href="http://www.blendedtechnologies.com/visualization-tricks-generate-a-heatmap-of-your-keystrokes/">link</a>)</li>
</ul>
<?php
	echo
"		</main>
		<footer>\n";
	include($path_lib1 . "footer-common.php");
	echo
"		</footer>
	</body>
</html>\n";
?>
