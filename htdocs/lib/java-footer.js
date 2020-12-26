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

function reloadThisPage(gameID, layoutID, gameSeo)
{
	// gameID is no longer needed
	var styleID = getValueFromDropDownList("VisualStyleSwitch", "style");
	var formatID = getValueFromRadioButton("tech");
	var tenkeyBool = getValueFromRadioButton("tkey");
	if (formatID == 1)
		window.location.href = "keyboard-diagram-" + gameSeo + ".svg?sty=" + styleID + "&lay=" + layoutID + "&fmt=" + formatID + "&ten=" + tenkeyBool;
	else
		window.location.href = "keyboard-diagram-" + gameSeo + ".php?sty=" + styleID + "&lay=" + layoutID + "&fmt=" + formatID + "&ten=" + tenkeyBool;
}

function getValueFromDropDownList(formname, listname)
{
	var targetForm = document.forms[formname];
	var targetDropDown = targetForm.elements[listname];
	return targetDropDown.value;
}

function getValueFromRadioButton(buttonsname)
{
	var buttons = document.getElementsByName(buttonsname);
	for (var i = 0, n = buttons.length; i < n; i++)
	{
		var this_button = buttons[i];
		if (this_button.checked)
		{
			return this_button.value;
		}
	}
	return null;
}
