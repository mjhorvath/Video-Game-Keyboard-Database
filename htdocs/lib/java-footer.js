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

function reload_this_page()
{
	if (format_id == 1)
		var out_href = "keyboard-diagram-" + game_seo + ".svg?sty=" + style_id + "&lay=" + layout_id + "&fmt=" + format_id + "&ten=" + ten_bool + "&vrt=" + vert_bool;
	else
		var out_href = "keyboard-diagram-" + game_seo + ".php?sty=" + style_id + "&lay=" + layout_id + "&fmt=" + format_id + "&ten=" + ten_bool + "&vrt=" + vert_bool;
	window.location.href = out_href;
}

function init_footer()
{
	var stylsel = document.VisualStyleSwitch.styl;
	stylsel.addEventListener
	('change',
		function()
		{
			style_id = parseInt(this.value);
		}
	);
	var formrad = document.VisualStyleSwitch.form;
	for (var i = 0, n = formrad.length; i < n; i++)
	{
		formrad[i].addEventListener
		('change',
			function()
			{
				format_id = parseInt(this.value);
			}
		);
	}
	var tkeyrad = document.VisualStyleSwitch.tkey;
	for (var i = 0, n = tkeyrad.length; i < n; i++)
	{
		tkeyrad[i].addEventListener
		('change',
			function()
			{
				ten_bool = parseInt(this.value);
			}
		);
	}
	var vertrad = document.VisualStyleSwitch.vert;
	for (var i = 0, n = vertrad.length; i < n; i++)
	{
		vertrad[i].addEventListener
		('change',
			function()
			{
				vert_bool = parseInt(this.value);
			}
		);
	}
}
