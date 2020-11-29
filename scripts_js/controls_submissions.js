// Copyright (C) 2009  Michael Horvath

// This library is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.

// This library is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.

// You should have received a copy of the GNU Lesser General Public
// License along with this library; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA


function Init_Submissions()
{
	// games, styles and layouts lists
	for (var j = 0, o = SelectsTable.length; j < o; j++)
	{
		var thisPrefix = SelectsTable[j]
		var targetElement = document.getElementById(thisPrefix + 'sub_div')
		var thisList1 = Dat_Table[thisPrefix]
		var thisList2 = Grp_Table[thisPrefix]
		for (var i = 0, n = thisList2.length; i < n; i++)
		{
			var thisOptgroup = document.createElement('optgroup')
			thisOptgroup.setAttribute('label', thisList2[i])
			for (var k = 0, p = thisList1.length; k < p; k++)
			{
				var thisItem = thisList1[k]
				if (thisItem)
				{
					var thisShort = thisItem[0]
					var thisLong = thisItem[1]
					var thisDefault = thisItem[2]
					var thisGroup = thisItem[3]
					if (thisGroup === i)
					{
						var thisOption = document.createElement('option')
						var thisMark = thisDefault ? ' ✦' : ''
						var thisText = document.createTextNode(thisLong + thisMark)
						thisOption.setAttribute('optindex', k)
						thisOption.setAttribute('optprefix', thisPrefix)
						thisOption.setAttribute('optshort', thisShort)
						thisOption.id = thisPrefix + 'sub_' + k
						thisOption.addEventListener('click', Set_List_Item)
						if (thisDefault === 1)
						{
							thisOption.selected = true
							CurrentItem[thisPrefix] = k
						}
						else
						{
							thisOption.selected = false
						}
						thisOption.appendChild(thisText)
						thisOptgroup.appendChild(thisOption)
					}
				}
			}
			targetElement.appendChild(thisOptgroup)
		}
	}

	Update_Bindings_Table_Step_One()

	// effects checkboxes
	var thisPrefix = 'eff'
	var targetElement = document.getElementById(thisPrefix + 'sub_div')
	var thisList = Dat_Table[thisPrefix]
	CurrentItem[thisPrefix] = 0
	for (var i = 0, n = thisList.length; i < n; i++)
	{
		var thisItem = thisList[i]
		var thisContainr = document.createElement('div')
		var thisCheckbox = document.createElement('input')
		var thisDescript = document.createTextNode(thisItem[1])
		thisCheckbox.setAttribute('type', 'checkbox')
		thisCheckbox.id = 'effsub_' + i
		thisCheckbox.onclick = Check_Me
		if (thisItem[0] === 1)
		{
			thisCheckbox.setAttribute('checked', 'checked')
			CurrentItem[thisPrefix] += thisItem[0]
		}
		thisContainr.appendChild(thisCheckbox)
		thisContainr.appendChild(thisDescript)
		targetElement.appendChild(thisContainr)
	}
}
function Load_Script(thisOption)
{
	var thisIndex = thisOption.getAttribute('optindex')
	var thisPrefix = thisOption.getAttribute('optprefix')
	var thisShort = thisOption.getAttribute('optshort')
	var thisScript = document.createElement('script')
	var thisHead = document.getElementsByTagName('head')[0]
//	var oldScript = document.getElementById(thisPrefix + '_script')
//	thisHead.removeChild(oldScript)
	CurrentItem[thisPrefix] = thisIndex
	thisScript.setAttribute('type', 'text/javascript')
	thisScript.setAttribute('src', thisPrefix + '_' + thisShort + '.js')
	thisScript.setAttribute('charset', 'UTF-8')
//	thisScript.id = thisPrefix + '_script'
	thisHead.appendChild(thisScript)
	return thisScript
}
function Set_List_Item(e)
{
	var thisOption = getElementByEvent(e)
	var thisIndex = thisOption.getAttribute('optindex')
	var thisPrefix = thisOption.getAttribute('optprefix')
//	var thisShort = thisOption.getAttribute('optshort')
	CurrentItem[thisPrefix] = thisIndex

	if (thisPrefix === 'gam')
	{
		var lay_first = false
		var lay_current = parseInt(CurrentItem['lay'], 10)
		var	lay_layout_list = Dat_Table['lay']
		var gam_layout_list = Dat_Table['gam'][thisIndex][4]
		for (var i = 0, n = lay_layout_list.length; i < n; i++)
		{
			var thisLayout = lay_layout_list[i]
			if (thisLayout)
			{
				var targetLayout = document.getElementById('laysub_' + i)
				targetLayout.selected = false
				targetLayout.disabled = true
				for (var j = 0, o = gam_layout_list.length; j < o; j++)
				{
					var p = gam_layout_list[j]
					if (i === p)
					{
						targetLayout.disabled = false
						if ((lay_first === false) || (i === lay_current))
						{
							targetLayout.selected = true
							CurrentItem['lay'] = i
							lay_first = true
						}
						break
					}
				}
			}
		}
	}
}
function Update_Bindings_Table_Step_One()
{
	var gamOption = document.getElementById('gamsub_' + CurrentItem['gam'])
	var gamScript = Load_Script(gamOption)
	gamScript.onload = Update_Bindings_Table_Step_Two
}
function Update_Bindings_Table_Step_Two()
{
	var layOption = document.getElementById('laysub_' + CurrentItem['lay'])
	var layScript = Load_Script(layOption)
	layScript.onload = Update_Bindings_Table_Step_Three
}
function Update_Bindings_Table_Step_Three()
{
	var layString = Dat_Table['lay'][CurrentItem['lay']][0]
	Make_Keys()
	Fill_Keys()
	Make_Legends()
	Make_Notes()
	document.getElementById('titsub_div').value = Gam_Table[layString]['tit']
	document.getElementById('autsub_div').value = Gam_Table[layString]['aut']
//	var gamOption = document.getElementById('gamsub_' + CurrentItem['gam'])
//	gamOption.click()
}
function Add_Field(thisTarget)
{
	if (NotesCount[thisTarget] > 31)
		return
	NotesCount[thisTarget] += 1
	var targetElement = document.getElementById(thisTarget + 'sub_div')
	var newRow = document.createElement('tr')
	var newCel0 = document.createElement('th')
	var newTxt0 = document.createTextNode(NotesCount[thisTarget])
	newCel0.appendChild(newTxt0)
	newRow.appendChild(newCel0)
	if (thisTarget !== 'add')
	{
		var newCel1 = document.createElement('td')
		var newInp1 = document.createElement('input')
		newInp1.setAttribute('type', 'text')
		newInp1.setAttribute('size', '30')
		newInp1.id = thisTarget + 'sub_inp1_' + NotesCount[thisTarget]
		newCel1.appendChild(newInp1)
		newRow.appendChild(newCel1)
	}
	var newCel2 = document.createElement('td')
	var newInp2 = document.createElement('input')
	newInp2.setAttribute('type', 'text')
	newInp2.setAttribute('size', '40')
	newInp2.id = thisTarget + 'sub_inp2_' + NotesCount[thisTarget]
	newCel2.appendChild(newInp2)
	newRow.appendChild(newCel2)
	targetElement.appendChild(newRow)
}

