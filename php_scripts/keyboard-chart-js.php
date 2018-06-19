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
