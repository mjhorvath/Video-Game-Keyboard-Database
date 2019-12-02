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
#local show_grid =	0;
#local cube_scale =	4;
#local gap_width =	0;//0.004;

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
/*
	cylinder
	{
		0, x*-100, 0.01
		pigment {color rgb y}
	}
*/
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
/*
	cylinder
	{
		0, x*-100, 0.01
		pigment {color rgb y}
	}
*/
	rotate		z*-60
	rotate		y*+60
}

camera
{
	#local CameraDistance = 10;
	#local ScreenArea = sqrt(2*pow(1,2)) * 4/10 * 3;
	#local AspectRatio = 2*cos(2*pi/12) * 3/6;
	orthographic
	location -z*CameraDistance
	direction z*CameraDistance
	right     x*ScreenArea
	up        y*ScreenArea/AspectRatio
//	rotate x*90
	rotate x*asind(tand(30))
//	rotate x*30
	rotate y*45
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

#declare BasicObject_Pigment = texture
{
	pigment {color rgb 1}
	finish
	{
//		Phong_Glossy
		ambient 2/4
	}
}

#macro Menger_Sponge(dMax, cSca, gWid)
	#local iPos = array[27];
	#local gPos = array[27];
	#local iMax = 3;
	#local iCount = 0;
	#local xCount = 0;
	#while (xCount < iMax)
		#local yCount = 0;
		#while (yCount < iMax)
			#local zCount = 0;
			#while (zCount < iMax)
				#local iPos[iCount] = <xCount,yCount,zCount,>;
				#local gPos[iCount] = 0;
				#if ((xCount = 1) & (yCount = 1))
				#else
					#if ((xCount = 1) & (zCount = 1))
					#else
						#if ((zCount = 1) & (yCount = 1))
						#else
							#local gPos[iCount] = 1;
						#end
					#end
				#end
				#local iCount = iCount + 1;
				#local zCount = zCount + 1;
			#end
			#local yCount = yCount + 1;
		#end
		#local xCount = xCount + 1;
	#end
	#local gWid = gWid * pow(3, dMax);
	#local bObj = box
	{
		0, cSca
		scale 1 - gWid/cSca
		translate gWid/2
	}

	#local cObj = merge
	{
		#local iCount = 0;
		#while (iCount < 27)
			#local gVal = gPos[iCount];
			#if (gVal = 0)
				#local iCoo = iPos[iCount] * cSca;
				object
				{
					bObj
					translate iCoo
					scale 1/3
				}
			#end
			#local iCount = iCount + 1;
		#end
	}


	#local dCount = 0;
	#while (dCount < dMax)
		#local bObj = merge
		{
			#local iCount = 0;
			#while (iCount < 27)
				#local gVal = gPos[iCount];
				#if (dCount = 0)
					#if (gVal = 0)
						#local iCoo = iPos[iCount] * cSca;
						object
						{
							bObj
							translate iCoo
							scale 1/3
						}
					#end
				#else
					#if (gVal = 1)
						#local iCoo = iPos[iCount] * cSca;
						object
						{
							bObj
							translate iCoo
							scale 1/3
						}
					#end
				#end
				#local iCount = iCount + 1;
			#end
		}
		#local dCount = dCount + 1;
	#end
	merge
	{
		object {bObj}
		object {cObj}
	}
#end

difference
{

	object
	{
		Menger_Sponge(cube_scale - 1, 1, gap_width)
	}
	bounded_by
	{
		box {0, 1}
	}
	texture {BasicObject_Pigment}
	scale 9/10
	rotate y * 180
}
