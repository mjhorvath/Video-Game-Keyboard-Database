<?php
	// Copyright (C) 2018  Michael Horvath
	//
	// This program is free software: you can redistribute it and/or modify
	// it under the terms of the GNU General Public License as published by
	// the Free Software Foundation, either version 3 of the License, or
	// any later version.
	// 
	// This program is distributed in the hope that it will be useful,
	// but WITHOUT ANY WARRANTY; without even the implied warranty of
	// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	// GNU General Public License for more details.
	// 
	// You should have received a copy of the GNU General Public License
	// along with this program. If not, see <https://www.gnu.org/licenses/>.

	$path_root		= "../";

	header("Content-Type: text/javascript; charset=utf8");

	include($path_root. "ssi/keyboard-connection.php");
	include("./keyboard-common.php");

	$con = mysqli_connect($con_website,$con_username,$con_password,$con_database);
 
	// check connection
	if (mysqli_connect_errno())
	{
		trigger_error("Database connection failed: "  . mysqli_connect_error(), E_USER_ERROR);
	}

	mysqli_query($con, "SET NAMES 'utf8'");

	$layout_table		= [];
	$layout_string		= "";
	$seourl_table		= [];
	$seourl_string		= "";
	$style_table		= [];
	$style_string		= "";
	$games_max		= 0;
	$layouts_max		= 0;
	$styles_max		= 0;

	function doGamesAutoincJS($in_result)
	{
		global $games_max;
		$game_row = mysqli_fetch_row($in_result);
		$games_max = $game_row[0] - 1;
	}
	function doLayoutsAutoincJS($in_result)
	{
		global $layouts_max;
		$layout_row = mysqli_fetch_row($in_result);
		$layouts_max = $layout_row[0] - 1;
	}
	function doStylesAutoincJS($in_result)
	{
		global $styles_max;
		$style_row = mysqli_fetch_row($in_result);
		$styles_max = $style_row[0] - 1;
	}
	function doGamesJS($in_result)
	{
		global $seourl_table;
		while ($game_row = mysqli_fetch_row($in_result))
		{
			// game_id, game_friendlyurl
			$game_id = $game_row[0];
			$game_seo = $game_row[1];
			$seourl_table[$game_id-1] = $game_seo;
		}
	}
	function doGameRecordsJS($in_result)
	{
		global $layout_table;
		while ($gamesrecord_row = mysqli_fetch_row($in_result))
		{
			// record_id, game_id, layout_id
			$gamesrecord_id = $gamesrecord_row[0];
			$game_id = $gamesrecord_row[1];
			$layout_id = $gamesrecord_row[2];
			$layout_table[$game_id-1][$layout_id-1] = true;
		}
	}
	function doStyleRecordsJS($in_result)
	{
		global $style_table;
		while ($stylesrecord_row = mysqli_fetch_row($in_result))
		{
			// record_id, style_id, layout_id
			$stylesrecord_id = $stylesrecord_row[0];
			$style_id = $stylesrecord_row[1];
			$layout_id = $stylesrecord_row[2];
			$style_table[$layout_id-1][$style_id-1] = true;
		}
	}


	$selectString = "SELECT MAX(g.game_id) FROM games AS g;";
	selectQuery($con, $selectString, "doGamesAutoincJS");

	$selectString = "SELECT MAX(l.layout_id) FROM layouts AS l;";
	selectQuery($con, $selectString, "doLayoutsAutoincJS");

	$selectString = "SELECT MAX(s.style_id) FROM styles AS s;";
	selectQuery($con, $selectString, "doStylesAutoincJS");

	$selectString = "SELECT g.game_id, g.game_friendlyurl FROM games AS g;";
	selectQuery($con, $selectString, "doGamesJS");

	$selectString = "SELECT r.record_id, r.game_id, r.layout_id FROM records_games AS r;";
	selectQuery($con, $selectString, "doGameRecordsJS");

	$selectString = "SELECT r.record_id, r.style_id, r.layout_id FROM records_styles AS r;";
	selectQuery($con, $selectString, "doStyleRecordsJS");


	for ($i = 0; $i < $games_max; $i++)
	{
		if (isset($seourl_table[$i]))
		{
			$seourl_string .= "'" . $seourl_table[$i] . "'";
			$layout_string .= "[";
			for ($j = 0; $j < $layouts_max; $j++)
			{
				$layout_string .= isset($layout_table[$i][$j]) ? 1 : 0;
				if ($j != $layouts_max - 1)
				{
					$layout_string .= ",";
				}
			}
			$layout_string .= "]";
			if ($i != $games_max - 1)
			{
				$seourl_string .= ",";
				$layout_string .= ",";
			}
			$seourl_string .= "//" . ($i+1) . "\n";
			$layout_string .= "//" . ($i+1) . "\n";
		}
	}
	for ($i = 0; $i < $layouts_max; $i++)
	{
		if (isset($style_table[$i]))
		{
			$style_string .= "[";
			for ($j = 0; $j < $styles_max; $j++)
			{
				$style_string .= isset($style_table[$i][$j]) ? 1 : 0;
				if ($j != $styles_max - 1)
				{
					$style_string .= ",";
				}
			}
			$style_string .= "]";
			if ($i != $layouts_max - 1)
			{
				$style_string .= ",";
			}
			$style_string .= "//" . ($i+1) . "\n";
		}
	}

	mysqli_close($con);
?>

//-------------------------------------Main page form controls

var layout_table =
[
<?php echo $layout_string; ?>
]

