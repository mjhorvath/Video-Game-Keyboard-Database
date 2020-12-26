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


// should split the first line of "sprites_data" off into a new "snake_sprite" variable

var code_mode = 1;		// 0 = dimetric, 1 = near isometric, 2 = isometric stretch

switch (code_mode)
{
	// dimetric
	// projection very common in video games
	// most likely to work correctly
	// there should not be any artifacts in the end result if the browser and desktop PPI are both set to 100%
	case 0:
		// pixel w, pixel h
		var atom_dim = [96,48];
		var path_prefix = './lib/';
		var grid_file = path_prefix + 'dimetric-grid.png';
		var sprites_data =
		[
			// the first item is used inside the snake
			// pixel w, pixel h, file path, 3D dimensions, likelihood/odds
			[96, 96, path_prefix + 'dimetric-sprite-cube-sml.png', [1,1,1], 0.5],
			[96, 96, path_prefix + 'dimetric-sprite-pyrd-sml.png', [1,1,1], 0.6],
			[96, 96, path_prefix + 'dimetric-sprite-sphr-med.png', [2,2,2], 0.8],
			[96, 96, path_prefix + 'dimetric-sprite-cube-med.png', [2,2,2], 0.9],
			[96, 96, path_prefix + 'dimetric-sprite-cube-lrg.png', [3,3,3], 1.0]
		];
	break;
	// near isometric
	// very near true isometric, but deviates by tiny amounts
	// take a look at "isometric_aspect_ratios.txt" to see how far off the mark it is
	// the number 97 is not evenly divisible by 8, so there are going to be noticeable artifacts
	case 1:
		// pixel w, pixel h
		var atom_dim = [97,56];
		var path_prefix = './lib/';
		var grid_file = path_prefix + 'isometric-grid.png';
		var sprites_data =
		[
			// the first item is used inside the snake
			// pixel w, pixel h, file path, 3D dimensions, likelihood/odds
			[97, 112, path_prefix + 'isometric-sprite-cube-sml.png', [1,1,1], 0.5],
			[97, 112, path_prefix + 'isometric-sprite-pyrd-sml.png', [1,1,1], 0.6],
			[97, 112, path_prefix + 'isometric-sprite-sphr-med.png', [2,2,2], 0.8],
			[97, 112, path_prefix + 'isometric-sprite-cube-med.png', [2,2,2], 0.9],
			[97, 112, path_prefix + 'isometric-sprite-cube-lrg.png', [3,3,3], 1.0]
		];
	break;
	// isometric stretch
	// creates a regular hexagon from a non-regular hexagon by stretching the non-hexagon sprite
	// does not yet work with sprites of 3D shapes such as spheres and pyramids at this time however
	// the vertical resolution is not evenly divisible by 8, so there are going to be noticeable artifacts
	case 2:
		var hex_scale = 2/Math.sqrt(3);
		// pixel w, pixel h
		var atom_dim = [96,48*hex_scale];
		var path_prefix = './lib/';
		var grid_file = path_prefix + 'still-to-do.png';
		var sprites_data =
		[
			// the first item is used inside the snake
			// pixel w, pixel h, file path, 3D dimensions, likelihood/odds
			[96, 96*hex_scale, path_prefix + 'still-to-do.png', [1,1,1], 0.5],
			[96, 96*hex_scale, path_prefix + 'still-to-do.png', [1,1,1], 0.6],
			[96, 96*hex_scale, path_prefix + 'still-to-do.png', [2,2,2], 0.8],
			[96, 96*hex_scale, path_prefix + 'still-to-do.png', [2,2,2], 0.9],
			[96, 96*hex_scale, path_prefix + 'still-to-do.png', [3,3,3], 1.0]
		];
	break;
}

var units_dim = [atom_dim[0] / 8, atom_dim[1] / 8];

// need to double check if these parameters are still correct after so many years
var screen_dim = [];
if (window.innerWidth)
{
	screen_dim[0] = window.innerWidth;
	screen_dim[1] = window.innerHeight;
}
else if (document.documentElement.clientWidth)
{
	screen_dim[0] = document.documentElement.clientWidth;
	screen_dim[1] = document.documentElement.clientHeight;
}
else if (document.body.clientWidth)
{
	screen_dim[0] = document.body.clientWidth;
	screen_dim[1] = document.body.clientHeight;
}

var screen_dim = [screen_dim[0] - atom_dim[0], screen_dim[1] - atom_dim[1]];		// why do I need this?
var screen_mul = screen_dim[0] * screen_dim[1] / 1024 / 768;

//console.log('screen_dim[0]:' + screen_dim[0] + ';screen_dim[1]:' + screen_dim[1] + ';');

