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

// To make min/max cube positions scale with screen dimensions, 
// need to change the code to subtract or add min and max to 1/2
// then divide by screen_mul. Not necessary if they stay at 
// their current values, though.

var start_idx = -100000;
var atom_dim = [97,56];
var units_dim = [atom_dim[0] / 8, atom_dim[1] / 8];
var sprites_dat =
[
	// pixel w, pixel h, file path, 3D dimensions, likelihood/odds
	[97, 112, './lib/isometric_sprite_cube_sml.png', [1,1,1], 0.5],
	[97, 112, './lib/isometric_sprite_pyrd_sml.png', [1,1,1], 0.6],
	[97, 112, './lib/isometric_sprite_sphr_med.png', [2,2,2], 0.8],
	[97, 112, './lib/isometric_sprite_cube_med.png', [2,2,2], 0.9],
	[97, 112, './lib/isometric_sprite_cube_lrg.png', [3,3,3], 1.0]
];

// need to double check if these parameters are still correct after so many years
if (window.innerWidth)
{
	var screen_wid = window.innerWidth;
	var screen_hgh = window.innerHeight;
}
else if (document.documentElement.clientWidth)
{
	var screen_wid = document.documentElement.clientWidth;
	var screen_hgh = document.documentElement.clientHeight;
}
else if (document.body.clientWidth)
{
	var screen_wid = document.body.clientWidth;
	var screen_hgh = document.body.clientHeight;
}

var screen_dim = [screen_wid - atom_dim[0], screen_hgh - atom_dim[1]];
var screen_mul = screen_wid * screen_hgh / 1024 / 768;

var grid_table = [];
var grid_padding = 8;
var grid_dim_x = Math.ceil(screen_wid / units_dim[0] / 2);
var grid_dim_y = Math.ceil(screen_hgh / units_dim[1] / 2);
var grid_size = grid_dim_x + grid_dim_y + grid_padding * 2;

var cube_pos = [];
var cube_num = 0;	// was 8
var cube_tot = Math.round(screen_mul) * cube_num;
/*
var cube_min_lft = 1/6;
var cube_max_lft = 2/6;
var cube_min_rgt = 5/6;
var cube_max_rgt = 6/6;

var cube_min_lft = 0/2;
var cube_max_lft = 1/2;
var cube_min_rgt = 1/2;
var cube_max_rgt = 2/2;
*/
var cube_min_lft = 0/4;
var cube_max_lft = 1/4;
var cube_min_rgt = 3/4;
var cube_max_rgt = 4/4;

var snake_seg = 0;
var snake_len = 10;
var snake_pos = [97,112];
var snake_idx = start_idx + snake_pos[1] / units_dim[1];
var snake_dir = 0;
var snake_cll = [];
var dir_table =
[
	[-1,-1],
	[+1,-1],
	[+1,+1],
	[-1,+1]
];

function grid_init()
{
	for (var i = 0; i < grid_size; i++)
	{
		grid_table[i] = [];
		for (var j = 0; j < grid_size; j++)
		{
			grid_table[i][j] = 0;
		}
	}
}

function grid_location(in_coo)
{
	var grid_cll_i = in_coo[0] / units_dim[0];
	var grid_cll_j = in_coo[1] / units_dim[1];
	grid_cll_i = Math.floor((grid_cll_i + grid_cll_j) / 2) + grid_padding + 6;
	grid_cll_j = Math.floor((grid_cll_i - grid_cll_j) / 2) + grid_padding - 3 + grid_dim_y;
	return [grid_cll_i, grid_cll_j];
}

function cube_init()
{
	for (var iCount = 0; iCount < cube_tot;)
	{
		var pass_bool = true;
		if (iCount < cube_tot/2)
			var rand_x = Math.random() * (cube_max_lft - cube_min_lft) + cube_min_lft;
		else
			var rand_x = Math.random() * (cube_max_rgt - cube_min_rgt) + cube_min_rgt;
		var rand_y = Math.random();
		var coo_x = Math.round(rand_x * screen_dim[0] / atom_dim[0]) * atom_dim[0];
		var coo_y = Math.round(rand_y * screen_dim[1] / atom_dim[1]) * atom_dim[1];
		for (var jCount = 0; jCount < iCount; jCount++)
		{
			if ((coo_x == cube_pos[jCount][0]) && (coo_y == cube_pos[jCount][1]))
			{
				pass_bool = false;
				break;
			}
		}
		if (pass_bool == true)
		{
			cube_pos[iCount] = [coo_x, coo_y];
			iCount += 1;
		}
	}
	cube_pos.sort(cube_sort);
	for (var iCount = 0; iCount < cube_tot; iCount++)
	{
		var coo_this = cube_pos[iCount];
		var img_new = document.createElement('img');
		var img_rand = Math.random();
		var img_this = sprites_dat[0];
		for (var tCount = 0; tCount < sprites_dat.length; tCount++)
		{
			if (img_rand < sprites_dat[tCount][4])
			{
				img_this = sprites_dat[tCount];
				break;
			}
		}
		var img_dim = img_this[3];
		var img_buf = Math.max(img_dim[0], img_dim[1]) - 1;
		img_new.src = img_this[2];
		img_new.style.width  = img_this[0] + 'px';
		img_new.style.height = img_this[1] + 'px';
		img_new.style.position = 'fixed';
		img_new.style.zIndex = start_idx + coo_this[1] / units_dim[1] + img_buf;
		img_new.style.left = coo_this[0] + 'px';
		img_new.style.top  = coo_this[1] + 'px';
		document.body.appendChild(img_new);
		var grid_cll = grid_location(coo_this);
		var grid_cll_i = grid_cll[0];
		var grid_cll_j = grid_cll[1];
		var img_dim_i = img_dim[0];
		var img_dim_j = img_dim[1];
		for (var i = 0; i < img_dim_i; i++)
		{
			for (var j = 0; j < img_dim_j; j++)
			{
				grid_table[grid_cll_i + i][grid_cll_j - j] = 1;
			}
		}
	}
}