function Rem_Field(thisTarget)
{
	if (NotesCount[thisTarget] <= 0)
		return
	NotesCount[thisTarget] -= 1
	var targetElement = document.getElementById(thisTarget + 'sub_div')
	targetElement.removeChild(targetElement.lastChild)
}
function Parse_Fields()
{
	// styles and layouts
	for (var j = 0, o = SelectsTable.length; j < o; j++)
	{
		var thisPrefix = SelectsTable[j]
		var targetElement = document.getElementById(thisPrefix + 'sub_div')
		var thisOption = targetElement.options[targetElement.selectedIndex]
		CurrentItem[thisPrefix] = thisOption.getAttribute('optindex')
	}
	// effects
	var thisPrefix = 'eff'
	var thisTable = Dat_Table[thisPrefix]
	CurrentItem[thisPrefix] = 0
	for (var i = 0, n = thisTable.length; i < n; i++)
	{
		var targetElement = document.getElementById(thisPrefix + 'sub_' + i)
		if (targetElement.checked)
			CurrentItem[thisPrefix] += thisTable[i][0]
	}
	// keys
	var targetScheme = []
	var layString = Dat_Table['lay'][CurrentItem['lay']][0]
	var thisGame = Gam_Table[layString]['key']
	for (var i = 0, n = thisGame.length; i < n; i++)
	{
		if (!Lay_Table['key'][i])
			targetScheme[i] = undefined
		else
		{
			var newLoTxtInp = document.getElementById('keysub_lotxt_' + i)
			var newLoGrpInp = document.getElementById('keysub_logrp_' + i)
			var newShTxtInp = document.getElementById('keysub_shtxt_' + i)
			var newShGrpInp = document.getElementById('keysub_shgrp_' + i)
			var newCoTxtInp = document.getElementById('keysub_cotxt_' + i)
			var newCoGrpInp = document.getElementById('keysub_cogrp_' + i)
			var newAlTxtInp = document.getElementById('keysub_altxt_' + i)
			var newAlGrpInp = document.getElementById('keysub_algrp_' + i)
			var newGrTxtInp = document.getElementById('keysub_grtxt_' + i)
			var newGrGrpInp = document.getElementById('keysub_grgrp_' + i)
			var newExTxtInp = document.getElementById('keysub_extxt_' + i)
			var newExGrpInp = document.getElementById('keysub_exgrp_' + i)
			var newImFilInp = document.getElementById('keysub_imfil_' + i)
			var newImUriInp = document.getElementById('keysub_imuri_' + i)

			var newLoTxtVal = Sanitize_Text(newLoTxtInp.value)
			if (newLoTxtVal === '')
				newLoTxtVal = undefined
			var newLoGrpVal = newLoGrpInp.selectedIndex - 1
			if (newLoGrpVal === -1)
				newLoGrpVal = undefined
			var newShTxtVal = Sanitize_Text(newShTxtInp.value)
			if (newShTxtVal === '')
				newShTxtVal = undefined
			var newShGrpVal = newShGrpInp.selectedIndex - 1
			if (newShGrpVal === -1)
				newShGrpVal = undefined
			var newCoTxtVal = Sanitize_Text(newCoTxtInp.value)
			if (newCoTxtVal === '')
				newCoTxtVal = undefined
			var newCoGrpVal = newCoGrpInp.selectedIndex - 1
			if (newCoGrpVal === -1)
				newCoGrpVal = undefined
			var newAlTxtVal = Sanitize_Text(newAlTxtInp.value)
			if (newAlTxtVal === '')
				newAlTxtVal = undefined
			var newAlGrpVal = newAlGrpInp.selectedIndex - 1
			if (newAlGrpVal === -1)
				newAlGrpVal = undefined
			var newGrTxtVal = Sanitize_Text(newGrTxtInp.value)
			if (newGrTxtVal === '')
				newGrTxtVal = undefined
			var newGrGrpVal = newGrGrpInp.selectedIndex - 1
			if (newGrGrpVal === -1)
				newGrGrpVal = undefined
			var newExTxtVal = Sanitize_Text(newExTxtInp.value)
			if (newExTxtVal === '')
				newExTxtVal = undefined
			var newExGrpVal = newExGrpInp.selectedIndex - 1
			if (newExGrpVal === -1)
				newExGrpVal = undefined
			var newImFilVal = newImFilInp.value
			if (newImFilVal === '')
				newImFilVal = undefined
			var newImUriVal = newImUriInp.value
			if (newImUriVal === '')
				newImUriVal = undefined
			targetScheme[i] =
			[
				newLoTxtVal,
				newLoGrpVal,
				newShTxtVal,
				newShGrpVal,
				newCoTxtVal,
				newCoGrpVal,
				newAlTxtVal,
				newAlGrpVal,
				newGrTxtVal,
				newGrGrpVal,
				newExTxtVal,
				newExGrpVal,
				newImFilVal,
				newImUriVal
			]
			// if all the cells in the row are undefined, might as well make the whole row undefined
			var boolUndefined = 1
			for (var j = 0, o = targetScheme[i].length; j < o; j++)
			{
				if (targetScheme[i][j])
				{
					boolUndefined = 0
					break
				}
			}
			if (boolUndefined === 1)
				targetScheme[i] = undefined
		}
	}
	Gam_Table[layString]['key'] = targetScheme
	// legends #1
	targetScheme = []
	for (var i = 0; i < 6; i++)
	{
		var newDescrInp = document.getElementById('keyexa_captn_' + i)
		var newDescrVal = newDescrInp.value
		if (newDescrVal === '')
			targetScheme[i] = null
		else
			targetScheme[i] = newDescrVal
	}
	Lay_Table['leg'] = targetScheme
	// legends #2
	targetScheme = []
	for (var i = 0; i < 12; i++)
	{
		var newDescrInp = document.getElementById('legsub_descr_' + i)
		var newDescrVal = newDescrInp.value
		if (newDescrVal !== '')
			targetScheme.push([i, newDescrVal])
	}
	Gam_Table[layString]['leg'] = targetScheme
	// notes
	for (var thisTarget in NotesCount)
	{
		targetScheme = []
		var targetElement = document.getElementById(thisTarget + 'sub_div')
		var targetChildren = targetElement.childNodes
		// skip the first header row
		for (var i = 1, n = targetChildren.length; i < n; i++)
		{
			var thisChild1 = targetChildren[i].childNodes[1]
			var thisChild2 = targetChildren[i].childNodes[2]
			if (thisChild2)
			{
				var thisValue1 = thisChild1.firstChild.value
				var thisValue2 = thisChild2.firstChild.value
				if ((thisValue1 !== '') && (thisValue2 !== ''))
					targetScheme.push([thisValue1, thisValue2])
			}
			else if (thisChild1)
			{
				var thisValue1 = thisChild1.firstChild.value
				if (thisValue1 !== '')
					targetScheme.push(thisValue1)
			}

		}
		Gam_Table[layString][thisTarget] = targetScheme
	}
	// misc
	Gam_Table[layString]['tit'] = Sanitize_Text(document.getElementById('titsub_div').value)
	Gam_Table[layString]['aut'] = Sanitize_Text(document.getElementById('autsub_div').value)
	AuthorEmail = document.getElementById('emlsub_div').value
	AuthorApprv = document.getElementById('apvsub_div').value
}
function Sanitize_Text(inText)
{
	return inText.replaceAll('\\','\\\\').replaceAll('\'','\\\'').replaceAll('\\\\n','\\n').replaceAll('\\\\t','\\t')
}
function Spawn_Preview()
{
	var thisString = 'keyboard_diagram.html???-1;' + CurrentItem['sty'] + ';' + CurrentItem['lay'] + ';' + CurrentItem['eff'] + ';'
	window.open(thisString, 'KeyDiagramKeys')
}
function Submit_Scheme()
{
	var layString = Dat_Table['lay'][CurrentItem['lay']][0]
	var submitText = 'layString = \'' + layString + '\'\n' +
	'Gam_Table[layString] = {}\n' +
	'Gam_Table[layString][\'tit\'] = ' + '\'' + Gam_Table[layString]['tit'] + '\'\n' +
	'Gam_Table[layString][\'aut\'] = ' + '\'' + Gam_Table[layString]['aut'] + '\'\n' +
	'Gam_Table[layString][\'key\'] =\n' + '[\n'
	// keys
	var targetScheme = Gam_Table[layString]['key']
	for (var i = 0, n = targetScheme.length; i < n; i++)
	{
		var thisScheme = targetScheme[i]
		if (thisScheme)
		{
			submitText += '\t['
			for (var j = 0, o = thisScheme.length; j < o; j++)
			{
				var thisText = thisScheme[j]
				if ((thisText) || (thisText === 0))
				{
					if (Number.isInteger(thisText))
						submitText += thisText + ','
					else
						submitText += '\'' + thisText + '\','
				}
				else
				{
					submitText += 'null,'
				}
			}
			submitText = submitText.replace(/\,$/,'')
			submitText += '],\n'
		}
		else
		{
			submitText += '\tnull,\n'
		}
	}
	submitText = submitText.replace(/\,\n$/,'\n')
	submitText += ']\n'
	// legends
	targetScheme = Gam_Table[layString]['leg']
	submitText += 'Gam_Table[layString][\'leg\'] =\n[\n'
	for (var i = 0, n = targetScheme.length; i < n; i++)
	{
		var thisScheme = targetScheme[i]
		submitText += '\t[' + thisScheme[0] + ',\'' + thisScheme[1] + '\'],\n'
	}
	submitText = submitText.replace(/\,\n$/,'\n')
	submitText += ']\n'
	// notes
	for (var thisTarget in NotesCount)
	{
		targetScheme = Gam_Table[layString][thisTarget]
		submitText += 'Gam_Table[layString][\'' + thisTarget + '\'] =\n[\n'
		for (var i = 0, n = targetScheme.length; i < n; i++)
		{
			var thisNum = targetScheme[i].length
			if (thisNum === 2)
				submitText += '\t[\'' + targetScheme[i][0] + '\',\'' + targetScheme[i][1] + '\'],\n'
			else
				submitText += '\t\'' + targetScheme[i] + '\',\n'
		}
		submitText = submitText.replace(/\,\n$/,'\n')
		submitText += ']\n'
	}
	submitText = submitText.replace(/=\n\[\n\]/g,'= []')
	document.getElementById('endsub_div').innerHTML = submitText
	alert('Email submissions not implemented yet.')
}
