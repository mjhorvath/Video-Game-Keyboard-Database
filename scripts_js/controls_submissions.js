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
	// legends table #2
	targetElement = document.getElementById('legsub_div')
	var newTableRow = document.createElement('tr')
	var newClassCel = document.createElement('th')
	var newDescrCel = document.createElement('th')
	var newClassTxt = document.createTextNode('Group')
	var newDescrTxt = document.createTextNode('Description')
	newClassCel.appendChild(newClassTxt)
	newDescrCel.appendChild(newDescrTxt)
	newTableRow.appendChild(newClassCel)
	newTableRow.appendChild(newDescrCel)
	targetElement.appendChild(newTableRow)
	// skip the first null color
	for (var i = 1, n = ClassesTableFull.length; i < n; i++)
	{
		newTableRow = document.createElement('tr')
		newClassCel = document.createElement('th')
		newDescrCel = document.createElement('td')
		newClassTxt = document.createTextNode(ClassesTableFull[i])
		newDescrInp = document.createElement('input')
		newDescrInp.id = 'legsub_descr_' + i
		newDescrInp.setAttribute('type', 'text')
		newDescrInp.setAttribute('size', '40')
		newClassCel.className = newClassCel.className + ' ' + ClassesTableAbbr[i]
		newClassCel.appendChild(newClassTxt)
		newDescrCel.appendChild(newDescrInp)
		newTableRow.appendChild(newClassCel)
		newTableRow.appendChild(newDescrCel)
		targetElement.appendChild(newTableRow)
	}
	// legends table #1
	targetElement = document.getElementById('exasub_div')
	newTableRow = document.createElement('tr')
	newDescrCel = document.createElement('th')
	var newCaptnCel = document.createElement('th')
	newDescrTxt = document.createTextNode('Action')
	var newCaptnTxt = document.createTextNode('Description')
	newDescrCel.appendChild(newDescrTxt)
	newCaptnCel.appendChild(newCaptnTxt)
	newTableRow.appendChild(newDescrCel)
	newTableRow.appendChild(newCaptnCel)
	targetElement.appendChild(newTableRow)
	for (var i = 0, n = CaptionsTableR.length; i < n; i++)
	{
		newTableRow = document.createElement('tr')
		newDescrCel = document.createElement('th')
		newCaptnCel = document.createElement('td')
		newDescrTxt = document.createTextNode(CaptionsTableL[i])
		var newCaptnInp = document.createElement('input')
		newCaptnInp.setAttribute('type', 'text')
		newCaptnInp.value = CaptionsTableR[i]
		newCaptnInp.id = 'keyexa_captn_' + i
		newDescrCel.appendChild(newDescrTxt)
		newCaptnCel.appendChild(newCaptnInp)
	   	newTableRow.appendChild(newDescrCel)
		newTableRow.appendChild(newCaptnCel)
		targetElement.appendChild(newTableRow)
	}
	// mouse, joystick, etc. lists
	for (var thisTarget in NotesCount)
	{
		var targetElement = document.getElementById(thisTarget + 'sub_div')
		var newRow = document.createElement('tr')
		var newCel0 = document.createElement('th')
		newRow.appendChild(newCel0)
		if (thisTarget !== 'add')
		{
			var newCel1 = document.createElement('th')
			var newTxt1 = document.createTextNode('Command')
			newCel1.appendChild(newTxt1)
			newRow.appendChild(newCel1)
			var newCel2 = document.createElement('th')
			var newTxt2 = document.createTextNode('Description')
			newCel2.appendChild(newTxt2)
			newRow.appendChild(newCel2)
		}
		else
		{
			var newCel2 = document.createElement('th')
			var newTxt2 = document.createTextNode('Note')
			newCel2.appendChild(newTxt2)
			newRow.appendChild(newCel2)
		}
		targetElement.appendChild(newRow)
		for (var i = 0; i < 3; i++)
			Add_Field(thisTarget)
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

// hopefully this is finally fixed
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
	var gamOption = document.getElementById('gamsub_' + CurrentItem['gam'])
	gamOption.click()
	Make_Keys()
	Fill_Keys()
}

