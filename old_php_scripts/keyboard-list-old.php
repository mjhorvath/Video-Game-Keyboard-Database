<?php
	header("Content-Type: text/html; charset=utf8");
	$path_root = "../";
	$page_title = "Video Game Keyboard Diagrams - Master List";
	$page_onload	= "sortTableInit();Toggle_Waiting(false);";
	$analytics	= true;
	$is_short	= true;
	$foot_array	= array("copyright","license_kbd");
	$java_array	= ["keyboard-list-js.php",$path_root . "java/sort_table.js"];
	$stys_array	= ["style_alphalist.css"];
	include($path_root . "ssi/normalpage.php");
	echo $page_top;
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

	$genre_array		= [];
	$layout_array		= [];
	$record_array		= [];
	$platform_array		= [];
	$game_name_array	= [];
	$game_index_array	= [];
	$game_seourl_array	= [];
	$game_genre_array	= [];

	$con = mysqli_connect($con_website,$con_username,$con_password,$con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error('Database connection failed: '  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");


	selGenresList();
	selGamesList();
	selLayoutsList();
	selGamesRecordsList();
	selPlatformsList();


	mysqli_close($con);

	echo
"<img id=\"waiting\" src=\"animated_loading_icon.webp\" alt=\"loading\" style=\"position:fixed;display:block;z-index:10;width:100px;height:100px;left:50%;top:50%;margin-top:-50px;margin-left:-50px;\"/>
<table id=\"tableToSort\" cellspacing=\"0\" cellpadding=\"0\" class=\"kbd_tab\">
	<tr>
		<th onclick=\"Wait_and_Sort(0);\">Name					<span id=\"arrw_u0\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d0\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n0\" class=\"arrw_n\">&#9674;</span></th>
		<th onclick=\"Wait_and_Sort(1);;\" style=\"width:6em;\">Genre		<span id=\"arrw_u1\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d1\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n1\" class=\"arrw_n\">&#9674;</span></th>
		<th onclick=\"Wait_and_Sort(2);\" style=\"width:4em;\">#ID		<span id=\"arrw_u2\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d2\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n2\" class=\"arrw_n\">&#9674;</span></th>
		<th onclick=\"Wait_and_Sort(3);\">Record(s)				<span id=\"arrw_u3\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d3\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n3\" class=\"arrw_n\">&#9674;</span></th>
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
			$platform_layout_array[$platform_id_pla] = [];
		}

		echo
"	<tr><td>" . $game_name_gam . "</td><td>" . $game_genre_gam . "</td><td>" . $game_id_gam . "</td><td>";

		for ($j = 0; $j < count($record_array); $j++)
		{
			$game_id_rec = $record_array[$j][0];
			$layout_id_rec = $record_array[$j][1];
			$platform_id_rec = getPlatformID($layout_id_rec);

			if ($game_id_rec == $game_id_gam)
			{
				$platform_layout_array[$platform_id_rec][] = $layout_id_rec;
			}
		}

		$platform_iterate = 0;
		for ($j = 0; $j < count($platform_array); $j++)
		{
			$platform_id_pla = $platform_array[$j][0];
			$platform_abbv_pla = $platform_array[$j][2];
			$this_platform = $platform_layout_array[$platform_id_pla];
			if (count($this_platform) > 0)
			{
				if ($platform_iterate > 0)
				{
					echo "<br/>";
				}

				echo $platform_abbv_pla . ": <span style=\"font-size:smaller;\">";

				for ($k = 0; $k < count($this_platform); $k++)
				{
					$this_layout = $this_platform[$k];
					if ($k > 0)
					{
						echo ", ";
					}
					echo "<a target=\"_blank\" href=\"./keyboard-diagram-" . $game_seo_gam . ".php?sty=15&lay=" . $this_layout . "&fmt=0\">" . getLayoutName($this_layout) . "</a>";
				}

				echo "</span>";

				$platform_iterate++;
			}
		}

		echo "</td></tr>\n";
	}

	echo
"</table>\n";
?>
<?php echo $page_bot; ?>
