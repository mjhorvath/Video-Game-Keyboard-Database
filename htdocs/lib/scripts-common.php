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

	$vgkd_url		= "https://isometricland.net/keyboard/";
	$php_url		= "";		// set by checkURLParameters()
	$svg_url		= "";		// set by checkURLParameters()
	$can_url		= "";		// set by checkURLParameters()
	$default_game_id	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_style_id	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_layout_id	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_format_id	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_svgb_flag	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_tenk_flag	= 1;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_vert_flag	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_kcap_flag	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_platform_id	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_stylegroup_id	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_genre_id	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_language_id	= 0;		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_game_seo	= "";		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_game_name	= "";		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_style_name	= "";		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_layout_name	= "";		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_format_name	= "";		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_platform_name	= "";		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_stylegroup_name	= "";		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_genre_name	= "";		// set by selDefaultsAll(), utilized by checkURLParameters()
	$default_language_code	= "";		// set by selDefaultsAll(), utilized by checkURLParameters()
	$binding_color_table	= [];
	$binding_class_table	= [];
	$errors_table		= [];		// populated by checkForErrors()
	$urlqueries_table	= [];		// populated by selURLQueriesAll(), utilized by selDefaultsAll()
	$language_code		= "";		// set by selThisLanguageStringsChart()
	$language_title		= "";		// set by selThisLanguageStringsChart()
	$language_description	= "";		// set by selThisLanguageStringsChart()
	$language_keywords	= "";		// set by selThisLanguageStringsChart()
	$language_legend	= "";		// set by selThisLanguageStringsChart()
	$temp_game_seo		= "";		// set by checkForErrors(), utilized by pageTitle()
	$temp_game_name		= "";		// set by checkForErrors(), utilized by pageTitle()
	$temp_layout_name	= "";		// set by checkForErrors(), utilized by pageTitle()
	$temp_style_name	= "";		// set by checkForErrors(), utilized by pageTitle()
	$temp_platform_name	= "";		// set by checkForErrors(), utilized by pageTitle()
	$temp_format_name	= "";		// set by checkForErrors(), utilized by pageTitle()
	$temp_separator		= "";		// set by checkForErrors(), utilized by pageTitle()
	$page_title_a		= "";		// set by pageTitle()
	$page_title_b		= "";		// set by pageTitle()
	$layout_language	= 1;		// temporary until more text strings get localized

	// from https://www.php.net/manual/en/function.pathinfo.php
	function extension($path)
	{
		$qpos = strpos($path, "?");
		if ($qpos!==false) $path = substr($path, 0, $qpos);
		$extension = pathinfo($path, PATHINFO_EXTENSION);
		return $extension;
	}
	// what is the ampersand in front of $connection for?
	function selectQuery(&$connection, $query_string, $dofunction)
	{
		$first_result = true;
		if (mysqli_multi_query($connection, $query_string))
		{
			do
			{
				$query_result = mysqli_store_result($connection);
				if ($query_result)
				{
					if ($first_result)
					{
						call_user_func($dofunction, $query_result);
						$first_result = false;
					}
					mysqli_free_result($query_result);
				}
				else
				{
					printf("Error: %s<br/>", mysqli_error($connection));
				}
				$query_result = null;
			} while(mysqli_more_results($connection) && mysqli_next_result($connection));
		}
	}
	function getURLParameters()
	{
		global	$gamesrecord_id,
			$game_seo,
			$game_id,
			$style_id,
			$layout_id,
			$format_id,
			$svgb_flag,
			$tenk_flag,
			$vert_flag,
			$kcap_flag;

		$gamesrecord_id	= array_key_exists("grid", $_GET) ? intval(ltrim($_GET["grid"], "0"))	: null;	// should override other parameters
		$game_seo	= array_key_exists("seo", $_GET) ? $_GET["seo"]				: null;	// generated by htaccess if missing
		$game_id	= array_key_exists("gam", $_GET) ? intval(ltrim($_GET["gam"], "0"))	: null;
		$layout_id	= array_key_exists("lay", $_GET) ? intval(ltrim($_GET["lay"], "0"))	: null;
		$style_id	= array_key_exists("sty", $_GET) ? intval(ltrim($_GET["sty"], "0"))	: null;
		$format_id	= array_key_exists("fmt", $_GET) ? intval(ltrim($_GET["fmt"], "0"))	: null;
		$svgb_flag	= array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0"))	: null;	// obsolete parameter kept so old links don't break
		$tenk_flag	= array_key_exists("ten", $_GET) ? intval(ltrim($_GET["ten"], "0"))	: null;
		$vert_flag	= array_key_exists("vrt", $_GET) ? intval(ltrim($_GET["vrt"], "0"))	: null;
		$kcap_flag	= array_key_exists("cap", $_GET) ? intval(ltrim($_GET["cap"], "0"))	: null;
	}
	function checkURLParameters()
	{
		global	$vgkd_url, $php_url, $svg_url, $can_url, $url_ext,
			$game_seo,	$default_game_seo,
			$game_id,	$default_game_id,
			$game_name,	$default_game_name,
			$style_id,	$default_style_id,
			$style_name,	$default_style_name,
			$layout_id,	$default_layout_id,
			$layout_name,	$default_layout_name,
			$format_id,	$default_format_id,
			$format_name,	$default_format_name,
			$svgb_flag,	$default_svgb_flag,
			$tenk_flag,	$default_tenk_flag,
			$vert_flag,	$default_vert_flag,
			$kcap_flag,	$default_kcap_flag,
			$gamesrecord_id;

		$fix_url	= false;

		if ($gamesrecord_id !== null)
		{
			$game_seo = null;
			$game_id = null;
			$layout_id = null;
			selThisGameLayoutChart();		// move this to top of file if possible
			$fix_url = true;
		}
		if ($game_id === null)
		{
			//error_log("game_id is null", 0);
			if ($game_seo !== null)
			{
				selThisGameSEOChart();
			}
			else
			{
				//error_log("game_seo is null", 0);
				$game_seo	= $default_game_seo;
				$game_id	= $default_game_id;
				$game_name	= $default_game_name;
			}
//			$fix_url = true;	// don't uncomment this!
		}
		if ($game_seo === null)
		{
			//error_log("game_seo is null", 0);
			if ($game_id !== null)
			{
				selThisGamesIDChart();
			}
			else
			{
				//error_log("game_id is null", 0);
				$game_seo	= $default_game_seo;
				$game_id	= $default_game_id;
				$game_name	= $default_game_name;
			}
			$fix_url = true;
		}
		if ($layout_id === null)
		{
			//error_log("layout_id is null", 0);
			$layout_id	= $default_layout_id;
			$layout_name	= $default_layout_name;
			$fix_url = true;
		}
		if ($style_id === null)
		{
			//error_log("style_id is null", 0);
			$style_id	= $default_style_id;
			$style_name	= $default_style_name;
			$fix_url = true;
		}
		if ($url_ext == "svg")
		{
			if ($format_id != 1)
			{
				$fix_url = true;
			}
		}
		if ($format_id == 1)
		{
			if ($url_ext != "svg")
			{
				$fix_url = true;
			}
		}
		elseif ($format_id === null)
		{
			//error_log("format_id is null", 0);
			if ($url_ext == "svg")
			{
				$format_id = 1;
			}
			else if ($svgb_flag !== null)
			{
				$format_id = $svgb_flag;
			}
			else
			{
				//error_log("svgb_flag is null", 0);
				$format_id = $default_format_id;
			}
			$fix_url = true;
		}
		if ($svgb_flag === null)
		{
			//error_log("svgb_flag is null", 0);
			$svgb_flag = $default_svgb_flag;
//			$fix_url = true;	// don't uncomment this!
		}
		if ($tenk_flag === null)
		{
			//error_log("tenk_flag is null", 0);
			$tenk_flag = $default_tenk_flag;
			$fix_url = true;
		}
		if ($vert_flag === null)
		{
			//error_log("vert_flag is null", 0);
			$vert_flag = $default_vert_flag;
			$fix_url = true;
		}
		if ($kcap_flag === null)
		{
			//error_log("kcap_flag is null", 0);
			$kcap_flag = $default_kcap_flag;
			$fix_url = true;
		}

		$php_url = "keyboard-diagram-" . $game_seo . ".php?sty=" . $style_id . "&lay=" . $layout_id . "&fmt=" . $format_id . "&ten=" . $tenk_flag . "&vrt=" . $vert_flag . "&cap=" . $kcap_flag;
		$svg_url = "keyboard-diagram-" . $game_seo . ".svg?sty=" . $style_id . "&lay=" . $layout_id . "&fmt=" . 1          . "&ten=" . $tenk_flag . "&vrt=" . $vert_flag . "&cap=" . $kcap_flag;
		$can_url = $vgkd_url . $php_url;

		if ($fix_url === true)
		{
			if ($format_id == 1)
				header("Location: " . $svg_url);
			else
				header("Location: " . $php_url);
			die();
		}
	}
	function pageTitle()
	{
		global	$page_title_a, $page_title_b, $language_title, $gamesrecord_id,
			$temp_separator, $temp_game_name, $temp_platform_name, $temp_layout_name, $temp_style_name, $temp_format_name;
		$temp_separator	= " - ";
		$page_title_a	= $temp_game_name;
		$page_title_b	= $language_title . $temp_separator . $temp_platform_name . $temp_separator . $temp_layout_name . $temp_separator . $temp_style_name . $temp_separator . $temp_format_name . $temp_separator . "GRID:" . $gamesrecord_id;
	}
	// there is an analogous function written in JavaScript in "java-submit.js"
	// need to keep the two functions synced
	function seo_url($input)
	{
		$input = mb_convert_case($input, MB_CASE_LOWER, "UTF-8");	//convert to lowercase
		$input = str_replace(array("'", "\""), "", $input);		//remove single and double quotes
		$input = preg_replace("/[^a-zA-Z0-9]+/", "-", $input);		//replace everything non-alphanumeric with dashes
		$input = preg_replace("/\-+/", "-", $input);			//replace multiple dashes with one dash
		$input = trim($input, "-");					//trim dashes from the beginning and end of the string if any
		return $input;
	}
	function getPlatformID($in_layout_id)
	{
		global $layout_table;
		foreach ($layout_table as $i => $layout_value)
		{
			// note these are local variables
			$layout_id   = $layout_value[0];
			$platform_id = $layout_value[2];
			if ($layout_id == $in_layout_id)
			{
				return $platform_id;
			}
		}
	}
	function getLayoutName($in_layout_id)
	{
		global $layout_table;
		foreach ($layout_table as $i => $layout_value)
		{
			// note these are local variables
			$layout_id	= $layout_value[0];
			$layout_name	= $layout_value[1];
			if ($layout_id == $in_layout_id)
			{
				return $layout_name;
			}
		}
	}
	function getGenreName($in_genre_id)
	{
		global $genre_table;
		foreach ($genre_table as $i => $genre_value)
		{
			// note these are local variables
			$genre_id	= $genre_value[0];
			$genre_name	= $genre_value[1];
			if ($genre_id == $in_genre_id)
			{
				return $genre_name;
			}
		}
	}
	function getStyleName($in_style_id)
	{
		global $style_table;
		for ($i = 0; $i < count($style_table); $i++)
		{
			$style_box = $style_table[$i];
			for ($j = 0; $j < count($style_box); $j++)
			{
				// note these are local variables
				$style_id = $style_box[$j][0];
				$style_name = $style_box[$j][1];
				if ($style_id == $in_style_id)
				{
					return $style_name;
				}
			}
		}
	}
	function getGameName($in_game_id)
	{
	}
	function getAuthorName($in_author_id)
	{
		global $author_table;
		for ($i = 0; $i < count($author_table); $i++)
		{
			// note these are local variables
			$author_id = $author_table[$i][0];
			$author_name = $author_table[$i][1];
			if ($author_id == $in_author_id)
			{
				return $author_name;
			}
		}
	}
	// cleaning!
	// this function is for "output-submit-html.php", need to create a version of this function for "output-svg.php" as well
	function printKeyHTML($in_id, $in_class, $in_color, $in_value)
	{
		echo
"								<div id=\"" . $in_id . "\" class=\"" . $in_class . "\">" . cleantextHTML($in_value) . "</div>\n";
	}
	function cleantextHTML($instring)
	{
		return str_replace("\\t","\t",str_replace("\\r","",str_replace("\\n","<br/>",str_replace("\r","",str_replace("\n","",htmlentities($instring,ENT_QUOTES|ENT_HTML401,"UTF-8",false))))));
	}
	function cleantextSVG($instring)
	{
		return str_replace("\\t","\t",str_replace("\\r","",str_replace("\\n","\n",str_replace("\r","",str_replace("\n","",htmlentities($instring,ENT_QUOTES|ENT_XML1,"UTF-8",false))))));
	}
	function cleantextJS($instring)
	{
		return str_replace("'","\\'",str_replace("\\","\\\\",$instring));
	}
	function cleantextWiki($instring)
	{
		return str_replace("\\t","\t",str_replace("\\n","&lt;br/&gt;",$instring));
	}
	function splitkeytext($instring)
	{
		return explode("\n", $instring);
	}
	// non!
	// order!
	function getkeycolor($group)
	{
		global $binding_color_table;
		foreach ($binding_color_table as $i => $binding_color_value)
		{
			// keygroup_id, keygroup_class, sortorder_id
			if ($binding_color_value[0] == $group)
			{
				return $binding_color_value[1];
				break;
			}
		}
		return "non";
	}
	// non! actually not needed here, but anyway...
	function getkeyclass($group)
	{
		global $binding_class_table;
		foreach ($binding_class_table as $i => $binding_class_value)
		{
			if ($binding_class_value[0] == $group)
			{
				return $binding_class_value[1];
				break;
			}
		}
		return "";
	}
	function leadingZeros($innumber, $level)
	{
		return str_pad($innumber, $level, "0", STR_PAD_LEFT);
	}
	function getFileTime()
	{
		global $path_lib2, $path_file;
		$in_file = $path_lib2 . $path_file;
		$path_parts = pathinfo($in_file);
		$base_name = $path_parts["basename"];
		date_default_timezone_set("UTC");
		if (file_exists($in_file))
		{
			return "Page: " . $base_name . ". Last modified: " . date("Y-m-d H:i:s T.", filemtime($in_file));
		}
		else
		{
			return "Page: " . $base_name . ". Last modified: File does not exist.";
		}
	}
	function checkForErrors()
	{
		global	$errors_table, $gamesrecord_id, $stylesrecord_id,
			$game_seo,	$temp_game_seo,
			$game_name,	$temp_game_name,	$game_id,
			$platform_name,	$temp_platform_name,	$platform_id,
			$layout_name,	$temp_layout_name,	$layout_id,
			$style_name,	$temp_style_name,	$style_id,
			$format_name,	$temp_format_name,	$format_id;
		$temp_game_seo		= $game_seo		? $game_seo		: "unrecognized-game";		// needs to be low caps
		$temp_game_name		= $game_name		? $game_name		: "Unrecognized Game";
		$temp_platform_name	= $platform_name	? $platform_name	: "Unrecognized Platform";
		$temp_layout_name	= $layout_name		? $layout_name		: "Unrecognized Layout";
		$temp_style_name	= $style_name		? $style_name		: "Unrecognized Style";
		$temp_format_name	= $format_name		? $format_name		: "Unrecognized Format";
		// checking for $game_id or $game_name isn't working right now
		// should check for $format_id and $format_name too
		// should check that $tenk_flag is in the correct range too
		// should check that $vert_flag is in the correct range too
		if (!$game_name)
		{
			$errors_table[] = "Game with ID number " . $game_id . " not found.";
		}
		if (!$layout_name)
		{
			$errors_table[] = "Layout with ID number " . $layout_id . " not found.";
		}
		if (!$style_name)
		{
			$errors_table[] = "Style with ID number " . $style_id . " not found.";
		}
		if (!$gamesrecord_id)
		{
			$errors_table[] = "No bindings found for game \"" . $temp_game_name . "\" and layout \"" . $temp_layout_name . "\".";
		}
		if (!$stylesrecord_id)
		{
			$errors_table[] = "No configurations found for style \"" . $temp_style_name . "\" and layout \"" . $temp_layout_name . "\".";
		}
	}
	function usortByMember1($a, $b)
	{
		return strnatcmp($a[1], $b[1]);
	}
	function usortByMember2($a, $b)
	{
		return strnatcmp($a[2], $b[2]);
	}
	function test_dummy()
	{
		global $this_is_a_test;
		$this_is_a_test = "test in progress";
	}
	function test_path_info()
	{
		global $path_lib2, $path_file;
//		$path_parts = pathinfo($_SERVER["REQUEST_URI"]);
//		$path_parts = pathinfo(__FILE__);
		$path_parts = pathinfo($path_lib2 . $path_file);
		echo $path_parts["dirname"],	"<br>";
		echo $path_parts["basename"],	"<br>";
		echo $path_parts["extension"],	"<br>";
		echo $path_parts["filename"],	"<br>";	// since PHP 5.2.0
	}
?>