function Change_Color(e)
{
	var thisSelect = getElementByEvent(e)
	var thisIndex = thisSelect.selectedIndex
	var abbr_color = ClassesTableAbbr[thisIndex]
	thisSelect.className = abbr_color
}
function Fill_Keys()
{
	var layString = Dat_Table['lay'][CurrentItem['lay']][0]
	var thisGame = Gam_Table[layString]['key']
	// regular rows
	for (var i = 0, n = thisGame.length; i < n; i++)
	{
		var thisRow = thisGame[i]
		if (thisRow)
		{
			// Normal Key, Group, SHIFT + Key, Group, CTRL + Key, Group, ALT + Key, Group, ALTGR + Key, Group, Extra Action, Group, Icon File, Icon URI
			Validate_Keys_Inp(thisRow[0], 'keysub_lobin_' + i)			// column 1
			Validate_Keys_Sel(thisRow[1], 'keysub_losel_' + i)			// column 2
			Validate_Keys_Inp(thisRow[2], 'keysub_shbin_' + i)			// column 3
			Validate_Keys_Sel(thisRow[3], 'keysub_shsel_' + i)			// column 4
			Validate_Keys_Inp(thisRow[4], 'keysub_cobin_' + i)			// column 5
			Validate_Keys_Sel(thisRow[5], 'keysub_cosel_' + i)			// column 6
			Validate_Keys_Inp(thisRow[6], 'keysub_albin_' + i)			// column 7
			Validate_Keys_Sel(thisRow[7], 'keysub_alsel_' + i)			// column 8
			Validate_Keys_Inp(thisRow[8], 'keysub_grbin_' + i)			// column 9
			Validate_Keys_Sel(thisRow[9], 'keysub_grsel_' + i)			// column 10
			Validate_Keys_Inp(thisRow[10], 'keysub_exbin_' + i)			// column 11
			Validate_Keys_Sel(thisRow[11], 'keysub_exsel_' + i)			// column 12
			Validate_Keys_Inp(thisRow[12], 'keysub_imfil_' + i)			// column 13
			Validate_Keys_Inp(thisRow[13], 'keysub_imuri_' + i)			// column 14
		}
	}
}
function Validate_Keys_Inp(thisVal, thisID)
{
	if ((typeof thisVal === 'string') && (thisVal.length != ''))
	{
		document.getElementById(thisID).value = thisVal.replace('\n','\\n').replace('\t','\\t')
	}
}
function Validate_Keys_Sel(thisVal, thisID)
{
	// remember the first item in the ClassesTableAbbr table is 'non' which has an index of 0
	// also remember that the SQL database starts with an index of 1 but the JS data starts with 0
	if (Number.isInteger(thisVal) && (thisVal >= 0) && (thisVal <= 5))
	{
		var abbr_color = ClassesTableAbbr[thisVal + 1]
		document.getElementById(thisID).selectedIndex = thisVal + 1
		document.getElementById(thisID).className = abbr_color
	}
}
function Make_Keys()
{
	NumberOfKeys = Lay_Table['key'].length
	var targetElement = document.getElementById('keysub_div')
	while (targetElement.hasChildNodes())
		targetElement.removeChild(targetElement.lastChild)
	// header row
	var newTableRow = document.createElement('tr')
	var newDescrHed = document.createElement('th')
	var newNumbrHed = document.createElement('th')
	var newLoBinHed = document.createElement('th')
	var newLoSelHed = document.createElement('th')
	var newShBinHed = document.createElement('th')
	var newShSelHed = document.createElement('th')
	var newCoBinHed = document.createElement('th')
	var newCoSelHed = document.createElement('th')
	var newAlBinHed = document.createElement('th')
	var newAlSelHed = document.createElement('th')
	var newGrBinHed = document.createElement('th')
	var newGrSelHed = document.createElement('th')
	var newExBinHed = document.createElement('th')
	var newExSelHed = document.createElement('th')
	var newImFilHed = document.createElement('th')
	var newImUriHed = document.createElement('th')
	var newDescrTxt = document.createTextNode(ActionsTable[0])
	var newNumbrTxt = document.createTextNode(ActionsTable[1])
	var newLoBinTxt = document.createTextNode(ActionsTable[2])
	var newLoSelTxt = document.createTextNode(ActionsTable[3])
	var newShBinTxt = document.createTextNode(ActionsTable[4])
	var newShSelTxt = document.createTextNode(ActionsTable[5])
	var newCoBinTxt = document.createTextNode(ActionsTable[6])
	var newCoSelTxt = document.createTextNode(ActionsTable[7])
	var newAlBinTxt = document.createTextNode(ActionsTable[8])
	var newAlSelTxt = document.createTextNode(ActionsTable[9])
	var newGrBinTxt = document.createTextNode(ActionsTable[10])
	var newGrSelTxt = document.createTextNode(ActionsTable[11])
	var newExBinTxt = document.createTextNode(ActionsTable[12])
	var newExSelTxt = document.createTextNode(ActionsTable[13])
	var newImFilTxt = document.createTextNode(ActionsTable[14])
	var newImUriTxt = document.createTextNode(ActionsTable[15])
	newDescrHed.appendChild(newDescrTxt)
	newNumbrHed.appendChild(newNumbrTxt)
	newLoBinHed.appendChild(newLoBinTxt)
	newLoSelHed.appendChild(newLoSelTxt)
	newShBinHed.appendChild(newShBinTxt)
	newShSelHed.appendChild(newShSelTxt)
	newCoBinHed.appendChild(newCoBinTxt)
	newCoSelHed.appendChild(newCoSelTxt)
	newAlBinHed.appendChild(newAlBinTxt)
	newAlSelHed.appendChild(newAlSelTxt)
	newGrBinHed.appendChild(newGrBinTxt)
	newGrSelHed.appendChild(newGrSelTxt)
	newExBinHed.appendChild(newExBinTxt)
	newExSelHed.appendChild(newExSelTxt)
	newImFilHed.appendChild(newImFilTxt)
	newImUriHed.appendChild(newImUriTxt)
	newTableRow.appendChild(newDescrHed)
	newTableRow.appendChild(newNumbrHed)
	newTableRow.appendChild(newLoBinHed)
	newTableRow.appendChild(newLoSelHed)
	newTableRow.appendChild(newShBinHed)
	newTableRow.appendChild(newShSelHed)
	newTableRow.appendChild(newCoBinHed)
	newTableRow.appendChild(newCoSelHed)
	newTableRow.appendChild(newAlBinHed)
	newTableRow.appendChild(newAlSelHed)
	newTableRow.appendChild(newGrBinHed)
	newTableRow.appendChild(newGrSelHed)
	newTableRow.appendChild(newExBinHed)
	newTableRow.appendChild(newExSelHed)
	newTableRow.appendChild(newImFilHed)
	newTableRow.appendChild(newImUriHed)
	targetElement.appendChild(newTableRow)
	// regular rows
	for (var i = 0; i < NumberOfKeys; i++)
	{
		var this_row = Lay_Table['key'][i]
		if (this_row)
		{
			var black_label = this_row[5]
			if (this_row[4])
				black_label += ' (' + this_row[4] + ')'
			var newTableRow = document.createElement('tr')
			var newDescrCel = document.createElement('th')
			var newNumbrCel = document.createElement('th')
			var newLoBinCel = document.createElement('td')
			var newLoSelCel = document.createElement('td')
			var newShBinCel = document.createElement('td')
			var newShSelCel = document.createElement('td')
			var newCoBinCel = document.createElement('td')
			var newCoSelCel = document.createElement('td')
			var newAlBinCel = document.createElement('td')
			var newAlSelCel = document.createElement('td')
			var newGrBinCel = document.createElement('td')
			var newGrSelCel = document.createElement('td')
			var newExBinCel = document.createElement('td')
			var newExSelCel = document.createElement('td')
			var newImFilCel = document.createElement('td')
			var newImUriCel = document.createElement('td')
			var newDescrInp = document.createTextNode(black_label)
			var newNumbrInp = document.createTextNode(i+1)
			var newLoBinInp = document.createElement('input')
			var newLoSelInp = document.createElement('select')
			var newShBinInp = document.createElement('input')
			var newShSelInp = document.createElement('select')
			var newCoBinInp = document.createElement('input')
			var newCoSelInp = document.createElement('select')
			var newAlBinInp = document.createElement('input')
			var newAlSelInp = document.createElement('select')
			var newGrBinInp = document.createElement('input')
			var newGrSelInp = document.createElement('select')
			var newExBinInp = document.createElement('input')
			var newExSelInp = document.createElement('select')
			var newImFilInp = document.createElement('input')
			var newImUriInp = document.createElement('input')
			newLoBinInp.id = 'keysub_lobin_' + i
			newLoSelInp.id = 'keysub_losel_' + i
			newShBinInp.id = 'keysub_shbin_' + i
			newShSelInp.id = 'keysub_shsel_' + i
			newCoBinInp.id = 'keysub_cobin_' + i
			newCoSelInp.id = 'keysub_cosel_' + i
			newAlBinInp.id = 'keysub_albin_' + i
			newAlSelInp.id = 'keysub_alsel_' + i
			newGrBinInp.id = 'keysub_grbin_' + i
			newGrSelInp.id = 'keysub_grsel_' + i
			newExBinInp.id = 'keysub_exbin_' + i
			newExSelInp.id = 'keysub_exsel_' + i
			newImFilInp.id = 'keysub_imfil_' + i
			newImUriInp.id = 'keysub_imuri_' + i
			newLoBinInp.setAttribute('type', 'text')
			newShBinInp.setAttribute('type', 'text')
			newCoBinInp.setAttribute('type', 'text')
			newAlBinInp.setAttribute('type', 'text')
			newGrBinInp.setAttribute('type', 'text')
			newExBinInp.setAttribute('type', 'text')
			newImFilInp.setAttribute('type', 'text')
			newImUriInp.setAttribute('type', 'text')
			newLoSelInp.style.width = '*'
			newShSelInp.style.width = '*'
			newCoSelInp.style.width = '*'
			newAlSelInp.style.width = '*'
			newGrSelInp.style.width = '*'
			newExSelInp.style.width = '*'
			for (var j = 0, o = ClassesTableFull.length; j < o; j++)
			{
				var full_color = ClassesTableFull[j]
				var abbr_color = ClassesTableAbbr[j]
				var newLoSelOpt = document.createElement('option')
				var newShSelOpt = document.createElement('option')
				var newCoSelOpt = document.createElement('option')
				var newAlSelOpt = document.createElement('option')
				var newGrSelOpt = document.createElement('option')
				var newExSelOpt = document.createElement('option')
				var newLoSelNod = document.createTextNode(full_color)
				var newShSelNod = document.createTextNode(full_color)
				var newCoSelNod = document.createTextNode(full_color)
				var newAlSelNod = document.createTextNode(full_color)
				var newGrSelNod = document.createTextNode(full_color)
				var newExSelNod = document.createTextNode(full_color)
				newLoSelOpt.className = abbr_color
				newShSelOpt.className = abbr_color
				newCoSelOpt.className = abbr_color
				newAlSelOpt.className = abbr_color
				newGrSelOpt.className = abbr_color
				newExSelOpt.className = abbr_color
				newLoSelInp.onchange = Change_Color
				newShSelInp.onchange = Change_Color
				newCoSelInp.onchange = Change_Color
				newAlSelInp.onchange = Change_Color
				newGrSelInp.onchange = Change_Color
				newExSelInp.onchange = Change_Color
				newLoSelOpt.appendChild(newLoSelNod)
				newShSelOpt.appendChild(newShSelNod)
				newCoSelOpt.appendChild(newCoSelNod)
				newAlSelOpt.appendChild(newAlSelNod)
				newGrSelOpt.appendChild(newGrSelNod)
				newExSelOpt.appendChild(newExSelNod)
				newLoSelInp.appendChild(newLoSelOpt)
				newShSelInp.appendChild(newShSelOpt)
				newCoSelInp.appendChild(newCoSelOpt)
				newAlSelInp.appendChild(newAlSelOpt)
				newGrSelInp.appendChild(newGrSelOpt)
				newExSelInp.appendChild(newExSelOpt)
			}
			newDescrCel.appendChild(newDescrInp)
			newNumbrCel.appendChild(newNumbrInp)
			newLoBinCel.appendChild(newLoBinInp)
			newLoSelCel.appendChild(newLoSelInp)
			newShBinCel.appendChild(newShBinInp)
			newShSelCel.appendChild(newShSelInp)
			newCoBinCel.appendChild(newCoBinInp)
			newCoSelCel.appendChild(newCoSelInp)
			newAlBinCel.appendChild(newAlBinInp)
			newAlSelCel.appendChild(newAlSelInp)
			newGrBinCel.appendChild(newGrBinInp)
			newGrSelCel.appendChild(newGrSelInp)
			newExBinCel.appendChild(newExBinInp)
			newExSelCel.appendChild(newExSelInp)
			newImFilCel.appendChild(newImFilInp)
			newImUriCel.appendChild(newImUriInp)
			newTableRow.appendChild(newDescrCel)
			newTableRow.appendChild(newNumbrCel)
			newTableRow.appendChild(newLoBinCel)
			newTableRow.appendChild(newLoSelCel)
			newTableRow.appendChild(newShBinCel)
			newTableRow.appendChild(newShSelCel)
			newTableRow.appendChild(newCoBinCel)
			newTableRow.appendChild(newCoSelCel)
			newTableRow.appendChild(newAlBinCel)
			newTableRow.appendChild(newAlSelCel)
			newTableRow.appendChild(newGrBinCel)
			newTableRow.appendChild(newGrSelCel)
			newTableRow.appendChild(newExBinCel)
			newTableRow.appendChild(newExSelCel)
			newTableRow.appendChild(newImFilCel)
			newTableRow.appendChild(newImUriCel)
			targetElement.appendChild(newTableRow)
		}
	}
}

function Add_Field(thisTarget)
{
	if (NotesCount[thisTarget] >= 32)
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