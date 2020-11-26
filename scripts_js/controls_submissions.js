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
//	var gamOption = document.getElementById('gamsub_' + CurrentItem['gam'])
//	gamOption.click()
	Make_Keys()
	Fill_Keys()
	Make_Legends()
	Make_Notes()
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
		targetElement = document.getElementById(thisPrefix + 'sub_' + i)
		if (targetElement.checked)
			CurrentItem[thisPrefix] += thisTable[i][0]
	}
	// keys
	targetElement = document.getElementById('keysub_div')
	var targetScheme = []
	for (var i = 0; i < NumberOfKeys; i++)
	{
		if (i === 0)
		{
			var boolUndefined = 1
			var tempScheme = []
			for (var j = 0, o = CaptionsTable.length; j < o; j++)
			{
				if ((j === 0) || (j === CaptionsTable.length - 1))
					tempScheme[j] = undefined
				else
				{
					var newCaptnInp = document.getElementById('keyexa_captn_' + j)
					var newCaptnVal = newCaptnInp.value
					if (newCaptnVal === '')
						newCaptnVal = undefined
					else
						boolUndefined = 0
					tempScheme[j] = newCaptnVal
				}
			}
			if (boolUndefined === 1)
				targetScheme[i] = undefined
			else
				targetScheme[i] = tempScheme
		}
		else if (!Lay_Table['key'][i])
			targetScheme[i] = undefined
		else
		{
			var newLoBinInp = document.getElementById('keysub_lobin_' + i)
			var newLoGrpInp = document.getElementById('keysub_logrp_' + i)
			var newShBinInp = document.getElementById('keysub_shbin_' + i)
			var newShGrpInp = document.getElementById('keysub_shgrp_' + i)
			var newCoBinInp = document.getElementById('keysub_cobin_' + i)
			var newCoGrpInp = document.getElementById('keysub_cogrp_' + i)
			var newAlBinInp = document.getElementById('keysub_albin_' + i)
			var newAlGrpInp = document.getElementById('keysub_algrp_' + i)
			var newGrBinInp = document.getElementById('keysub_grbin_' + i)
			var newGrGrpInp = document.getElementById('keysub_grgrp_' + i)
			var newExBinInp = document.getElementById('keysub_exbin_' + i)
			var newExGrpInp = document.getElementById('keysub_exgrp_' + i)
			var newImFilInp = document.getElementById('keysub_imfil_' + i)
			var newImUriInp = document.getElementById('keysub_imuri_' + i)
			var newLoBinVal = newLoBinInp.value
			if (newLoBinVal === '')
				newLoBinVal = undefined
			var newLoGrpVal = ClassesTableAbbr[newLoGrpInp.selectedIndex]
			if (newLoGrpVal === 'none')
				newLoGrpVal = undefined
			var newShBinVal = newShBinInp.value
			if (newShBinVal === '')
				newShBinVal = undefined
			var newShGrpVal = ClassesTableAbbr[newShGrpInp.selectedIndex]
			if (newShGrpVal === 'none')
				newShGrpVal = undefined
			var newCoBinVal = newCoBinInp.value
			if (newCoBinVal === '')
				newCoBinVal = undefined
			var newCoGrpVal = ClassesTableAbbr[newCoGrpInp.selectedIndex]
			if (newCoGrpVal === 'none')
				newCoGrpVal = undefined
			var newAlBinVal = newAlBinInp.value
			if (newAlBinVal === '')
				newAlBinVal = undefined
			var newAlGrpVal = ClassesTableAbbr[newAlGrpInp.selectedIndex]
			if (newAlGrpVal === 'none')
				newAlGrpVal = undefined
			var newGrBinVal = newGrBinInp.value
			if (newGrBinVal === '')
				newGrBinVal = undefined
			var newGrGrpVal = ClassesTableAbbr[newGrGrpInp.selectedIndex]
			if (newGrGrpVal === 'none')
				newGrGrpVal = undefined
			var newExBinVal = newExBinInp.value
			if (newExBinVal === '')
				newExBinVal = undefined
			var newExGrpVal = ClassesTableAbbr[newExGrpInp.selectedIndex]
			if (newExGrpVal === 'none')
				newExGrpVal = undefined
			var newImFilVal = newImFilInp.value
			if (newImFilVal === '')
				newImFilVal = undefined
			var newImUriVal = newImUriInp.value
			if (newImUriVal === '')
				newImUriVal = undefined
			targetScheme[i] =
			[
				newLoBinVal,
				newLoGrpVal,
				newShBinVal,
				newShGrpVal,
				newCoBinVal,
				newCoGrpVal,
				newAlBinVal,
				newAlGrpVal,
				newGrBinVal,
				newGrGrpVal,
				newExBinVal,
				newExGrpVal,
				newImFilVal,
				newImUriVal
			]
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
	Gam_Table[CurrentItem['lay']]['key'] = targetScheme
	targetScheme = []
	// legends
	for (var i = 0, n = ClassesTableAbbr.length; i < n; i++)
	{
		var newDescrInp = document.getElementById('legsub_descr_' + i)
		var newDescrVal = newDescrInp.value
		var newDescrIdx = ClassesTableAbbr[i]
		if (newDescrVal !== '')
			targetScheme[i] = [newDescrIdx,newDescrVal]
	}
	Gam_Table[CurrentItem['lay']]['leg'] = targetScheme
	targetScheme = []
	// mouse, joystick, etc.
	for (var thisTarget in NotesCount)
	{
		var targetElement = document.getElementById(thisTarget + 'sub_div')
		var targetChildren = targetElement.childNodes
		// skip the first header row
		for (var i = 1, n = targetChildren.length; i < n; i++)
		{
			var thisChild1 = targetChildren[i].childNodes[1].firstChild
			if (thisChild1)
			{
				var thisValue1 = thisChild1.value
				if (thisValue1 !== '')
				{
					var thisText = thisValue1
					if (thisTarget !== 'add')
					{
						var thisChild2 = targetChildren[i].childNodes[2].firstChild
						thisText += ' = ' + thisChild2.value
					}
					targetScheme.push(thisText)
				}
			}
		}
		Gam_Table[CurrentItem['lay']][thisTarget] = targetScheme
		targetScheme = []
	}
	// misc
	var thisName = document.getElementById('namsub_div').value
	var thisAuth = document.getElementById('autsub_div').value
	Gam_Table[CurrentItem['lay']]['gam'] = thisName
	Gam_Table[CurrentItem['lay']]['aut'] = thisAuth
	AuthorEmail = document.getElementById('emlsub_div').value
	AuthorApprv = document.getElementById('apvsub_div').value
}
function Spawn_Preview()
{
	var thisString = 'keydiagramkeys.htm???Preview;' + CurrentItem['sty'] + ';' + CurrentItem['lay'] + ';' + CurrentItem['eff'] + ';'
	window.open(thisString, 'KeyDiagramKeys')
}
function Submit_Scheme()
{
	var submitText = '\nsLay = \'' + CurrentItem['lay'] + '\'\n' +
	'Gam_Table[layString][\'gam\'] = ' + '\'' + Gam_Table[CurrentItem['lay']]['gam'] + '\'\n' +
	'Gam_Table[layString][\'aut\'] = ' + '\'' + Gam_Table[CurrentItem['lay']]['aut'] + '\'\n' +
	'Gam_Table[layString][\'key\'] =\n' + '[\n'
	var targetScheme = Gam_Table[CurrentItem['lay']]['key']
	for (var i = 0, n = targetScheme.length; i < n; i++)
	{
		var thisScheme = targetScheme[i]
		if (thisScheme)
		{
			submitText += '['
			for (var j = 0, o = thisScheme.length; j < o; j++)
			{
				var thisText = thisScheme[j]
				if (thisText)
					submitText += '\'' + thisText + '\','
				else
					submitText += ','
			}
			submitText = submitText.replace(/\,$/,'')
			submitText += ']'
		}
		submitText += ',\n'
	}
	submitText = submitText.replace(/\,\n$/,'\n')
	submitText += ']\n'
	targetScheme = Gam_Table[CurrentItem['lay']]['leg']
	submitText += 'Gam_Table[layString][\'leg\'] =\n[\n'
	for (var i = 0, n = targetScheme.length; i < n; i++)
	{
		submitText += '['
		var thisScheme = targetScheme[i]
		for (var j = 0, o = thisScheme.length; j < o; j++)
			submitText += '\'' + thisScheme[j] + '\','
		submitText = submitText.replace(/\,$/,'')
		submitText += '],\n'
	}
	submitText = submitText.replace(/\,\n$/,'\n')
	submitText += ']\n'
	for (var thisTarget in NotesCount)
	{
		targetScheme = Gam_Table[CurrentItem['lay']][thisTarget]
		submitText += 'Gam_Table[layString][\'' + thisTarget + '\'] =\n[\n'
		for (var i = 0, n = targetScheme.length; i < n; i++)
			submitText += '\'' + targetScheme[i] + '\',\n'
		submitText = submitText.replace(/\,\n$/,'\n')
		submitText += ']\n'
	}
	submitText = submitText.replace(/=\n\[\n\]/g,'= []')
	document.getElementById('endsub_div').value = submitText
}