var seourl_table =
[
<?php echo $seourl_string; ?>
]

var style_table =
[
<?php echo $style_string; ?>
]

var selectList = ['gam','sty','lay']
var lastClicked = {}

function Toggle_Waiting(thisBool)
{
	document.getElementById('waiting').style.display = thisBool ? 'block' : 'none'
}

function Set_Layout(thisindex, thiselement)
{
	if (thiselement.className == 'acc_dis')
	{
		return
	}
	var hasSelect = false
	var SelectForm = document.forms['keyboard_select']
	var layouttable = layout_table[thisindex]
	for (var i = 0, n = layouttable.length; i < n; i++)
	{
		var thisselect = document.getElementById('lay_' + i)
		if (thisselect)
		{
			if (layouttable[i] == 1)
			{
				if (thisselect == lastClicked['lay'])
				{
					thisselect.className = 'acc_sel'
					Set_Style(i, thisselect)
					hasSelect = true
				}
				else
				{
					thisselect.className = 'acc_nrm'
				}
			}
			else
			{
				thisselect.className = 'acc_dis'
				if (thisselect == lastClicked['lay'])
				{
					UnSet_Styles()
					document.getElementById('lay_check').style.display = 'none'
					document.getElementById('sty_check').style.display = 'none'
					document.getElementById('fmt_check').style.display = 'none'
					document.getElementById('but_check').style.display = 'none'
					document.getElementById('lay_xmark').style.display = 'block'
					document.getElementById('sty_xmark').style.display = 'block'
					document.getElementById('fmt_xmark').style.display = 'block'
					document.getElementById('but_xmark').style.display = 'block'

					lastClicked['lay'] = null
				}
			}
		}
	}
	SelectForm.seo.value = seourl_table[thisindex]
	if (hasSelect == false)
	{
		SelectForm.lay.value = ''
	}
}

function Set_Style(thisindex, thiselement)
{
	if (thiselement.className == 'acc_dis')
	{
		return
	}
	var hasSelect = false
	var SelectForm = document.forms['keyboard_select']
	var styletable = style_table[thisindex]
	for (var i = 0, n = styletable.length; i < n; i++)
	{
		var thisselect = document.getElementById('sty_' + i)
		if (thisselect)
		{
			if (styletable[i] == 1)
			{
				if (thisselect == lastClicked['sty'])
				{
					thisselect.className = 'acc_sel'
					hasSelect = true
				}
				else
				{
					thisselect.className = 'acc_nrm'
				}
			}
			else
			{
				thisselect.className = 'acc_dis'
				if (thisselect == lastClicked['sty'])
				{
					document.getElementById('sty_check').style.display = 'none'
					document.getElementById('but_check').style.display = 'none'
					document.getElementById('sty_xmark').style.display = 'block'
					document.getElementById('but_xmark').style.display = 'block'
					lastClicked['sty'] = null
				}
			}
		}
	}
	if (hasSelect == false)
	{
		SelectForm.sty.value = ''
	}
}

function UnSet_Styles()
{
	var styletable = style_table[0]
	for (var i = 0, n = styletable.length; i < n; i++)
	{
		var thisselect = document.getElementById('sty_' + i)
		if (thisselect)
		{
			thisselect.className = 'acc_dis'
			if (thisselect == lastClicked['sty'])
			{
				lastClicked['sty'] = null
			}
		}
	}
}

function Set_Select_Value(thisElement)
{
	if (thisElement.className == 'acc_dis')
	{
		return
	}
	var SelectForm = document.forms['keyboard_select']
	var thisInput = thisElement.getAttribute('menu')
	var thisValue = thisElement.getAttribute('value')
	SelectForm[thisInput].value = thisValue
	if (thisInput == 'sty')
	{
		var WarnBox = document.getElementById('submit_warn_wrap')
		WarnBox.className = 'warn_no'
		document.getElementById('fmt_check').style.display = 'block'
		document.getElementById('but_check').style.display = 'block'
		document.getElementById('fmt_xmark').style.display = 'none'
		document.getElementById('but_xmark').style.display = 'none'
	}
	document.getElementById(thisInput + '_check').style.display = 'block'
	document.getElementById(thisInput + '_xmark').style.display = 'none'
}

function Select_Init()
{
	var SelectForm = document.forms['keyboard_select']
	for (var i = 0, n = selectList.length; i < n; i++)
	{
		var thisSelect = selectList[i]
		initMenu(thisSelect)
		SelectForm[thisSelect].value = ''
	}
	Toggle_Waiting(0)
}

function Check_Values_and_Spawn()
{
	var SelectForm = document.forms['keyboard_select']
	var gam_value = SelectForm.gam.value == '' ? null : SelectForm.gam.value
	var sty_value = SelectForm.sty.value == '' ? null : SelectForm.sty.value
	var lay_value = SelectForm.lay.value == '' ? null : SelectForm.lay.value
	var seo_value = SelectForm.seo.value == '' ? null : SelectForm.seo.value
	var fmt_value = getValueFromRadioButton('fmtradio');
	var WarnBox = document.getElementById('submit_warn_wrap')
	if (gam_value && sty_value && lay_value && seo_value)
	{
		WarnBox.className = 'warn_no'
		window.open('keyboard-diagram-' + seo_value + '.php?sty=' + sty_value + '&lay=' + lay_value + '&fmt=' + fmt_value)
	}
	else	
	{
		WarnBox.className = 'warn_yes'
	}
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
