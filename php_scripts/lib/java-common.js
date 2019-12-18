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


// there may be a native JavaScript function that can do this better like there is for PHP
function cleantextHTML(in_string)
{
	return in_string.replace(/\&/g,'&amp;').replace(/\>/g,'&gt;').replace(/\</g,'&lt;').replace(/\\n/g,'<br>');
}

function cleantextTSV(in_string)
{
	in_string = in_string.replace(/\\/g,'\\\\');
	return in_string == '' ? '\\N' : in_string;
}

function cleannumberTSV(in_number)
{
	return !in_number ? '\\N' : in_number;
}

// needs work
//function cleantextJS(in_string)
//{
//	return in_string.replace('\'','\\\'');
//}

function does_not_work_yet()
{
	alert('This feature does not work yet.');
}

function test_function()
{
	alert('Hello world!');
}
