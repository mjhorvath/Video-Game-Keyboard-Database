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

	$page_title		= "Sitemap Code";
	$path_vgkb		= "http://isometricland.net/keyboard/";
	$path_file		= "keyboard-sitemap.php";	// this file
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
	include($path_lib1	. "queries-list.php");

	$game_table		= [];
	$layout_table		= [];
	$record_table		= [];
	$platform_table		= [];

	// MySQL connection
	$con = mysqli_connect($con_website, $con_username, $con_password, $con_database);
 	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}
	mysqli_query($con, "SET NAMES 'utf8'");

	// MySQL queries
	selGamesList();
	selLayoutsList();
	selGamesRecordsList();
	selPlatformsList();

	mysqli_close($con);

	echo
"<!DOCTYPE HTML>
<html>
	<head>
		<script src=\"" . $path_lib1 . "java-common.js\"></script>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style-common.css\"/>
		<title>VGKD - " . $page_title . "</title>
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_lib1 . "favicon.png\"/>
	</head>
	<body>
		<header>\n";
	include($path_lib1 . "page-header.php");	// not working in Android Chrome
	echo
"			<h2>" . $page_title . "</h2>
			<p>This form generates code that can be copied and pasted into the project site's &quot;sitemap.xml&quot; file.</p>
		</header>
		<main>
			<textarea id=\"site_out\" readonly=\"readonly\" wrap=\"off\" style=\"width:100%;height:40em;font-family:monospace;\">\n";

	foreach ($game_table as $i => $game_value)
	{
//		$game_genre_game = $game_value[0];
		$game_id_gam = $game_value[1];
		$game_name_gam = $game_value[2];
		$game_seo_gam = $game_value[3];
		$platform_layout_table = [];

		foreach ($platform_table as $j => $platform_value)
		{
			$platform_id_pla = $platform_value[0];
			$platform_layout_table[$platform_id_pla-1] = [];
		}

		foreach ($record_table as $j => $record_value)
		{
			$game_id_rec = $record_value[1];
			$layout_id_rec = $record_value[2];
			$platform_id_rec = getPlatformID($layout_id_rec);

			if ($game_id_rec == $game_id_gam)
			{
				$platform_layout_table[$platform_id_rec-1][] = $layout_id_rec;
			}
		}

		foreach ($platform_table as $j => $platform_value)
		{
			$platform_id_pla = $platform_value[0];
			$this_layout_table = $platform_layout_table[$platform_id_pla-1];
			foreach ($this_layout_table as $k => $this_layout_value)
			{
				// maybe not a good idea to list one page for every game/layout combination, so let's only list layout #1 for the time being
				if ($this_layout_value == 1)
					echo cleantextHTML(str_replace("&","&amp;amp;","<url><loc>http://isometricland.net/keyboard/keyboard-diagram-" . $game_seo_gam . ".php?sty=15&lay=" . $this_layout_value . "&fmt=0&ten=1</loc><changefreq>yearly</changefreq></url>")) . "\n";
			}
		}
	}

	echo
"			</textarea>
			<button onclick=\"select_and_copy_textarea();\">Select All &amp; Copy</button>
<!--
			<button onclick=\"clear_textarea();\">Clear</button>
			<button onclick=\"\" disabled=\"disabled\">Reset</button>
-->
		</main>
		<footer>\n";
	include($path_lib1 . "footer-normal.php");
	echo
"		</footer>
	</body>
</html>\n";
?>
