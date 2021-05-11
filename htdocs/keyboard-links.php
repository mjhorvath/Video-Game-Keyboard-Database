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

	$page_title		= "Links";
	$path_vgkd		= "https://isometricland.net/keyboard/";
	$path_file		= "keyboard-links.php";	// this file
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
		<title>VGKD - " . $page_title . "</title>
		<link rel=\"canonical\" href=\"" . $path_vgkd . $path_file . "\"/>
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_lib1 . "favicon.png\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style-normalize.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style-common.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style-header.css\"/>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\"\"/>
		<meta name=\"keywords\" content=\"\"/>\n";
	echo writeAnalyticsTracking();
	echo
"	</head>
	<body>
		<nav>\n";
	include($path_lib1 . "header-navbar.php");	// not working in Android Chrome
	echo
"		</nav>
		<main>
			<h2>" . $page_title . "</h2>\n";
?>
<p>On this page I will keep a list of links that are useful to this project.</p>
<h4>Project pages</h4>
<ul>
	<li>GitHub repository (<a target="_blank" href="https://github.com/mjhorvath/vgkd">link</a>)</li>
</ul>
<h4>Discussion</h4>
<ul>
	<li>&quot;If you’re a fan of the same [0,&infin;) games I am...&quot; (<a target="_blank" href="https://forum.quartertothree.com/t/if-youre-a-fan-of-the-same-150-games-i-am/127577">link</a>)</li>
</ul>
<h4>Further reading</h4>
<ul>
	<li>&quot;ShortcutMapper&quot; (<a target="_blank" href="http://waldobronchart.github.io/ShortcutMapper/">link</a>)</li>
	<li>&quot;Keyboard Layout Editor&quot; (<a target="_blank" href="http://www.keyboard-layout-editor.com/">link</a>)</li>
	<li>&quot;KeyXL Keyboard Shortcuts&quot; (<a target="_blank" href="http://www.keyxl.com/">link</a>)</li>
	<li>AllHotkeys.com (<a target="_blank" href="http://allhotkeys.com/">link</a>)</li>
	<li>replacementdocs.com (<a target="_blank" href="http://www.replacementdocs.com/">link</a>)</li>
	<li>&quot;Character sets&quot; (<a target="_blank" href="http://www.alanwood.net/demos/wgl4.html">link</a>)</li>
	<li>&quot;REALDev - The Keyboard&quot; (<a target="_blank" href="http://classicteck.com/rbarticles/mackeyboard.php">link</a>)</li>
	<li>&quot;Key Support, Keyboard Scan Codes, and Windows&quot; (<a href="http://www.microsoft.com/whdc/archive/scancode.mspx">link</a>)</li>
	<li>&quot;Virtual-Key Codes&quot; (<a target="_blank" href="http://msdn.microsoft.com/en-us/library/ms645540.aspx">link</a>)</li>
	<li>&quot;Generate a Heatmap of your Keystrokes&quot; (<a target="_blank" href="http://www.blendedtechnologies.com/visualization-tricks-generate-a-heatmap-of-your-keystrokes/">link</a>)</li>
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
