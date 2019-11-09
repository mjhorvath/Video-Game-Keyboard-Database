<?php header("Content-Type: text/javascript; charset=utf8"); ?>

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
	var locHref = "keyboard-diagram-" + gameSeo + ".php?sty=" + styleID + "&lay=" + layoutID + "&fmt=" + formatID + "&ten=" + tenkeyBool;
	window.location.href = locHref;
}

function getValueFromDropDownList(formname, listname)
{
	var targetForm = document.forms[formname];
	var targetDropDown = targetForm.elements[listname];
	return targetDropDown.value;
}

function getValueFromRadioButton(buttonsname)
{
	//Get all elements with the name
	var buttons = document.getElementsByName(buttonsname);
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
