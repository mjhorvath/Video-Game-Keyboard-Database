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

	$page_title		= "Master Table";
	$path_vgkd		= "https://isometricland.net/keyboard/";
	$path_file		= "keyboard-list.php";	// this file
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

	$genre_table		= [];
	$layout_table		= [];
	$record_table		= [];
	$platform_table		= [];

	// open MySQL connection
	$con = mysqli_connect($con_website, $con_username, $con_password, $con_database);
 	if (mysqli_connect_errno())
	{
		trigger_error('Database connection failed: '  . mysqli_connect_error(), E_USER_ERROR);
	}
	mysqli_query($con, "SET NAMES 'utf8'");

	// MySQL queries
	selGenresList();
	selGamesList();
	selLayoutsList();
	selGamesRecordsList();
	selPlatformsList();

	// close MySQL connection
	mysqli_close($con);

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
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1 . "style-list.css\"/>
		<script src=\"" . $path_lib1 . "java-list.js\"></script>
		<script src=\"" . $path_lib1 . "sort_table.js\"></script>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\"\"/>
		<meta name=\"keywords\" content=\"\"/>\n";
	echo writeAnalyticsTracking();
	echo
"	</head>
	<body onload=\"sortTableInit();Toggle_Waiting(false);\">
		<nav>\n";
	include($path_lib1 . "header-navbar.php");	// not working in Android Chrome
	echo
"		</nav>
		<main>
			<h2>" . $page_title . "</h2>
			<p>You can sort the table by clicking on the arrow icons on the right sides of the table header cells.</p>
<img id=\"waiting\" src=\"./lib/animated-loading-icon.webp\" alt=\"loading\" style=\"position:fixed;display:block;z-index:10;width:100px;height:100px;left:50%;top:50%;margin-top:-50px;margin-left:-50px;\"/>
<table id=\"tableToSort\" class=\"kbd_tab\">
	<thead>
		<tr>
			<th onclick=\"Wait_and_Sort(0);\">Game&nbsp;Title<span id=\"arrw_u0\" class=\"arrw_u\">&#129081;</span><span id=\"arrw_d0\" class=\"arrw_d\">&#129083;</span><span id=\"arrw_n0\" class=\"arrw_n\">&#11021;</span></th>
			<th onclick=\"Wait_and_Sort(1);\">Genre<span id=\"arrw_u1\" class=\"arrw_u\">&#129081;</span><span id=\"arrw_d1\" class=\"arrw_d\">&#129083;</span><span id=\"arrw_n1\" class=\"arrw_n\">&#11021;</span></th>
			<th onclick=\"Wait_and_Sort(2);\" style=\"min-width:2.5em;\">ID<span id=\"arrw_u2\" class=\"arrw_u\">&#129081;</span><span id=\"arrw_d2\" class=\"arrw_d\">&#129083;</span><span id=\"arrw_n2\" class=\"arrw_n\">&#11021;</span></th>
			<th onclick=\"Wait_and_Sort(3);\">Records<span id=\"arrw_u3\" class=\"arrw_u\">&#129081;</span><span id=\"arrw_d3\" class=\"arrw_d\">&#129083;</span><span id=\"arrw_n3\" class=\"arrw_n\">&#11021;</span></th>
		</tr>
	</thead>
	<tbody>\n";
	foreach ($game_table as $i => $game_value)
	{
		$game_genre_gam	= getGenreName($game_value[0]);
		$game_id_gam	= $game_value[1];
		$game_name_gam	= $game_value[2];
		$game_seo_gam	= $game_value[3];
		$game_pad_gam	= leadingZeros($game_id_gam, 3);

		echo
"		<tr><td>" . cleantextHTML($game_name_gam) . "</td><td>" . cleantextHTML($game_genre_gam) . "</td><td>" . $game_pad_gam . "</td><td><dl>";

		$platform_layout_table = [];
		foreach ($platform_table as $j => $platform_value)
		{
			$platform_id_pla = $platform_value[0];
			$platform_layout_table[$platform_id_pla-1] = [];
		}

		foreach ($record_table as $j => $record_value)
		{
			$game_id_rec	= $record_value[1];
			$layout_id_rec	= $record_value[2];
			$layout_name_rec = getLayoutName($layout_id_rec);
			$platform_id_rec = getPlatformID($layout_id_rec);
			if ($game_id_rec == $game_id_gam)
			{
				$platform_layout_table[$platform_id_rec-1][$layout_id_rec-1] = [$layout_id_rec, $layout_name_rec];
			}
		}

		usort($platform_table, "usortByMember1");
		foreach ($platform_table as $j => $platform_value)
		{
			$platform_id_pla	= $platform_value[0];
			$platform_abbv_pla	= $platform_value[2];
			$platform_layouts_pla	= $platform_layout_table[$platform_id_pla-1];
			if (count($platform_layouts_pla) > 0)
			{
				echo "<dt>" . $platform_abbv_pla . "</dt>";
				usort($platform_layouts_pla, "usortByMember1");
				foreach ($platform_layouts_pla as $k => $this_layout)
				{
					$this_layout_id   = $this_layout[0];
					$this_layout_name = $this_layout[1];
					echo "<dd><a target=\"_blank\" href=\"./keyboard-diagram-" . $game_seo_gam . ".php?sty=15&lay=" . $this_layout_id . "&fmt=0&ten=1&vrt=0\">" . cleantextHTML($this_layout_name) . "</a></dd>";
				}
			}
		}

		echo "</dl></td></tr>\n";
	}
	echo
"	</tbody>
</table>
		</main>
		<footer>\n";
	include($path_lib1 . "footer-normal.php");
	echo
"		</footer>
	</body>
</html>\n";
?>