function cube_sort(a, b)
{
	if (a[1] > b[1]) 
		return +1;
	if (a[1] < b[1]) 
		return -1;
	return 0;
}

function snake_init()
{
	for (var i = 0; i < snake_len; i++)
	{
		var img_new = document.createElement('img');
//		var img_this = [97,112,'images/isometric_test' + i + '_01.png',	[1,1,1]];
		var img_this = sprites_dat[0];
		img_new.src = img_this[2];
		img_new.id = 'snake' + i;
		img_new.style.width  = img_this[0] + 'px';
		img_new.style.height = img_this[1] + 'px';
		img_new.style.position = 'fixed';
		img_new.style.zIndex = snake_idx;
		img_new.style.left = snake_pos[0] + 'px';
		img_new.style.top  = snake_pos[1] + 'px';
		document.body.appendChild(img_new);
		snake_cll[i] = [0,0];
	}
	window.setInterval(snake_move, 250);
}

function snake_move()
{
	var old_pos_x = snake_pos[0];
	var old_pos_y = snake_pos[1];
	var allow_dir = [1,1,1,1];
	if (old_pos_x < 0)
	{
		allow_dir[0] = 0;
		allow_dir[3] = 0;
	}
	else if (old_pos_x >= screen_dim[0])
	{
		allow_dir[1] = 0;
		allow_dir[2] = 0;
	}
	if (old_pos_y < 0)
	{
		allow_dir[0] = 0;
		allow_dir[1] = 0;
	}
	else if (old_pos_y >= screen_dim[1])
	{
		allow_dir[2] = 0;
		allow_dir[3] = 0;
	}
	if (Math.random() < 0.1)
	{
		var rand_sgn = +1;
		if (Math.random() < 0.5)
			rand_sgn = -1;
		for (var i = 0; i < 4; i++)
		{
			var temp_dir = snake_dir;
			if (temp_dir == 0)
				temp_dir = 4;
			if (i == 0)
				temp_dir += rand_sgn;
			else if (i == 1)
				temp_dir -= rand_sgn;
			else if (i == 2)
				temp_dir += 0;
			else if (i == 3)
				temp_dir += 2;
			temp_dir %= 4;
			if (allow_dir[temp_dir] == 0)
				continue;
			if (snake_position(temp_dir, 0) == true)
				break;
		}
	}
	snake_position(snake_dir, 1);
}

function snake_position(temp_dir, flag)
{
	var old_pos_x = snake_pos[0];
	var old_pos_y = snake_pos[1];
	var new_dir = dir_table[temp_dir];
	var new_pos_i = old_pos_x + units_dim[0] * new_dir[0];
	var new_pos_j = old_pos_y + units_dim[1] * new_dir[1];
	var new_pos = [new_pos_i, new_pos_j];
	var new_cll = grid_location(new_pos);
	var new_cll_i = new_cll[0];
	var new_cll_j = new_cll[1];
	// this shouldn't happen, but it might anyway due to some bug
	if
	(
		(new_cll_i >= grid_size) ||
		(new_cll_j >= grid_size) ||
		(new_cll_i < 0) ||
		(new_cll_j < 0) ||
		(true == false)
	)
		var new_grid = 1;		// should this be 1 or 0? was 1
	else
		var new_grid = grid_table[new_cll_i][new_cll_j];
	if (new_grid == 0)
	{
		snake_seg -= 1;
		if (snake_seg == -1)
			snake_seg = snake_len - 1;
		var old_cll_i = snake_cll[snake_seg][0];
		var old_cll_j = snake_cll[snake_seg][1];
		grid_table[old_cll_i][old_cll_j] = 0;
//		grid_table[new_cll_i][new_cll_j] = 1;		// buggy because the snake can become trapped
		snake_dir = temp_dir;
		snake_pos = new_pos;
		snake_cll[snake_seg] = new_cll;
		snake_idx += new_dir[1];
		var snake_image = document.getElementById('snake' + snake_seg);
		snake_image.style.left = snake_pos[0] + 'px';
		snake_image.style.top  = snake_pos[1] + 'px';
		snake_image.style.zIndex = snake_idx;
		return true;
	}
	snake_move();
}

function cube_scatter()
{
	grid_init();
	cube_init();
	snake_init();
}
