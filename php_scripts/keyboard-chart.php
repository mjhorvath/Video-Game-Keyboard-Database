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

	$svg_bool = array_key_exists("svg", $_GET) ? intval(ltrim($_GET["svg"], "0")) : 0;

	// validity checks
	if ($svg_bool == 1)
	{
//		header("Location: embed-" . $game_seo . ".php?sty=" . $style_id . "&lay=" . $layout_id . "&svg=" . $svg_bool);
		include("./keyboard-embed.php");
	}
	else
	{
//		header("Location: html-" . $game_seo . ".php?sty=" . $style_id . "&lay=" . $layout_id . "&svg=" . $svg_bool);
		include("./keyboard-html.php");
	}
?>
