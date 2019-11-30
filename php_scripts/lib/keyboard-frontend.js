function Toggle_Waiting(thisBool)
{
	document.getElementById('waiting').style.display = thisBool ? 'block' : 'none';
}

// I'm not entirely confident this script works properly
function Reset_Page()
{
	document.getElementById('lay_check').style.display = 'none';
	document.getElementById('sty_check').style.display = 'none';
	document.getElementById('gam_check').style.display = 'none';
	document.getElementById('fmt_check').style.display = 'none';
	document.getElementById('ten_check').style.display = 'none';
	document.getElementById('but_check').style.display = 'none';
	document.getElementById('lay_xmark').style.display = 'none';
	document.getElementById('sty_xmark').style.display = 'none';
	document.getElementById('gam_xmark').style.display = 'none';
	document.getElementById('fmt_xmark').style.display = 'none';
	document.getElementById('ten_xmark').style.display = 'none';
	document.getElementById('but_xmark').style.display = 'none';
	document.getElementById('but_ready').style.display = 'none';
	document.getElementById('but_error').style.display = 'none';

	for (var i = 0; i < style_count; i++)
	{
		var thisselect = document.getElementById('sty_' + i);
		if (thisselect)
			thisselect.className = 'acc_dis';
	}
	for (var i = 0; i < game_count; i++)
	{
		var thisselect = document.getElementById('gam_' + i);
		if (thisselect)
			thisselect.className = 'acc_dis';
	}
	if (lastClicked['lay'])
		lastClicked['lay'].className = 'acc_nrm';

	lastClicked = {}
	// these three are hidden inputs
	document.forms['keyboard_select'].lay.value = '';
	document.forms['keyboard_select'].sty.value = '';
	document.forms['keyboard_select'].gam.value = '';
	resetRadioButtons("fmt_radio");
	resetRadioButtons("ten_radio");
	disableResetButton();
}

function Set_Game(thisindex, thiselement)
{
	if (thiselement.className == 'acc_dis')
	{
		return;
	}
	var hasSelect = false;
	var gametable = game_table[thisindex];
	var lastselect = lastClicked['gam'];
	for (var i = 0; i < game_count; i++)
	{
		var thisselect = document.getElementById('gam_' + i);
		if (thisselect)
		{
			if (gametable[i] == 1)
			{
				if (thisselect == lastselect)
				{
					thisselect.className = 'acc_sel';
					hasSelect = true;
				}
				else
				{
					thisselect.className = 'acc_nrm';
				}
			}
			else
			{
				thisselect.className = 'acc_dis';
				if (thisselect == lastselect)
				{
					document.getElementById('gam_check').style.display = 'none';
					document.getElementById('but_check').style.display = 'none';
					document.getElementById('gam_xmark').style.display = 'inline-block';
					document.getElementById('but_xmark').style.display = 'inline-block';
					document.getElementById('but_ready').style.display = 'none';
					document.getElementById('but_error').style.display = 'block';
					lastClicked['gam'] = null;
				}
			}
		}
	}
	if (hasSelect == false)
	{
		document.forms['keyboard_select'].gam.value = '';
	}
}

function Set_Style(thisindex, thiselement)
{
	if (thiselement.className == 'acc_dis')
	{
		return;
	}
	var hasSelect = false;
	var styletable = style_table[thisindex];
	var lastselect = lastClicked['sty'];
	for (var i = 0; i < style_count; i++)
	{
		var thisselect = document.getElementById('sty_' + i);
		if (thisselect)
		{
			if (styletable[i] == 1)
			{
				if (thisselect == lastselect)
				{
					thisselect.className = 'acc_sel';
					hasSelect = true;
				}
				else
				{
					thisselect.className = 'acc_nrm';
				}
			}
			else
			{
				thisselect.className = 'acc_dis';
				if (thisselect == lastselect)
				{
					document.getElementById('sty_check').style.display = 'none';
					document.getElementById('but_check').style.display = 'none';
					document.getElementById('sty_xmark').style.display = 'inline-block';
					document.getElementById('but_xmark').style.display = 'inline-block';
					document.getElementById('but_ready').style.display = 'none';
					document.getElementById('but_error').style.display = 'block';
					lastClicked['sty'] = null;
				}
			}
		}
	}
	if (hasSelect == false)
	{
		document.forms['keyboard_select'].sty.value = '';
	}
}

