var selectList = ['gam','sty','lay']
var lastClicked = {}

function Toggle_Waiting(thisBool)
{
	document.getElementById('waiting').style.display = thisBool ? 'block' : 'none';
}

function Set_Game(thisindex, thiselement)
{
	if (thiselement.className == 'acc_dis')
	{
		return;
	}
	var hasSelect = false;
	var gametable = game_table[thisindex];
	for (var i = 0, n = gametable.length; i < n; i++)
	{
		var thisselect = document.getElementById('gam_' + i);
		if (thisselect)
		{
			if (gametable[i] == 1)
			{
				if (thisselect == lastClicked['gam'])
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
				if (thisselect == lastClicked['gam'])
				{
					document.getElementById('gam_check').style.display = 'none';
					document.getElementById('but_check').style.display = 'none';
					document.getElementById('gam_xmark').style.display = 'block';
					document.getElementById('but_xmark').style.display = 'block';
					document.getElementById('butready').style.display = 'none';
					document.getElementById('buterror').style.display = 'block';
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
	for (var i = 0, n = styletable.length; i < n; i++)
	{
		var thisselect = document.getElementById('sty_' + i);
		if (thisselect)
		{
			if (styletable[i] == 1)
			{
				if (thisselect == lastClicked['sty'])
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
				if (thisselect == lastClicked['sty'])
				{
					document.getElementById('sty_check').style.display = 'none';
					document.getElementById('but_check').style.display = 'none';
					document.getElementById('sty_xmark').style.display = 'block';
					document.getElementById('but_xmark').style.display = 'block';
					document.getElementById('butready').style.display = 'none';
					document.getElementById('buterror').style.display = 'block';
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
		document.getElementById('butready').style.display = 'none';
		document.getElementById('buterror').style.display = 'block';
//		document.getElementById('fmt_check').style.display = 'none';
//		document.getElementById('ten_check').style.display = 'none';
		document.getElementById('but_check').style.display = 'none';
//		document.getElementById('fmt_xmark').style.display = 'block';
//		document.getElementById('ten_xmark').style.display = 'block';
		document.getElementById('but_xmark').style.display = 'block';
	}
	else
	{
		document.getElementById('butready').style.display = 'block';
		document.getElementById('buterror').style.display = 'none';
		document.getElementById('fmt_check').style.display = 'block';
		document.getElementById('ten_check').style.display = 'block';
		document.getElementById('but_check').style.display = 'block';
		document.getElementById('fmt_xmark').style.display = 'none';
		document.getElementById('ten_xmark').style.display = 'none';
		document.getElementById('but_xmark').style.display = 'none';
	}
	document.getElementById(thisInput + '_check').style.display = 'block';
	document.getElementById(thisInput + '_xmark').style.display = 'none';
}

function Select_Init()
{
	var SelectForm = document.forms['keyboard_select'];
	for (var i = 0, n = selectList.length; i < n; i++)
	{
		var thisSelect = selectList[i];
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
	var fmt_value = getValueFromRadioButton('fmtradio');
	var ten_value = getValueFromRadioButton('tenradio');
	var WarnBoxReady = document.getElementById('butready');
	var WarnBoxError = document.getElementById('buterror');
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
	// Get all elements with the name
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
	// No radio button is selected
	return null;
}
