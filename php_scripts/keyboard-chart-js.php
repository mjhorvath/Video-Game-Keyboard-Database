// Copyright (C) 2018  Michael Horvath
//
// This program is free software; you can redistribute it and/or 
// modify it under the terms of the GNU General Public License 
// as published by the Free Software Foundation; either version 2 
// of the License, or (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful, 
// but WITHOUT ANY WARRANTY; without even the implied warranty of 
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details: 
// http://www.gnu.org/licenses/gpl.html

function reloadThisPage(gameID, layoutID, gameSeo)
{
	var targetForm = document.forms["VisualStyleSwitch"];
	var targetDropDown = targetForm.elements["style"];
	var targetRadioBox = targetForm.elements["tech"];
	var styleID = targetDropDown.value;
	var formatID = getValueFromRadioButton("tech");
	var locHref = "keyboard-diagram-" + gameSeo + ".php?sty=" + styleID + "&lay=" + layoutID + "&fmt=" + formatID;
	window.location.href = locHref;
}

function getValueFromRadioButton(name)
{
	//Get all elements with the name
	var buttons = document.getElementsByName(name);
	for (var i = 0, n = buttons.length; i < n; i++)
	{
		//Check if button is checked
		var button = buttons[i];
		if (button.checked)
		{
			//Return value
			return button.value;
		}
	}
	//No radio button is selected
	return null;
}
