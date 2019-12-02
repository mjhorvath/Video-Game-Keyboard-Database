// Title: Shape grid macro v1.00
// Author: Michael Horvath
// Homepage: http://www.geocities.com/Area51/Quadrant/3864/povray.htm
// Created: 2008-06-22
// Last Updated: 2008-06-22
// This file is licensed under the terms of the CC-LGPL.

#include "ShapeGrid_macro.inc"
#include "functions.inc"
#include "math.inc"
#include "transforms.inc"
#include "Axes_macro.inc"


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
	#local ScreenArea = sqrt(2*pow(1,2)) * 4/10 * 7;
	#local AspectRatio = 2*cos(2*pi/12)/1;
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
	no_reflection
}

//------------------------------
// CSG objects

#declare SphereObject_Object = sphere {0, 1}

#declare SphereObject_Intersection = intersection
{
	plane {+z, 0 rotate y * 0}
	plane {-y, 0 rotate z * 0}
	plane {+x, 0 rotate y * 0}
}

#declare BasicObject_Pigment = texture
{
	pigment {color rgb <4/4,4/4,4/4,>}
	finish {ambient 2/4}
}


//------------------------------
// Example in an intersection

difference
{
	intersection
	{
		object {SGrid_Sphere_Macro(6,12,6,1,0,0.01,on,off,)}
		object {SphereObject_Object}
	}
	object {SphereObject_Intersection}
	texture {BasicObject_Pigment}
	no_reflection
}
