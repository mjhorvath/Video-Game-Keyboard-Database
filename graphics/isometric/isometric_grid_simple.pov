#include "math.inc"

camera
{
	#local CameraDistance = 10;
	#local ScreenArea = sqrt(2*pow(1,2));
	#local AspectRatio = 2*cos(2*pi/12)/1;
	orthographic
	location -z*CameraDistance
	direction z*CameraDistance
	right     x*ScreenArea
	up        y*ScreenArea/AspectRatio
	rotate x*asind(tand(30))
	rotate y*45
}

plane
{
	y,0
	texture
	{
		pigment
		{
			checker color rgb 0, color rgb 1
		}
		finish
		{
			ambient 1
		}
	}
	scale 1
}