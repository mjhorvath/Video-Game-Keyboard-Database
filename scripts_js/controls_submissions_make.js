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


function Change_Group_Color(e)
{
	var thisSelect = getElementByEvent(e)
	var thisIndex = thisSelect.selectedIndex
	var abbr_color = ClassesTableAbbr[thisIndex]
	thisSelect.className = abbr_color
}
function Validate_Keys_Inp(thisVal, thisID)
{
	if ((typeof thisVal === 'string') && (thisVal.length != ''))
	{
		document.getElementById(thisID).value = thisVal.replaceAll('\n','\\n').replaceAll('\t','\\t')
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
	// data rows
	for (var i = 0, n = Lay_Table['key'].length; i < n; i++)
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
			newLoBinInp.id = 'keysub_lotxt_' + i
			newLoSelInp.id = 'keysub_logrp_' + i
			newShBinInp.id = 'keysub_shtxt_' + i
			newShSelInp.id = 'keysub_shgrp_' + i
			newCoBinInp.id = 'keysub_cotxt_' + i
			newCoSelInp.id = 'keysub_cogrp_' + i
			newAlBinInp.id = 'keysub_altxt_' + i
			newAlSelInp.id = 'keysub_algrp_' + i
			newGrBinInp.id = 'keysub_grtxt_' + i
			newGrSelInp.id = 'keysub_grgrp_' + i
			newExBinInp.id = 'keysub_extxt_' + i
			newExSelInp.id = 'keysub_exgrp_' + i
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
				newLoSelInp.onchange = Change_Group_Color
				newShSelInp.onchange = Change_Group_Color
				newCoSelInp.onchange = Change_Group_Color
				newAlSelInp.onchange = Change_Group_Color
				newGrSelInp.onchange = Change_Group_Color
				newExSelInp.onchange = Change_Group_Color
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
// this should be merged into 'Make_Keys'
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
			Validate_Keys_Inp(thisRow[0], 'keysub_lotxt_' + i)			// column 1
			Validate_Keys_Sel(thisRow[1], 'keysub_logrp_' + i)			// column 2
			Validate_Keys_Inp(thisRow[2], 'keysub_shtxt_' + i)			// column 3
			Validate_Keys_Sel(thisRow[3], 'keysub_shgrp_' + i)			// column 4
			Validate_Keys_Inp(thisRow[4], 'keysub_cotxt_' + i)			// column 5
			Validate_Keys_Sel(thisRow[5], 'keysub_cogrp_' + i)			// column 6
			Validate_Keys_Inp(thisRow[6], 'keysub_altxt_' + i)			// column 7
			Validate_Keys_Sel(thisRow[7], 'keysub_algrp_' + i)			// column 8
			Validate_Keys_Inp(thisRow[8], 'keysub_grtxt_' + i)			// column 9
			Validate_Keys_Sel(thisRow[9], 'keysub_grgrp_' + i)			// column 10
			Validate_Keys_Inp(thisRow[10], 'keysub_extxt_' + i)			// column 11
			Validate_Keys_Sel(thisRow[11], 'keysub_exgrp_' + i)			// column 12
			Validate_Keys_Inp(thisRow[12], 'keysub_imfil_' + i)			// column 13
			Validate_Keys_Inp(thisRow[13], 'keysub_imuri_' + i)			// column 14
		}
	}
}
function Make_Legends()
{
	// legends table #1
	var targetElement = document.getElementById('exasub_div')
	while (targetElement.hasChildNodes())
		targetElement.removeChild(targetElement.lastChild)
	var newTableRow = document.createElement('tr')
	var newDescrCel = document.createElement('th')
	var newCaptnCel = document.createElement('th')
	var newDescrTxt = document.createTextNode('Action')
	var newCaptnTxt = document.createTextNode('Description')
	newDescrCel.appendChild(newDescrTxt)
	newCaptnCel.appendChild(newCaptnTxt)
	newTableRow.appendChild(newDescrCel)
	newTableRow.appendChild(newCaptnCel)
	targetElement.appendChild(newTableRow)
	for (var i = 0; i < 6; i++)
	{
		var newTableRow = document.createElement('tr')
		var newDescrCel = document.createElement('th')
		var newCaptnCel = document.createElement('td')
		var newDescrTxt = document.createTextNode(CaptionsTable[i])
		var newCaptnInp = document.createElement('input')
		newCaptnInp.setAttribute('type', 'text')
		newCaptnInp.value = Lay_Table['leg'][i]
		newCaptnInp.id = 'keyexa_captn_' + i
		newDescrCel.appendChild(newDescrTxt)
		newCaptnCel.appendChild(newCaptnInp)
	   	newTableRow.appendChild(newDescrCel)
		newTableRow.appendChild(newCaptnCel)
		targetElement.appendChild(newTableRow)
	}
	// legends table #2
	var targetElement = document.getElementById('legsub_div')
	while (targetElement.hasChildNodes())
		targetElement.removeChild(targetElement.lastChild)
	var thisScheme = Gam_Table[sLay]['leg']
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
	for (var i = 0; i < 12; i++)
	{
		newTableRow = document.createElement('tr')
		newClassCel = document.createElement('th')
		newDescrCel = document.createElement('td')
		newClassTxt = document.createTextNode(ClassesTableFull[i+1])	// skip the first null color
		newDescrInp = document.createElement('input')
		newDescrInp.id = 'legsub_descr_' + i
		newDescrInp.setAttribute('type', 'text')
		newDescrInp.setAttribute('size', '40')
		for (var j = 0, o = thisScheme.length; j < o; j++)
		{
			if (thisScheme[j][0] === i)
			{
				newDescrInp.value = thisScheme[j][1]
				break
			}
		}
		newClassCel.className = newClassCel.className + ' ' + ClassesTableAbbr[i+1]	// skip the first null color
		newClassCel.appendChild(newClassTxt)
		newDescrCel.appendChild(newDescrInp)
		newTableRow.appendChild(newClassCel)
		newTableRow.appendChild(newDescrCel)
		targetElement.appendChild(newTableRow)
	}
}
// mouse, joystick, etc. lists
function Make_Notes()
{
	for (var thisTarget in NotesCount)
	{
		var targetElement = document.getElementById(thisTarget + 'sub_div')
		while (targetElement.hasChildNodes())
			targetElement.removeChild(targetElement.lastChild)
		NotesCount[thisTarget] = 0
		var dataTable = Gam_Table[sLay][thisTarget]
		// header row
		var newRow = document.createElement('tr')
		var newCel0 = document.createElement('th')
		var newTxt0 = document.createTextNode('#')
		newCel0.appendChild(newTxt0)
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
		// data rows
		for (var i = 0, n = dataTable.length; i < n; i++)
		{
			var thisDatum = dataTable[i]
			newRow = document.createElement('tr')
			newCel0 = document.createElement('th')
			newTxt0 = document.createTextNode(i + 1)
			newCel0.appendChild(newTxt0)
			newRow.appendChild(newCel0)
			if (thisTarget !== 'add')
			{
				newCel1 = document.createElement('td')
				var newInp1 = document.createElement('input')
				newInp1.setAttribute('type', 'text')
				newInp1.setAttribute('size', '30')
				newInp1.id = thisTarget + 'sub_inp1_' + NotesCount[thisTarget]
				newInp1.value = thisDatum[0]
				newCel1.appendChild(newInp1)
				newRow.appendChild(newCel1)

				newCel2 = document.createElement('td')
				var newInp2 = document.createElement('input')
				newInp2.setAttribute('type', 'text')
				newInp2.setAttribute('size', '40')
				newInp2.id = thisTarget + 'sub_inp2_' + NotesCount[thisTarget]
				newInp2.value = thisDatum[1]
				newCel2.appendChild(newInp2)
				newRow.appendChild(newCel2)
			}
			else
			{
				newCel2 = document.createElement('td')
				var newInp2 = document.createElement('input')
				newInp2.setAttribute('type', 'text')
				newInp2.setAttribute('size', '70')
				newInp2.id = thisTarget + 'sub_inp2_' + NotesCount[thisTarget]
				newInp2.value = thisDatum
				newCel2.appendChild(newInp2)
				newRow.appendChild(newCel2)
			}
			targetElement.appendChild(newRow)
			NotesCount[thisTarget] = i + 1
		}
	}
}
