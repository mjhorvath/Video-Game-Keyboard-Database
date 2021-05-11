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
	if (binding_data.format_id == 1)
		var out_href = "keyboard-diagram-" + binding_data.game_seo + ".svg?sty=" + binding_data.style_id + "&lay=" + binding_data.layout_id + "&fmt=" + binding_data.format_id + "&ten=" + binding_data.tenk_flag + "&vrt=" + binding_data.vert_flag + "&cap=" + binding_data.kcap_flag;
	else
		var out_href = "keyboard-diagram-" + binding_data.game_seo + ".php?sty=" + binding_data.style_id + "&lay=" + binding_data.layout_id + "&fmt=" + binding_data.format_id + "&ten=" + binding_data.tenk_flag + "&vrt=" + binding_data.vert_flag + "&cap=" + binding_data.kcap_flag;
	window.location.href = out_href;
}

function init_footer()
{
	var stylsel = document.VisualStyleSwitch.styl;
	stylsel.addEventListener
	('change',
		function()
		{
			binding_data.style_id = parseInt(this.value);
		}
	);
	var formrad = document.VisualStyleSwitch.form;
	for (var i = 0, n = formrad.length; i < n; i++)
	{
		formrad[i].addEventListener
		('change',
			function()
			{
				binding_data.format_id = parseInt(this.value);
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
				binding_data.tenk_flag = parseInt(this.value);
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
				binding_data.vert_flag = parseInt(this.value);
			}
		);
	}
	var kcaprad = document.VisualStyleSwitch.kcap;
	for (var i = 0, n = kcaprad.length; i < n; i++)
	{
		kcaprad[i].addEventListener
		('change',
			function()
			{
				binding_data.kcap_flag = parseInt(this.value);
			}
		);
	}
}
