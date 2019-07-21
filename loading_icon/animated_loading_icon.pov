#version 3.7;

global_settings
{
	assumed_gamma 1.0
}

// ----------------------------------------

camera
{
	orthographic
	location	-z*1000
	direction	+z*1000
	up			10*y
	right		10*x*image_width/image_height
	rotate		x * 90
}

// ----------------------------------------

#macro move_disc()
	#switch (clock)
		#range (0/4,1/4)
			#local temp_clock = (clock - 0/4) * 4;
			translate +x * (+3 - temp_clock * 6)
			translate +z * (+3)
		#break
		#range (1/4,2/4)
			#local temp_clock = (clock - 1/4) * 4;
			translate +x * (-3)
			translate +z * (+3 - temp_clock * 6)
		#break
		#range (2/4,3/4)
			#local temp_clock = (clock - 2/4) * 4;
			translate +x * (-3 + temp_clock * 6)
			translate +z * (-3)
		#break
		#range (3/4,4/4)
			#local temp_clock = (clock - 3/4) * 4;
			translate +x * (+3)
			translate +z * (-3 + temp_clock * 6)
		#break
	#end
#end


// orange
disc
{
	0, y, 2
	pigment {color srgb <241,143,001>/255}
	finish
	{
		diffuse 0
		ambient 0
		emission 1
	}
	move_disc()
	rotate +y * 000
}

// cyan
disc
{
	0, y, 2
	pigment {color srgb <004,139,168>/255}
	finish
	{
		diffuse 0
		ambient 0
		emission 1
	}
	move_disc()
	rotate +y * 090
}

// purple
disc
{
	0, y, 2
	pigment {color srgb <046,064,087>/255}
	finish
	{
		diffuse 0
		ambient 0
		emission 1
	}
	move_disc()
	rotate +y * 180
}

// green
disc
{
	0, y, 2
	pigment {color srgb <153,194,077>/255}
	finish
	{
		diffuse 0
		ambient 0
		emission 1
	}
	move_disc()
	rotate +y * 270
}