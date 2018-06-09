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

	function callProcedure0(&$connection, $procname, $dofunction)
	{
		mysqli_multi_query($connection, "CALL " . $procname . "();");
		$first_result = 1;
		do
		{
			$query_result = mysqli_store_result($connection);
			if ($query_result)
			{
				if ($first_result)
				{
					call_user_func($dofunction, $query_result);
					$first_result = 0;
				}
				mysqli_free_result($query_result);
			}
//			else
//			{
//			    printf("Error: %s<br/>", mysqli_error($connection));
//			}
			$query_result = NULL;
		} while(mysqli_next_result($connection));
	}
	function callProcedure1($connection, $procname, $dofunction, $in_param1)
	{
		mysqli_multi_query($connection, "CALL " . $procname . "(" . $in_param1 . ");");
		$first_result = 1;
		do
		{
			$query_result = mysqli_store_result($connection);
			if ($query_result)
			{
				if ($first_result)
				{
					call_user_func($dofunction, $query_result);
					$first_result = 0;
				}
				mysqli_free_result($query_result);
			}
//			else
//			{
//			    printf("Error: %s<br/>", mysqli_error($connection));
//			}
			$query_result = NULL;
		} while(mysqli_next_result($connection));
	}
	function callProcedure2($connection, $procname, $dofunction, $in_param1, $in_param2)
	{
		mysqli_multi_query($connection, "CALL " . $procname . "(" . $in_param1 . "," . $in_param2 . ");");
		$first_result = 1;
		do
		{
			$query_result = mysqli_store_result($connection);
			if ($query_result)
			{
				if ($first_result)
				{
					call_user_func($dofunction, $query_result);
					$first_result = 0;
				}
				mysqli_free_result($query_result);
			}
//			else
//			{
//			    printf("Error: %s<br/>", mysqli_error($connection));
//			}
			$query_result = NULL;
		} while(mysqli_next_result($connection));
	}
	function callProcedure1Txt($connection, $procname, $dofunction, $in_param1)
	{
		mysqli_multi_query($connection, "CALL " . $procname . "('" . $in_param1 . "');");
		$first_result = 1;
		do
		{
			$query_result = mysqli_store_result($connection);
			if ($query_result)
			{
				if ($first_result)
				{
					call_user_func($dofunction, $query_result);
					$first_result = 0;
				}
				mysqli_free_result($query_result);
			}
//			else
//			{
//			    printf("Error: %s<br/>", mysqli_error($connection));
//			}
			$query_result = NULL;
		} while(mysqli_next_result($connection));
	}
	// legacy code - do not delete
	function seo_url($input)
	{
		$input = mb_convert_case($input, MB_CASE_LOWER, "UTF-8");	//convert to lowercase
		$input = str_replace(array("'", "\""), "", $input);		//remove single and double quotes
		$input = preg_replace("/[^a-zA-Z0-9]+/", "-", $input);		//replace everything non-alphanumeric with dashes
		$input = preg_replace("/\-+/", "-", $input);			//replace multiple dashes with one dash
		$input = trim($input, "-");					//trim dashes from the beginning and end of the string if any
		return $input;
	}
	function getPlatformID($in_layout)
	{
		global $layout_array;
		for ($i = 0; $i < count($layout_array); $i++)
		{
			$layout_id = $layout_array[$i][0];
			$platform_id = $layout_array[$i][2];
			if ($in_layout == $layout_id)
			{
				return $platform_id;
			}
		}
	}
	function getLayoutName($in_layout)
	{
		global $layout_array;
		for ($i = 0; $i < count($layout_array); $i++)
		{
			$layout_id = $layout_array[$i][0];
			$layout_name = $layout_array[$i][1];
			if ($in_layout == $layout_id)
			{
				return $layout_name;
			}
		}
	}
	function print_key_html($in_id, $in_class, $in_color, $in_value, $in_clean_value)
	{
		global $write_maximal_keys;
		if ($write_maximal_keys == true)
		{
			if ($in_color == null)
			{
				echo
"						<div id=\"" . $in_id . "\" class=\"" . $in_class . "\" value=\"" . $in_value . "\">" . $in_clean_value . "</div>\n";
			}
			else
			{
				echo
"						<div id=\"" . $in_id . "\" class=\"" . $in_class . "\" color=\"" . $in_color . "\" value=\"" . $in_value . "\">" . $in_clean_value . "</div>\n";
			}
		}
		else
		{
			echo
"						<div id=\"" . $in_id . "\" class=\"" . $in_class . "\">" . $in_clean_value . "</div>\n";
		}
	}
	function cleantextHTML($instring)
	{
		return str_replace("\\t","\t",str_replace("\\r","",str_replace("\\n","<br/>",str_replace("\r","",str_replace("\n","",str_replace("<","&lt;",str_replace(">","&gt;",str_replace("&","&amp;",$instring))))))));
	}
	function cleantextSVG($instring)
	{
		return str_replace("\\t","\t",str_replace("\\r","",str_replace("\\n","\n",str_replace("\r","",str_replace("\n","",str_replace("<","&lt;",str_replace(">","&gt;",str_replace("&","&amp;",$instring))))))));
	}
	function cleantextcode($instring)
	{
		return str_replace("\\t","\t",str_replace("\\n","&lt;br/&gt;",str_replace("&","&amp;",$instring)));
	}
	function splittext($instring)
	{
		return array_filter(explode("\n", $instring));
	}
	function getcolor($group)
	{
		// hardcoded! should fetch from database instead
		$color_array = ["red","yel","grn","cyn","blu","mag","wht","gry","blk","org","olv","brn"];
		return array_key_exists($group-1, $color_array) ? $color_array[$group-1] : "non";
	}
	function getkeyclass($group)
	{
		// hardcoded! should fetch from database instead
		$class_array = ["cssA","cssB","cssC","cssD","cssE","cssF","cssG","cssH","cssI","cssJ","cssK","cssL"];
		return array_key_exists($group-1, $class_array) ? $class_array[$group-1] : "";

	}
	function getAuthorName($in_author_id)
	{
		global $author_table;
		for ($i = 0; $i < count($author_table); $i++)
		{
			$author_id = $author_table[$i][0];
			$author_name = $author_table[$i][1];
			if ($author_id == $in_author_id)
			{
				return $author_name;
			}
		}
	}
	function leadingZeros3($innumber)
	{
		$outstring = strval($innumber);
		if ($innumber < 10)
		{
			$outstring = "0" . $outstring;
		}
		if ($innumber < 100)
		{
			$outstring = "0" . $outstring;
		}
		return $outstring;
	}
	function leadingZeros2($innumber)
	{
		$outstring = strval($innumber);
		if ($innumber < 10)
		{
			$outstring = "0" . $outstring;
		}
		return $outstring;
	}
	function checkStyle($in_style_id)
	{
		global $style_table;
		for ($i = 0; $i < count($style_table); $i++)
		{
			if ($style_table[$i][0] == $in_style_id)
			{
				return true;
			}
		}
		return false;
	}
	function checkLayout($in_layout_id)
	{
	}

	$word_part1 = ["keyboard","video game","software","printable","graphical","visual"];
	$word_part2 = ["shortcut","binding","control","hotkey","command"];
	$word_part3 = ["diagram","chart","overlay","database","reference","guide","list","map"];
?>
