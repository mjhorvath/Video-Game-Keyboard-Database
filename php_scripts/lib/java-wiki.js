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

function clear_all()
{
	document.getElementById('site_out').value = '';
}

function select_and_copy_all()
{
	document.getElementById('site_out').select();
	document.execCommand('copy');
}
