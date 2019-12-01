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

	$path_file		= "./keyboard-list.php";	// this file
	$path_root1		= "../";		// for HTML and JS files
	$path_lib1		= "./lib/";		// for HTML and JS files
	$path_java1		= "../java/";		// for HTML and JS files
	$path_ssi1		= "../ssi/";		// for HTML and JS files
	$path_root2		= "../../";		// for PHP files
	$path_lib2		= "./";			// for PHP files
	$path_java2		= "../../java/";	// for PHP files
	$path_ssi2		= "../../ssi/";		// for PHP files

	include($path_ssi1	. "analyticstracking.php");
	include($path_ssi1	. "keyboard-connection.php");
	include($path_lib1	. "keyboard-common.php");
	include($path_lib1	. "keyboard-queries.php");

	$genre_array		= [];
	$layout_array		= [];
	$record_array		= [];
	$platform_array		= [];
	$game_name_array	= [];
	$game_index_array	= [];
	$game_seourl_array	= [];
	$game_genre_array	= [];

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
		<title>VGKD - Master Table</title>
		<link rel=\"canonical\" href=\"http://isometricland.net/keyboard/keyboard-list.php\"/>
		<link rel=\"icon\" type=\"image/png\" href=\"" . $path_lib1 . "favicon.png\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_root1 . "style_normalize.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style_common.css\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $path_lib1  . "style_masterlist.css\"/>
		<script src=\"" . $path_lib1  . "keyboard-list.js\"></script>
		<script src=\"" . $path_java1 . "sort_table.js\"></script>
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
		<meta name=\"description\" content=\"\"/>
		<meta name=\"keywords\" content=\"\"/>\n";
	echo writeAnalyticsTracking();
	echo
"	</head>
	<body onload=\"sortTableInit();Toggle_Waiting(false);\">
		<header>
			<h2>VGKD - Master Table</h2>
			<p>You can sort the table by clicking on the icons in the table headers.</p>
		</header>
		<main>
<img id=\"waiting\" src=\"./lib/animated_loading_icon.webp\" alt=\"loading\" style=\"position:fixed;display:block;z-index:10;width:100px;height:100px;left:50%;top:50%;margin-top:-50px;margin-left:-50px;\"/>
<table id=\"tableToSort\" class=\"kbd_tab\">
	<tr>
		<th onclick=\"Wait_and_Sort(0);\">Name		<span id=\"arrw_u0\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d0\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n0\" class=\"arrw_n\">&#9674;</span></th>
		<th onclick=\"Wait_and_Sort(1);\">Genre		<span id=\"arrw_u1\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d1\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n1\" class=\"arrw_n\">&#9674;</span></th>
		<th onclick=\"Wait_and_Sort(2);\">#ID		<span id=\"arrw_u2\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d2\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n2\" class=\"arrw_n\">&#9674;</span></th>
		<th onclick=\"Wait_and_Sort(3);\">Records	<span id=\"arrw_u3\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d3\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n3\" class=\"arrw_n\">&#9674;</span></th>
	</tr>\n";

	// using 'count()' here may be a bad idea in case there ever appear gaps in the table indexes due to deletions
	for ($i = 0; $i < count($game_name_array); $i++)
	{
		$game_id_gam = $game_index_array[$i];
		$game_seo_gam = $game_seourl_array[$i];
		$game_name_gam = $game_name_array[$i];
//		$game_genre_gam = $game_genre_array[$i];
		$game_genre_gam = $genre_array[$game_genre_array[$i]-1];

		$platform_layout_array = [];
		for ($j = 0; $j < count($platform_array); $j++)
		{
			$platform_id_pla = $platform_array[$j][0];
			$platform_layout_array[$platform_id_pla] = [[],[]];
		}

		echo
"	<tr><td>" . cleantextHTML($game_name_gam) . "</td><td>" . cleantextHTML($game_genre_gam) . "</td><td>" . $game_id_gam . "</td><td><dl>";

		for ($j = 0; $j < count($record_array); $j++)
		{
			$game_id_rec = $record_array[$j][0];
			$layout_id_rec = $record_array[$j][1];
			$layout_name = getLayoutName($layout_id_rec);
			$platform_id_rec = getPlatformID($layout_id_rec);

			if ($game_id_rec == $game_id_gam)
			{
				$platform_layout_array[$platform_id_rec][0][] = $layout_id_rec;
				$platform_layout_array[$platform_id_rec][1][] = $layout_name;
			}
		}

		for ($j = 0; $j < count($platform_array); $j++)
		{
			$platform_id_pla = $platform_array[$j][0];
			$platform_abbv_pla = $platform_array[$j][2];
			$these_layout_ids = $platform_layout_array[$platform_id_pla][0];
			$these_layout_names = $platform_layout_array[$platform_id_pla][1];
			array_multisort($these_layout_names, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $these_layout_ids);
			if (count($these_layout_ids) > 0)
			{
				echo "<dt>" . $platform_abbv_pla . "</dt>";

				for ($k = 0; $k < count($these_layout_ids); $k++)
				{
					$this_layout_id = $these_layout_ids[$k];
					$this_layout_name = $these_layout_names[$k];
					echo "<dd><a target=\"_blank\" href=\"./keyboard-diagram-" . $game_seo_gam . ".php?sty=15&lay=" . $this_layout_id . "&fmt=0&ten=1\">" . cleantextHTML($this_layout_name) . "</a></dd>";
				}
			}
		}

		echo "</dl></td></tr>\n";
	}

	echo
"</table>
		</main>
		<footer>";
	include($path_lib1 . "keyboard-footer-2.php");
	echo
"		</footer>
	</body>
</html>\n";
?>
