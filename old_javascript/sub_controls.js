var CurrentItem = {}, AuthorEmail = '', AuthorApprv = ''
var ClassesTableAbbr = ['none','red','yel','grn','cyn','blu','mag','wht','gry','blk','org','olv']
var ClassesTableFull = ['Red','Yellow','Green','Cyan','Blue','Magenta','White','Gray','Black','Orange','Olive']
var CaptionTable = ['Class','Caption','Shift','Ctrl','Alt','','','']
var ActionsTable = ['Key Color','Key Press','SHIFT + Key','CTRL + Key','ALT + Key','ALTGR + Key','Extra Action','Icon File']
var SelectsTable = ['sty','lay']
var NotesCount = {mou:0,joy:0,com:0,add:0}

function Init_Submissions()
{
	for (var j = 0, o = SelectsTable.length; j < o; j++)
	{
		var thisPrefix = SelectsTable[j]
		var targetElement = document.getElementById(thisPrefix + 'sub_div')
		var thisList = Dat_Table[thisPrefix]
		if (thisPrefix === 'lay')
			targetElement.onclick = Reset_Keylist
		var firstBool = 1
		for (var i = 0, n = thisList.length; i < n; i++)
		{
			var thisItem = thisList[i]
			if (thisItem[0] === 'break')
			{
				var thisOption = document.createElement('optgroup')
				var thisText = document.createTextNode(thisItem[1])
				thisOption.appendChild(thisText)
				targetElement.appendChild(thisOption)
			}
			else
			{
				var thisOption = document.createElement('option')
				var thisText = document.createTextNode(thisItem[1])
				thisOption.setAttribute('optindex', thisItem[0])
				thisOption.setAttribute('optprefix', thisPrefix)
				if (firstBool === 1)
				{
					thisOption.setAttribute('selected', 'selected')
					CurrentItem[thisPrefix] = thisItem[0]
					firstBool = 0
				}
				thisOption.appendChild(thisText)
				targetElement.appendChild(thisOption)
			}
		}
	}
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
	targetElement = document.getElementById('legsub_div')
	var newTableRow = document.createElement('tr')
	var newClassCel = document.createElement('th')
	var newDescrCel = document.createElement('th')
	var newClassTxt = document.createTextNode('Color')
	var newDescrTxt = document.createTextNode('Description')
	newClassCel.appendChild(newClassTxt)
	newDescrCel.appendChild(newDescrTxt)
	newTableRow.appendChild(newClassCel)
	newTableRow.appendChild(newDescrCel)
	targetElement.appendChild(newTableRow)
	for (var i = 0, n = ClassesTableFull.length; i < n; i++)
	{
		newTableRow = document.createElement('tr')
		newClassCel = document.createElement('th')
		newDescrCel = document.createElement('td')
		newClassTxt = document.createTextNode(ClassesTableFull[i])
		newDescrInp = document.createElement('input')
		newDescrInp.id = 'legsub_descr_' + i
		newDescrInp.setAttribute('type', 'text')
		newDescrInp.setAttribute('size', '40')
		newClassCel.appendChild(newClassTxt)
		newDescrCel.appendChild(newDescrInp)
		newTableRow.appendChild(newClassCel)
		newTableRow.appendChild(newDescrCel)
		targetElement.appendChild(newTableRow)
	}
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
	for (var i = 1, n = CaptionTable.length - 1; i < n; i++)
	{
		newTableRow = document.createElement('tr')
		newDescrCel = document.createElement('th')
		newCaptnCel = document.createElement('td')
		newDescrTxt = document.createTextNode(ActionsTable[i])
		var newCaptnInp = document.createElement('input')
		newCaptnInp.value = CaptionTable[i]
		newCaptnInp.id = 'keyexa_captn_' + i
		newDescrCel.appendChild(newDescrTxt)
		newCaptnCel.appendChild(newCaptnInp)
	   	newTableRow.appendChild(newDescrCel)
		newTableRow.appendChild(newCaptnCel)
		targetElement.appendChild(newTableRow)
	}
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
		}
		var newCel2 = document.createElement('th')
		var newTxt2 = document.createTextNode('Description')
		newCel2.appendChild(newTxt2)
		newRow.appendChild(newCel2)
		targetElement.appendChild(newRow)
		for (var i = 0; i < 3; i++)
			Add_Field(thisTarget)
	}
}

