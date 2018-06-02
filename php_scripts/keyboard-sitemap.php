<?php
	header("Content-Type: text/html; charset=utf8");
	$path_root = "../";
	$page_title = "Video Game Keyboard Diagrams - Sitemap Code";
	$foot_array = ["copyright","license_kbd"];
	$analytics	= true;
	$is_short	= true;
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

	$con = mysqli_connect($con_website,$con_username,$con_password,$con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");

	function doGames($in_result)
	{
		global $game_name_array, $game_index_array, $game_seourl_array;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// genre_id, game_id, game_name, game_friendlyurl
			$game_index_array[] = $game_row[1];
			$game_name_array[] = $game_row[2];
			$game_seourl_array[] = $game_row[3];
		}
//		array_multisort($game_name_array, $game_index_array, $game_seourl_array);
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

	$layout_array		= [];
	$record_array		= [];
	$platform_array		= [];
	$game_name_array	= [];
	$game_index_array	= [];
	$game_seourl_array	= [];

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
"<h2>Sitemap Code</h2>
<textarea readonly=\"readonly\" wrap=\"off\" style=\"width:100%;height:30em;\">\n";

	// using 'count()' here may be a bad idea in case there ever appear gaps in the table indexes due to deletions
	for ($i = 0; $i < count($game_name_array); $i++)
	{
		$game_id_gam = $game_index_array[$i];
		$game_seo_gam = $game_seourl_array[$i];
		$game_name_gam = $game_name_array[$i];

		$platform_layout_array = [];
		for ($j = 0; $j < count($platform_array); $j++)
		{
			$platform_id_pla = $platform_array[$j][0];
			$platform_layout_array[$platform_id_pla] = [];
		}

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
				for ($k = 0; $k < count($this_platform); $k++)
				{
					$this_layout = $this_platform[$k];
					echo "&lt;url&gt;&lt;loc&gt;http://isometricland.net/keyboard/keyboard-diagram-" . $game_seo_gam . ".php?sty=15&amp;amp;lay=" . $this_layout . "&amp;amp;frm=0&lt;/loc&gt;&lt;lastmod&gt;2016-12-17&lt;/lastmod&gt;&lt;changefreq&gt;yearly&lt;/changefreq&gt;&lt;/url&gt;\n";
				}
			}
		}
	}

	echo
"</textarea>\n";
	echo $page_bot;
?>
