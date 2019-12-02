// Title: Shape grid macro v1.00
// Author: Michael Horvath
// Homepage: http://www.geocities.com/Area51/Quadrant/3864/povray.htm
// Created: 2008-06-22
// Last Updated: 2008-06-22
// This file is licensed under the terms of the CC-LGPL.

#include "ShapeGrid_macro.inc"
#include "functions.inc"
#include "math.inc"
#include "finish.inc"
#include "transforms.inc"
#include "Axes_macro.inc"
#local show_grid = 0;
#local cube_scale = 2;
#local gap_width = 0;//0.004;

//------------------------------
// Scenery

global_settings
{
	assumed_gamma 1.0
	ambient_light 0.3
//	radiosity {brightness 0.3}
}

background {rgb 0}

union
{
	light_source
	{
		x*-100
		color rgb 2/4
		shadowless
		parallel
	}
	rotate		z*-60
	rotate		y*-30
}

union
{
	light_source
	{
		x*-100
		color rgb 2/4
		shadowless
		parallel
	}
	rotate		z*-60
	rotate		y*+60
}

camera
{
	#local CameraDistance =	10;
	#local ScreenArea =	sqrt(2 * pow(1,2)) * 4/10 * 1;
	#local AspectRatio =	2 * cos(2 * pi/12) * 1/2;
	orthographic
	location	-z * CameraDistance
	direction	+z	// * CameraDistance
	right		x * 7/1 * ScreenArea
	up		y * 1/2 * ScreenArea/AspectRatio
//	rotate		x * 90
	rotate		x * asind(tand(30))
//	rotate		x * 30
	rotate		y * 45
//	rotate		y * 90
}

//------------------------------
// the coordinate grid and axes

Axes_Macro
(
	10,	// Axes_axesSize,	The distance from the origin to one of the grid's edges.	(float)
	.1,	// Axes_majUnit,	The size of each large-unit square.	(float)
	5,	// Axes_minUnit,	The number of small-unit squares that make up a large-unit square.	(integer)
	0.0005,	// Axes_thickRatio,	The thickness of the grid lines (as a factor of axesSize).	(float)
	off,	// Axes_aBool,		Turns the axes on/off. (boolian)
	on,	// Axes_mBool,		Turns the minor units on/off. (boolian)
	off,	// Axes_xBool,		Turns the plane perpendicular to the x-axis on/off.	(boolian)
	on,	// Axes_yBool,		Turns the plane perpendicular to the y-axis on/off.	(boolian)
	off	// Axes_zBool,		Turns the plane perpendicular to the z-axis on/off.	(boolian)
)

object
{
	Axes_Object
	translate 0.000001
	#if (!show_grid)
		no_image
	#end
}

//------------------------------
// CSG objects

#declare BasicObject_Texture = texture
{
	pigment {color rgb 1}
	finish
	{
		Phong_Glossy
		ambient 2/4
	}
}

#declare Font_Name = "visitor1.ttf";
//#declare Font_Name = "acknowtt.ttf";		// a little short, nicer shape than visitor
//#declare Font_Name = "CWEBL.TTF";		// a little narrow
//#declare Font_Name = "ltype.TTF";		// too smooth, too tall
//#declare Font_Name = "VeraMono.TTF";		// too smooth, too tall
//#declare Font_Name = "04B_21__.TTF";		// unreadable
//#declare Font_Name = "D3CutebitmapismB.ttf";	// clipping
//#declare Font_Name = "m39.TTF";		// clipping
//#declare Font_Name = "MICRO___.TTF";		// small but nice, uneven spacing not apparent when typed I gather
#declare Axes_Number = 2;
#declare String_Array = array[3][13]
{
	{"I","S","O","M","E","T","R","I","C","L","A","N","D",},
	{"I","S","O","M","E","T","R","I","C","L","A","N","D",},
	{"I","S","O","M","E","T","R","I","C","L","A","N","D",}
}

union
{
	#local iCount = 0;
	#while (iCount < 13)
		intersection
		{
			#switch (Axes_Number)
				#case (3)
					text
					{
						ttf Font_Name String_Array[2][iCount] 1/2, 0
						rotate		+x * 090
						translate	-x * 1/2
						translate	+y * 1/2
						translate	-z * 1/2
					}
				#case (2)
					text
					{
						ttf Font_Name String_Array[1][iCount] 1/2, 0
						translate	-x * 1/2
						translate	-z * 1/2
					}
				#case (1)
					text
					{
						ttf Font_Name String_Array[0][iCount] 1/2, 0
						rotate		+y * 090
						translate	-x * 1/2
					}
				#break
			#end
			scale		1/2
			translate	+x * 1/2 * iCount
			translate	-z * 1/2 * iCount
		}
		#local iCount = iCount + 1;
	#end
	texture {BasicObject_Texture}
	translate	-x * 1/2 * 13 * 1/2
	translate	+z * 1/2 * 13 * 1/2
	scale		1/5 * cube_scale
}