function Reset_Keylist(e)
{
	var thisPrefix = 'lay'
	var thisSelect = getElementByEvent(e)
	var thisTag = thisSelect.tagName
	if ((thisTag === 'OPTION') || (thisTag === 'OPTGROUP'))
		thisSelect = thisSelect.parentNode
	var thisOption = thisSelect.options[thisSelect.selectedIndex]
	CurrentItem[thisPrefix] = thisOption.getAttribute('optindex')
	Set_LayoutFile(thisPrefix)
}

function Set_LayoutFile(thisPrefix)
{
	var thisScript = document.createElement('script')
	var thisHead = document.getElementById('head_div')
	var oldScript = document.getElementById(thisPrefix + '_script')
	thisHead.removeChild(oldScript)
	thisScript.setAttribute('type', 'text/javascript')
	thisScript.setAttribute('src', thisPrefix + '_' + CurrentItem[thisPrefix] + '.js')
	thisScript.id = thisPrefix + '_script'
	thisHead.appendChild(thisScript)
	Sch_Table = {}
	for (var i = 0, n = SchmPrefixes.length; i < n; i++)
		Sch_Table[SchmPrefixes[i]] = {}
}

function Get_Keys()
{
	var targetElement = document.getElementById('keysub_div')
	while (targetElement.hasChildNodes())
		targetElement.removeChild(targetElement.lastChild)
	var newTableRow = document.createElement('tr')
	var newDescrCel = document.createElement('th')
	var newClassCel = document.createElement('th')
	var newLowkyCel = document.createElement('th')
	var newShiftCel = document.createElement('th')
	var newContrCel = document.createElement('th')
	var newAltkyCel = document.createElement('th')
	var newAltgrCel = document.createElement('th')
	var newExtraCel = document.createElement('th')
	var newImageCel = document.createElement('th')
	var newDescrTxt = document.createTextNode('Key')
	var newClassTxt = document.createTextNode(ActionsTable[0])
	var newLowkyTxt = document.createTextNode(ActionsTable[1])
	var newShiftTxt = document.createTextNode(ActionsTable[2])
	var newContrTxt = document.createTextNode(ActionsTable[3])
	newDescrCel.appendChild(newDescrTxt)
	newClassCel.appendChild(newClassTxt)
	newLowkyCel.appendChild(newLowkyTxt)
	newShiftCel.appendChild(newShiftTxt)
	newContrCel.appendChild(newContrTxt)
	newTableRow.appendChild(newDescrCel)
	newTableRow.appendChild(newClassCel)
	newTableRow.appendChild(newLowkyCel)
	newTableRow.appendChild(newShiftCel)
	newTableRow.appendChild(newContrCel)
	targetElement.appendChild(newTableRow)
	for (var i = 1; i < NumberOfKeys; i++)
	{
		if (!Lay_Table['key'][i])
			continue
		newTableRow = document.createElement('tr')
		newTableRow = document.createElement('tr')
		newDescrCel = document.createElement('th')
		newClassCel = document.createElement('td')
		newLowkyCel = document.createElement('td')
		newShiftCel = document.createElement('td')
		newContrCel = document.createElement('td')
		var newDescrTxt = document.createTextNode(Lay_Table['key'][i][5])
		var newClassSel = document.createElement('select')
		var newLowkyInp = document.createElement('input')
		var newShiftInp = document.createElement('input')
		var newContrInp = document.createElement('input')
		newClassSel.id = 'keysub_class_' + i
		newLowkyInp.id = 'keysub_lowky_' + i
		newShiftInp.id = 'keysub_shift_' + i
		newContrInp.id = 'keysub_contr_' + i
		newClassSel.style.width = 11 + 'em'
		var newClassOpt = document.createElement('option')
		var newClassTxt = document.createTextNode('none')
		newClassOpt.setAttribute('selected', 'selected')
		newClassOpt.appendChild(newClassTxt)
		newClassSel.appendChild(newClassOpt)
		for (var j = 0, o = ClassesTableFull.length; j < o; j++)
		{
			newClassOpt = document.createElement('option')
			newClassTxt = document.createTextNode(ClassesTableFull[j])
			newClassOpt.style.backgroundColor = ClassesTableFull[j]
			newClassOpt.appendChild(newClassTxt)
			newClassSel.appendChild(newClassOpt)
		}
		newLowkyInp.setAttribute('type', 'text')
		newShiftInp.setAttribute('type', 'text')
		newContrInp.setAttribute('type', 'text')
		newDescrCel.appendChild(newDescrTxt)
		newClassCel.appendChild(newClassSel)
		newLowkyCel.appendChild(newLowkyInp)
		newShiftCel.appendChild(newShiftInp)
		newContrCel.appendChild(newContrInp)
		newTableRow.appendChild(newDescrCel)
		newTableRow.appendChild(newClassCel)
		newTableRow.appendChild(newLowkyCel)
		newTableRow.appendChild(newShiftCel)
		newTableRow.appendChild(newContrCel)
		targetElement.appendChild(newTableRow)
	}
	newTableRow = document.createElement('tr')
	newDescrCel = document.createElement('th')
	newDescrTxt = document.createTextNode('')
	newAltkyTxt = document.createTextNode(ActionsTable[4])
	newAltgrTxt = document.createTextNode(ActionsTable[5])
	newExtraTxt = document.createTextNode(ActionsTable[6])
	newImageTxt = document.createTextNode(ActionsTable[7])
	newDescrCel.appendChild(newDescrTxt)
	newAltkyCel.appendChild(newAltkyTxt)
	newAltgrCel.appendChild(newAltgrTxt)
	newExtraCel.appendChild(newExtraTxt)
	newImageCel.appendChild(newImageTxt)
	newTableRow.appendChild(newDescrCel)
	newTableRow.appendChild(newAltkyCel)
	newTableRow.appendChild(newAltgrCel)
	newTableRow.appendChild(newExtraCel)
	newTableRow.appendChild(newImageCel)
	targetElement.appendChild(newTableRow)
	for (var i = 1; i < NumberOfKeys; i++)
	{
		if (!Lay_Table['key'][i])
			continue
		newTableRow = document.createElement('tr')
		newDescrCel = document.createElement('th')
		newAltkyCel = document.createElement('td')
		newAltgrCel = document.createElement('td')
		newExtraCel = document.createElement('td')
		newImageCel = document.createElement('td')
		var newDescrTxt = document.createTextNode(Lay_Table['key'][i][5])
		var newAltkyInp = document.createElement('input')
		var newAltgrInp = document.createElement('input')
		var newExtraInp = document.createElement('input')
		var newImageInp = document.createElement('input')
		newAltkyInp.id = 'keysub_altky_' + i
		newAltgrInp.id = 'keysub_altgr_' + i
		newExtraInp.id = 'keysub_wxtra_' + i
		newImageInp.id = 'keysub_image_' + i
		newAltkyInp.setAttribute('type', 'text')
		newAltgrInp.setAttribute('type', 'text')
		newExtraInp.setAttribute('type', 'text')
		newImageInp.setAttribute('type', 'text')
		newDescrCel.appendChild(newDescrTxt)
		newAltkyCel.appendChild(newAltkyInp)
		newAltgrCel.appendChild(newAltgrInp)
		newExtraCel.appendChild(newExtraInp)
		newImageCel.appendChild(newImageInp)
		newTableRow.appendChild(newDescrCel)
		newTableRow.appendChild(newAltkyCel)
		newTableRow.appendChild(newAltgrCel)
		newTableRow.appendChild(newExtraCel)
		newTableRow.appendChild(newImageCel)
		targetElement.appendChild(newTableRow)
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

function Parse_Fields()
{
	for (var j = 0, o = SelectsTable.length; j < o; j++)
	{
		var thisPrefix = SelectsTable[j]
		var targetElement = document.getElementById(thisPrefix + 'sub_div')
		var thisOption = targetElement.options[targetElement.selectedIndex]
		CurrentItem[thisPrefix] = thisOption.getAttribute('optindex')
	}
	var thisPrefix = 'eff'
	var thisTable = Dat_Table[thisPrefix]
	CurrentItem[thisPrefix] = 0
	for (var i = 0, n = thisTable.length; i < n; i++)
	{
		targetElement = document.getElementById(thisPrefix + 'sub_' + i)
		if (targetElement.checked)
			CurrentItem[thisPrefix] += thisTable[i][0]
	}
	targetElement = document.getElementById('keysub_div')
	var targetScheme = []
	for (var i = 0; i < NumberOfKeys; i++)
	{
		if (i === 0)
		{
			var boolUndefined = 1
			var tempScheme = []
			for (var j = 0, o = CaptionTable.length; j < o; j++)
			{
				if ((j === 0) || (j === CaptionTable.length - 1))
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
			var newClassSel = document.getElementById('keysub_class_' + i)
			var newLowkyInp = document.getElementById('keysub_lowky_' + i)
			var newShiftInp = document.getElementById('keysub_shift_' + i)
			var newContrInp = document.getElementById('keysub_contr_' + i)
			var newAltkyInp = document.getElementById('keysub_altky_' + i)
			var newAltgrInp = document.getElementById('keysub_altgr_' + i)
			var newExtraInp = document.getElementById('keysub_wxtra_' + i)
			var newImageInp = document.getElementById('keysub_image_' + i)
			var newClassVal = ClassesTableAbbr[newClassSel.selectedIndex]
			if (newClassVal === 'none')
				newClassVal = undefined
			var newLowkyVal = newLowkyInp.value
			if (newLowkyVal === '')
				newLowkyVal = undefined
			var newShiftVal = newShiftInp.value
			if (newShiftVal === '')
				newShiftVal = undefined
			var newContrVal = newContrInp.value
			if (newContrVal === '')
				newContrVal = undefined
			var newAltkyVal = newAltkyInp.value
			if (newAltkyVal === '')
				newAltkyVal = undefined
			var newAltgrVal = newAltgrInp.value
			if (newAltgrVal === '')
				newAltgrVal = undefined
			var newExtraVal = newExtraInp.value
			if (newExtraVal === '')
				newExtraVal = undefined
			var newImageVal = newImageInp.value
			if (newImageVal === '')
				newImageVal = undefined
			targetScheme[i] = [newClassVal,newLowkyVal,newShiftVal,newContrVal,newAltkyVal,newAltgrVal,newExtraVal,newImageVal]
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
	Sch_Table['key'][CurrentItem['lay']] = targetScheme
	targetScheme = []
	for (var i = 1, n = ClassesTableAbbr.length; i < n; i++)
	{
		var thisCount = i - 1
		var newDescrInp = document.getElementById('legsub_descr_' + thisCount)
		var newDescrVal = newDescrInp.value
		var newDescrIdx = ClassesTableAbbr[i]
		if (newDescrVal !== '')
			targetScheme[thisCount] = [newDescrIdx,newDescrVal]
	}
	Sch_Table['leg'][CurrentItem['lay']] = targetScheme
	for (var thisTarget in NotesCount)
	{
		targetScheme = []
		var targetElement = document.getElementById(thisTarget + 'sub_div')
		var targetChildren = targetElement.childNodes
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
		Sch_Table[thisTarget][CurrentItem['lay']] = targetScheme
	}
	var thisName = document.getElementById('namsub_div').value
	var thisAuth = document.getElementById('autsub_div').value
	Sch_Table['gam'][CurrentItem['lay']] = thisName
	Sch_Table['aut'][CurrentItem['lay']] = thisAuth
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
	'Sch_Table[\'gam\'][sLay] = ' + '\'' + Sch_Table['gam'][CurrentItem['lay']] + '\'\n' +
	'Sch_Table[\'aut\'][sLay] = ' + '\'' + Sch_Table['aut'][CurrentItem['lay']] + '\'\n' +
	'Sch_Table[\'key\'][sLay] =\n' + '[\n'
	var targetScheme = Sch_Table['key'][CurrentItem['lay']]
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
	targetScheme = Sch_Table['leg'][CurrentItem['lay']]
	submitText += 'Sch_Table[\'leg\'][sLay] =\n[\n'
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
		targetScheme = Sch_Table[thisTarget][CurrentItem['lay']]
		submitText += 'Sch_Table[\'' + thisTarget + '\'][sLay] =\n[\n'
		for (var i = 0, n = targetScheme.length; i < n; i++)
			submitText += '\'' + targetScheme[i] + '\',\n'
		submitText = submitText.replace(/\,\n$/,'\n')
		submitText += ']\n'
	}
	submitText = submitText.replace(/=\n\[\n\]/g,'= []')
	document.getElementById('endsub_div').value = submitText
}
