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

	$path_root		= "../";

	header("Content-Type: text/javascript; charset=utf8");

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
	selGamesRecordsList();selPlatformsList();


	mysqli_close($con);

	echo
"var DATA_TABLE =
[\n";

	// using 'count()' here may be a bad idea in case there ever appear gaps in the table indexes due to deletions
	$game_name_count = count($game_name_array);
	for ($i = 0; $i < $game_name_count; $i++)
	{
		$game_id_gam	= cleantextJS($game_index_array[$i]);
		$game_seo_gam	= cleantextJS($game_seourl_array[$i]);
		$game_name_gam	= cleantextJS($game_name_array[$i]);
//		$game_genre_gam	= cleantextJS($game_genre_array[$i]);
		$game_genre_gam	= cleantextJS($genre_array[$game_genre_array[$i]-1]);

		$platform_layout_array = [];
		for ($j = 0; $j < count($platform_array); $j++)
		{
			$platform_id_pla = $platform_array[$j][0];
			$platform_layout_array[$platform_id_pla] = [];
		}

		echo
"	[\"" . $game_name_gam . "\",\"" . $game_genre_gam . "\",\"" . $game_id_gam . "\",\"";

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
			$this_platform_count = count($this_platform);
			if ($this_platform_count > 0)
			{
				echo $platform_abbv_pla . ": <span style=\\\"font-size:smaller;\\\">";

				for ($k = 0; $k < $this_platform_count; $k++)
				{
					$this_layout = $this_platform[$k];
					if ($k > 0)
					{
						echo ", ";
					}
					echo "<a target=\\\"_blank\\\" href=\\\"./keyboard-diagram-" . $game_seo_gam . ".php?sty=15&lay=" . $this_layout . "&fmt=0\\\">" . getLayoutName($this_layout) . "</a>";
				}
				echo "</span><br/>";
			}
		}

		echo
			"\"]";
		if ($i < $game_name_count - 1)
		{
			echo ",";
		}
		echo
			"\n";
	}
	echo
"]\n";
?>

var COLUMNS_NUMBER = 4;

var HEADER_TABLE =
[
	'Name		<span id=\"arrw_u0\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d0\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n0\" class=\"arrw_n\">&#9674;</span>',
	'Genre		<span id=\"arrw_u1\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d1\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n1\" class=\"arrw_n\">&#9674;</span>',
	'#ID		<span id=\"arrw_u2\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d2\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n2\" class=\"arrw_n\">&#9674;</span>',
	'Record(s)	<span id=\"arrw_u3\" class=\"arrw_u\">&#9650;</span><span id=\"arrw_d3\" class=\"arrw_d\">&#9660;</span><span id=\"arrw_n3\" class=\"arrw_n\">&#9674;</span>'
]

function sortDataTable(arrayColumns, asc)
{
	for (var i = 0, n = COLUMNS_NUMBER; i < n; i++)
	{
		var thisColumn = arrayColumns[i];
		DATA_TABLE = DATA_TABLE.sort(function(a,b)
		{
			if (asc)
			{
				return (stripHTML(a[thisColumn].toLowerCase()) > stripHTML(b[thisColumn].toLowerCase())) ? 1 : -1;
			}
			else
			{
				return (stripHTML(a[thisColumn].toLowerCase()) < stripHTML(b[thisColumn].toLowerCase())) ? 1 : -1;
			}
		});
	}
}

function updateHTMLTable()
{
	// update innerHTML / textContent according to DATA_TABLE
	// Note: textContent for firefox, innerHTML for others
	var table = document.getElementById('tableToSort');
	while (table.firstChild)
	{
		table.removeChild(table.firstChild);
	}
	// indent
		var headrow = document.createElement('tr');
		for (var j = 0, m = COLUMNS_NUMBER; j < m; j++)
		{
			var thishtml = HEADER_TABLE[j];
			var headcell = document.createElement('th');
			headcell.innerHTML = thishtml;
			headrow.appendChild(headcell);
		}
		table.appendChild(headrow);
	// outdent
	for (var i = 0, n = DATA_TABLE.length; i < n; i++)
	{
		var datarowdata = DATA_TABLE[i];
		var datarow = document.createElement('tr');
		for (var j = 0, m = COLUMNS_NUMBER; j < m; j++)
		{
			var thishtml = datarowdata[j];
			var datacell = document.createElement('td');
			datacell.innerHTML = thishtml;
			datarow.appendChild(datacell);
		}
		table.appendChild(datarow);
	}
}

function Toggle_Waiting(thisBool)
{
	document.getElementById('waiting').style.display = thisBool ? 'block' : 'none';
}

function Wait_and_Sort(colNum)
{
	var myArrayColumns = [0,1,2,3];
	sortTable(colNum);
//	sortDataTable(myArrayColumns, true);
//	updateHTMLTable();
}


function stripHTML(html)
{
	var doc = new DOMParser().parseFromString(html, 'text/html');
	return doc.body.textContent || '';
}