function Set_Select_Value(thisElement)
{
	if (thisElement.className == 'acc_dis')
	{
		return;
	}
	var SelectForm = document.forms['keyboard_select'];
	var thisInput = thisElement.getAttribute('menu');
	var thisValue = thisElement.getAttribute('value');
	SelectForm[thisInput].value = thisValue;
	if ((SelectForm.sty.value == '') || (SelectForm.gam.value == ''))
	{
//		document.getElementById('fmt_check').style.display = 'none';
//		document.getElementById('ten_check').style.display = 'none';
		document.getElementById('but_check').style.display = 'none';
//		document.getElementById('fmt_xmark').style.display = 'inline-block';
//		document.getElementById('ten_xmark').style.display = 'inline-block';
		document.getElementById('but_xmark').style.display = 'inline-block';
		document.getElementById('but_ready').style.display = 'none';
		document.getElementById('but_error').style.display = 'block';
	}
	else
	{
		document.getElementById('fmt_check').style.display = 'inline-block';
		document.getElementById('ten_check').style.display = 'inline-block';
		document.getElementById('but_check').style.display = 'inline-block';
		document.getElementById('fmt_xmark').style.display = 'none';
		document.getElementById('ten_xmark').style.display = 'none';
		document.getElementById('but_xmark').style.display = 'none';
		document.getElementById('but_ready').style.display = 'block';
		document.getElementById('but_error').style.display = 'none';
	}
	document.getElementById(thisInput + '_check').style.display = 'inline-block';
	document.getElementById(thisInput + '_xmark').style.display = 'none';
	enableResetButton();
}

function Select_Init()
{
	var SelectList = ['gam','sty','lay']		// possibly more useful in the future if additional accordion lists are created
	var SelectForm = document.forms['keyboard_select'];
	for (var i = 0, n = SelectList.length; i < n; i++)
	{
		var thisSelect = SelectList[i];
		initMenu(thisSelect);
		SelectForm[thisSelect].value = '';
	}
	Toggle_Waiting(0);
}

function Check_Values_and_Spawn()
{
	var SelectForm = document.forms['keyboard_select'];
	var lay_value = SelectForm.lay.value == '' ? null : parseInt(SelectForm.lay.value);
	var gam_value = SelectForm.gam.value == '' ? null : parseInt(SelectForm.gam.value);
	var sty_value = SelectForm.sty.value == '' ? null : parseInt(SelectForm.sty.value);
	var fmt_value = getValueFromRadioButton('fmt_radio');
	var ten_value = getValueFromRadioButton('ten_radio');
	var WarnBoxReady = document.getElementById('but_ready');
	var WarnBoxError = document.getElementById('but_error');
	if (lay_value && gam_value && sty_value)
	{
		WarnBoxReady.style.display = 'block';
		WarnBoxError.style.display = 'none';
		var seo_value = seourl_table[gam_value-1];
		window.open('keyboard-diagram-' + seo_value + '.php?sty=' + sty_value + '&lay=' + lay_value + '&fmt=' + fmt_value + '&ten=' + ten_value);
	}
	else	
	{
		WarnBoxReady.style.display = 'none';
		WarnBoxError.style.display = 'block';
	}
}

function getValueFromRadioButton(name)
{
	var buttons = document.getElementsByName(name);
	for (var i = 0, n = buttons.length; i < n; i++)
	{
		var button = buttons[i];
		if (button.checked)
		{
			return button.value;
		}
	}
	return null;
}

function resetRadioButtons(name)
{
	var buttons = document.getElementsByName(name);
	for (var i = 0, n = buttons.length; i < n; i++)
	{
		var button = buttons[i];
		if (i == 0)
			button.checked = true;
		else
			button.checked = false;
	}
}

function enableResetButton()
{
	document.getElementById('but_reset').disabled = false;
}

function disableResetButton()
{
	document.getElementById('but_reset').disabled = true;
}