var grid_table = [];
var grid_padding = 8;
var grid_dim_x = Math.ceil(screen_dim[0] / units_dim[0] / 2);
var grid_dim_y = Math.ceil(screen_dim[1] / units_dim[1] / 2);
var grid_size = grid_dim_x + grid_dim_y + grid_padding * 2;

var cube_pos = [];
var cube_num = 0;	// was 8
var cube_tot = Math.round(screen_mul) * cube_num;
/*
var cube_min_lft = 0/6;
var cube_max_lft = 1/6;
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
var snake_pos =
[
	Math.round(Math.random() * screen_dim[0] / atom_dim[0]) * atom_dim[0],
	Math.round(Math.random() * screen_dim[1] / atom_dim[1]) * atom_dim[1]
];

var snake_idx = snake_pos[1] / units_dim[1];
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

// not sure anymore what is going on here
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
			iCount++;
		}
	}
	cube_pos.sort(cube_sort);
	for (var iCount = 0; iCount < cube_tot; iCount++)
	{
		var coo_this = cube_pos[iCount];
		var img_new = document.createElement('img');
		var img_rand = Math.random();
		var img_this = sprites_data[0];
		for (var tCount = 0, tLength = sprites_data.length; tCount < tLength; tCount++)
		{
			if (img_rand < sprites_data[tCount][4])
			{
				img_this = sprites_data[tCount];
				break;
			}
		}
		var img_dim = img_this[3];
		var img_dim_i = img_dim[0];
		var img_dim_j = img_dim[1];
		var img_buf = Math.max(img_dim[0], img_dim[1]) - 1;
		img_new.src = img_this[2];
		img_new.style.width  = img_this[0] + 'px';
		img_new.style.height = img_this[1] + 'px';
		img_new.style.position = 'fixed';
		img_new.style.zIndex = coo_this[1] / units_dim[1] + img_buf;
		img_new.style.left = coo_this[0] + 'px';
		img_new.style.top  = coo_this[1] + 'px';
		this_body.appendChild(img_new);
		var grid_cll = grid_location(coo_this);
		var grid_cll_i = grid_cll[0];
		var grid_cll_j = grid_cll[1];
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
		var img_this = sprites_data[0];
		img_new.src = img_this[2];
		img_new.id = 'snake' + i;
		img_new.style.width  = img_this[0] + 'px';
		img_new.style.height = img_this[1] + 'px';
		img_new.style.position = 'fixed';
		img_new.style.zIndex = snake_idx;
		img_new.style.left = snake_pos[0] + 'px';
		img_new.style.top  = snake_pos[1] + 'px';
		this_body.appendChild(img_new);
		snake_cll[i] = [0,0];
	}
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
			switch (i)
			{
				case 0:
					temp_dir += rand_sgn;
				break;
				case 1:
					temp_dir -= rand_sgn;
				break;
				case 2:
					temp_dir += 0;
				break;
				case 3:
					temp_dir += 2;
				break;
			}
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
	// this shouldn't happen, but it might happen anyway as a result of a bug
	if ((new_cll_i >= grid_size) || (new_cll_j >= grid_size) || (new_cll_i < 0) || (new_cll_j < 0))
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
//		console.log('left:' + snake_pos[0] + ';top:' + snake_pos[1] + ';');
		snake_image.style.left = snake_pos[0] + 'px';
		snake_image.style.top  = snake_pos[1] + 'px';
		snake_image.style.zIndex = snake_idx;
		return true;
	}
	else if (new_grid == 1)
	{
		snake_move();
		return false;
	}
}

function anim_init()
{
	window.setInterval(snake_move, 500);
}

// this works until the user zooms in or out or changes the size of the browser window without refreshing
// then the user needs to manually refresh the page to reset the sprites, as there is no way to detect if/when a browser has been "zoomed"
function screen_init()
{
	var pixel_ratio = window.devicePixelRatio;
	this_body.style.backgroundImage = 'url(' + grid_file + ')';
	this_body.style.backgroundColor = '#e0e0e0';
	this_body.style.backgroundSize = atom_dim[0] + 'px ' + atom_dim[1] + 'px';
//	this_body.style.border = '2px solid yellow';
//	this_body.style.boxSizing = 'border-box';
	this_body.style.position = 'fixed';
	this_body.style.left = '0px';
	this_body.style.top = '0px';
	this_body.style.width = (100 * pixel_ratio) + '%';
	this_body.style.height = (100 * pixel_ratio) + '%';
	this_body.style.zIndex = -10000;
	this_body.style.margin = '0px';
	this_body.style.transformOrigin = 'top left';
	this_body.style.transform = 'scale(' + (1/pixel_ratio) + ')';
}

function cube_snake_init()
{
	this_body = document.getElementById('snake_pane');
	screen_init();
	grid_init();
	cube_init();
	snake_init();
	anim_init();
}
