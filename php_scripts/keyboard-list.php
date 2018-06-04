<?php
	header("Content-Type: text/html; charset=utf8");
	$path_root = "../";
	$page_title = "Video Game Keyboard Diagrams - Master List";
	$foot_array = ["copyright"];
	$page_onload	= "sortTableInit();";
	$analytics	= true;
	$is_short	= true;
	$foot_array	= array("copyright","license_kbd");
	$java_array	= [$path_root . "java/sort_table.js"];
	include($path_root . "ssi/normalpage.php");
	echo $page_top;
?>
<?php
	// Copyright (C) 2009  Michael Horvath

	// This library is free software; you can redistribute it and/or
	// modify it under the terms of the GNU Lesser General Public
	// License as published by the Free Software Foundation; either
	// version 2.1 of the License, or (at your option) any later version.

	// This library is distributed in the hope that it will be useful,
	// but WITHOUT ANY WARRANTY; without even the implied warranty of
	// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	// Lesser General Public License for more details.

	// You should have received a copy of the GNU Lesser General Public
	// License along with this library; if not, write to the Free Software
	// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 
	// 02110-1301  USA

	include("./keyboard-connection.php");
	include("./keyboard-common.php");

	function doGenres($in_result)
	{
		global $genre_array;
		while ($genre_row = mysqli_fetch_row($in_result))
		{
			// genre_name, genre_displayorder
			$genre_array[] = $genre_row[0];
		}
	}
	function doGames($in_result)
	{
		global $game_genre_array, $game_index_array, $game_name_array, $game_seourl_array;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// genre_id, game_id, game_name, game_friendlyurl
			$game_genre_array[] = $game_row[0];
			$game_index_array[] = str_pad($game_row[1], 3, '0', STR_PAD_LEFT);
			$game_name_array[] = $game_row[2];
			$game_seourl_array[] = $game_row[3];
		}
		array_multisort($game_name_array, $game_genre_array, $game_index_array, $game_seourl_array);
	}
	function doLayouts($in_result)
	{
		global $layout_array;
		while ($layout_row = mysqli_fetch_row($in_result))
		{
			// layout_id, layout_name, platform_id
			$layout_array[] = $layout_row;
		}
	}
	function doRecords($in_result)
	{
		global $record_array;
		while ($record_row = mysqli_fetch_row($in_result))
		{
			// game_id, layout_id
			$record_array[] = $record_row;
		}
	}
	function doPlatforms($in_result)
	{
		global $platform_array;
		while ($platform_row = mysqli_fetch_row($in_result))
		{
			// platform_id, platform_name, platform_abbv
			$platform_array[] = $platform_row;
		}
	}

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

//	error_log("get_genres_front");
	callProcedure0($con, "get_genres_front", "doGenres");
//	error_log("get_games_front");
	callProcedure0($con, "get_games_front", "doGames");
//	error_log("get_layouts_list");
	callProcedure0($con, "get_layouts_list", "doLayouts");
//	error_log("get_records_games_list");
	callProcedure0($con, "get_records_games_list", "doRecords");
//	error_log("get_platforms_list");
	callProcedure0($con, "get_platforms_list", "doPlatforms");

	mysqli_close($con);

	echo
"<table id=\"tableToSort\" cellspacing=\"0\" cellpadding=\"0\" class=\"kbd_tab\">
	<tr>
		<th onclick=\"sortTable(0)\">Name				<span id=\"arrw_u0\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d0\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n0\" class=\"arrw_n\">&#9674;</span></th>
		<th onclick=\"sortTable(1)\" style=\"width:6em;\">Genre		<span id=\"arrw_u1\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d1\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n1\" class=\"arrw_n\">&#9674;</span></th>
		<th onclick=\"sortTable(2)\" style=\"width:4em;\">#ID		<span id=\"arrw_u2\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d2\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n2\" class=\"arrw_n\">&#9674;</span></th>
		<th onclick=\"sortTable(3)\">Record(s)				<span id=\"arrw_u3\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d3\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n3\" class=\"arrw_n\">&#9674;</span></th>
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

		for ($j = 0; $j < count($platform_array); $j++)
		{
			$platform_id_pla = $platform_array[$j][0];
			$platform_abbv_pla = $platform_array[$j][2];
			$this_platform = $platform_layout_array[$platform_id_pla];
			if (count($this_platform) > 0)
			{
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
				echo "</span><br/>";
			}
		}

		echo
"	</td></tr>\n";
	}

	echo
"</table>\n";
?>
<?php echo $page_bot; ?>
