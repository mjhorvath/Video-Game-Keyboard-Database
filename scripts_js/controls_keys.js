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


//-------------------------------------Main page form controls

function getElementByEvent(e)
{
	var ev = !e ? window.event : e
	return	ev.target ? ev.target : ev.srcElement
}

function Init_Options()
{
	var DefaultScheme = 0
	for (var j = 0, o = ItemPrefixes.length; j < o; j++)
	{
		var thisPrefix = ItemPrefixes[j]
		var thisTable = Dat_Table[thisPrefix]
		var thisElement = document.getElementById(thisPrefix + '_select')
		if (thisPrefix === 'eff')
		{
			for (var i = 0, n = thisTable.length; i < n; i++)
			{
				var thisItem = thisTable[i]
				var thisDiv = document.createElement('div')
				var thisCheckbox = document.createElement('input')
				var thisText = document.createTextNode(thisItem[1])
				thisDiv.className = 'text'
				thisCheckbox.setAttribute('type', 'checkbox')
				if (thisItem[2] === 1)
					thisCheckbox.setAttribute('checked', 'checked')
				thisCheckbox.id = thisPrefix + '_' + i
				// Necessary for IE
				thisCheckbox.onclick = Check_Me
				thisDiv.appendChild(thisCheckbox)
				thisDiv.appendChild(thisText)
				thisElement.appendChild(thisDiv)
			}
		}
		else
		{
			for (var i = 0, n = thisTable.length; i < n; i++)
			{
				var thisItem = thisTable[i]
				if (thisItem[0] === 'break')
				{
					var newOptgroup = document.createElement('optgroup')
					newOptgroup.setAttribute('label', thisItem[1])
					newOptgroup.id = thisPrefix + '_opt_' + i
					thisElement.appendChild(newOptgroup)
				}
				else if (thisItem[0] !== 'Preview')
				{
					var newOption = document.createElement('option')
					var thisText = document.createTextNode(thisItem[1])
					newOption.setAttribute('loadfile', thisItem[0])
					if (thisItem[2] === 1)
					{
						newOption.setAttribute('selected', 'selected')
						if (thisPrefix === 'gam')
							DefaultScheme = i
					}
					newOption.id = thisPrefix + '_' + i
					newOption.setAttribute('optindex', i)
					if (DisableLayouts === true)
					{
						if (thisPrefix === 'gam')
							newOption.onclick = Event_Layout
						else if (thisPrefix === 'lay')
							newOption.setAttribute('disabled', 'disabled')
					}
					newOption.appendChild(thisText)
					thisElement.appendChild(newOption)
				}
			}
		}
	}
	if (DisableLayouts === true)
		Set_Layout(DefaultScheme)
}

function Spawn_Layout()
{
	var thisString = 'keyboard_keys.shtml???'
	for (var i = 0, n = ItemPrefixes.length - 1; i < n; i++)
	{
		var thisPrefix = ItemPrefixes[i]
		var thisObject = document.getElementById(thisPrefix + '_select')
		var thisIndex = thisObject.selectedIndex
		var thisOption = thisObject.options[thisIndex]
		thisString += thisOption.getAttribute('loadfile') + ';'
	}
	var thisTotal = 0
	var thisTable = Dat_Table['eff']
	for (var i = 0, n = thisTable.length; i < n; i++)
	{
		if (document.getElementById('eff_' + i).checked)
			thisTotal += thisTable[i][0]
	}
	thisString += thisTotal + ';'
	window.open(thisString, 'KeyDiagramKeys')
}

// Necessary for IE
function Check_Me(e)
{
	getElementByEvent(e).setAttribute('checked', 'checked')
}

function Event_Layout(e)
{
	Set_Layout(getElementByEvent(e).getAttribute('optindex'))
}

// Doesn't work correctly in IE.
function Set_Layout(thisIndex)
{
	var thisTable = Dat_Table['lay']
	var thisLayout = Dat_Table['gam'][thisIndex][3]
	var thisLength = thisLayout.length
	for (var j = 0, o = thisTable.length; j < o; j++)
	{
		if (thisTable[j][0] !== 'break')
		{
			var thisBool = 0
			var thisSelect = document.getElementById('lay_' + j)
			for (var i = 0, n = thisLength; i < n; i++)
			{
				if (thisLayout[i] === j)
				{
					thisBool = 1
					break
				}
			}
			if (thisBool === 1)
			{
				if (thisSelect.getAttribute('disabled') === 'disabled')
					thisSelect.removeAttribute('disabled')
			}
			else
				thisSelect.setAttribute('disabled', 'disabled')
		}
	}
}


//-------------------------------------Child window display/rendering controls

function Set_Defaults()
{
	var urlString = top.location.href, delimString = '???'
	var urlIndex = urlString.indexOf(delimString) + delimString.length
	var urlTable = urlString.substring(urlIndex).split(';')
	var urlCount = 0
	for (var i = 0, n = ItemPrefixes.length; i < n; i++)
	{
		var thisPrefix = ItemPrefixes[i]
		var thisTable = Dat_Table[thisPrefix]
		var thisString = urlTable[urlCount]
		for (var j = 0, o = thisTable.length; j < o; j ++)
		{
			var thisItem = thisTable[j]
			if (thisItem[2] === 1)
			{
				ItemDefault[thisPrefix] = thisItem[0]
				ItemCurrent[thisPrefix] = thisItem[0]
				break
			}
		}
		for (var j = 0, o = thisTable.length; j < o; j++)
		{
			var testString = thisTable[j][0]
			if ((thisString === testString) || ((thisString % 2) === (testString % 2)))
			{
				ItemCurrent[thisPrefix] = thisString
				break
			}
		}
		urlCount += 1
	}
	for (var i = 0, n = GamePrefixes.length; i < n; i++)
		Gam_Table[GamePrefixes[i]] = {}
}

function Set_Units()
{
	var CentimetersToInches = 0.393700787, DotsPerInch = 96
	switch (Lay_Table['uni'])
	{
		case 'cm':
			ConvertUnits = CentimetersToInches * DotsPerInch
		break
		case 'in':
			ConvertUnits = DotsPerInch
		break
	}
}

function Write_Includes()
{
	var thisHead = document.getElementById('head_div')
	for (var i = 0, n = ItemPrefixes.length - 1; i < n; i++)
	{
		var thisPrefix = ItemPrefixes[i]
		var thisScript = document.createElement('script')
		thisScript.setAttribute('type', 'text/javascript')
		thisScript.setAttribute('src', thisPrefix + '_' + ItemCurrent[thisPrefix] + '.js')
		thisHead.appendChild(thisScript)
	}
	var thisLink = document.createElement('link')
	thisLink.setAttribute('type', 'text/css')
	thisLink.setAttribute('rel', 'stylesheet')
	thisLink.setAttribute('href', 'sty_' + ItemCurrent['sty'] + '.css')
	thisHead.appendChild(thisLink)
}

function Test_Scheme()
{
	var CurrentLayout = ItemCurrent['lay'], DefaultLayout = ItemDefault['lay']
	if ((ItemCurrent['gam'] === 'Preview') && (window.opener))
	{
		for (var i = 0, n = GamePrefixes.length; i < n; i++)
		{
			var thisPrefix = GamePrefixes[i]
			Gam_Table[thisPrefix][CurrentLayout] = window.opener.Gam_Table[thisPrefix][CurrentLayout]
		}
	}
	else
	{
		for (var i = 0, n = GamePrefixes.length; i < n; i++)
		{
			var thisPrefix = GamePrefixes[i]
			if (Gam_Table[thisPrefix][CurrentLayout] === undefined)
				Gam_Table[thisPrefix][CurrentLayout] = Gam_Table[thisPrefix][DefaultLayout]
		}
	}
}

function Load_Scheme()
{
	// layouts
	var thisLayout = ItemCurrent['lay']
	for (var j = 0, o = NotePrefixes.length; j < o; j++)
	{
		var thisPrefix = NotePrefixes[j]
		var targetDiv = document.getElementById(thisPrefix + '_list')
		var thisScheme = Gam_Table[thisPrefix][thisLayout]
		var thisLength = thisScheme.length
		if (thisLength > 0)
		{
			document.getElementById(thisPrefix + '_div').style.display = 'block'
			for (var i = 0; i < thisLength; i++)
			{
				var thisItem = thisScheme[i]
				var thisDiv = document.createElement('div')
				var thisTxt = document.createTextNode(thisItem)
				thisDiv.className = 'text'
				thisDiv.id = thisPrefix + '_' + i
				thisDiv.appendChild(thisTxt)
				targetDiv.appendChild(thisDiv)
			}
		}
	}
	// legends
	var targetDiv = document.getElementById('leg_list')
	var thisScheme = Gam_Table['leg'][thisLayout]
	for (var i = 0, n = thisScheme.length; i < n; i++)
	{
		var thisItem = thisScheme[i]
		var thisDiv = document.createElement('div')
		var thisImg = document.createElement('img')
		var thisTxt = document.createElement('span')
		var thisWrt = document.createTextNode(thisItem[1])
		thisDiv.className = 'leg'
		thisImg.className = 'legimg ' + thisItem[0]
		thisTxt.className = 'legtxt'
		thisDiv.id = 'leg_' + i
		thisImg.id = 'legimg_' + i
		thisTxt.id = 'legtxt_' + i
		thisImg.src = 'blank.png'
		thisDiv.style.display = 'block'
		thisTxt.appendChild(thisWrt)
		thisDiv.appendChild(thisImg)
		thisDiv.appendChild(thisTxt)
		targetDiv.appendChild(thisDiv)
	}
	thisScheme = Gam_Table['aut'][thisLayout]
	if (thisScheme !== '')
	{
		document.getElementById('aut_div').style.display = 'block'
		var thisTxt = document.createTextNode(thisScheme)
		document.getElementById('aut_text').appendChild(thisTxt)
	}
	targetDiv = document.getElementById('key_div')
	thisScheme = Gam_Table['key'][thisLayout]
	for (var i = 1; i < NumberOfKeys; i++)
	{
		var keyBigDiv = document.createElement('div')
		keyBigDiv.className = 'key'
		keyBigDiv.id = 'key_' + i
		targetDiv.appendChild(keyBigDiv)
	}
	for (var i = 0; i < NumberOfKeys; i++)
	{
		var thisItem = thisScheme[i]
		if (thisItem === undefined)
		{
			if (i === 0)
			{
				switch (thisLayout)
				{
					case 'ATUS104':
					case 'ATUS104DVO':
						thisItem = ['','Caption','Shift','Ctrl','Alt','','','']
					break
					case 'ATDE105':
						thisItem = ['','Unterschrift','Shift','Strg','Alt','AltGr','','']
					break
					case 'ATFR105':
						thisItem = ['','L\u00e9gende','Maj.','Ctrl','Alt','AltGr','','']
					break
					case 'MACUS109OLD':
					case 'MACUK110OLD':
					case 'MACUS109NEW':
						thisItem = ['','Caption','Shift','Ctrl','Alt','','']
					break
				}
			}
			else
				thisItem = ['','','','','','','','']
		}
		else
		{
			for (var j = 0; j < 9; j ++)
			{
				if (thisItem[j] === undefined)
					thisItem[j] = ''
			}
		}
		var keyBigDiv = document.getElementById('key_' + i)
		var topBigDiv = document.createElement('div')
		var botBigDiv = document.createElement('div')
		var rgtBigDiv = document.createElement('div')
		var addBigDiv = document.createElement('div')
		var topChaDiv = document.createElement('div')
		var botChaDiv = document.createElement('div')
		var rgtChaDiv = document.createElement('div')
		var botDesDiv = document.createElement('div')
		var addShfDiv = document.createElement('div')
		var addCtrDiv = document.createElement('div')
		var addAltDiv = document.createElement('div')
		var addAgrDiv = document.createElement('div')
		var addXtrDiv = document.createElement('div')
		var botDesTxt = document.createTextNode(thisItem[1])
		var addShfTxt = document.createTextNode(thisItem[2])
		var addCtrTxt = document.createTextNode(thisItem[3])
		var addAltTxt = document.createTextNode(thisItem[4])
		var addAgrTxt = document.createTextNode(thisItem[5])
		var addXtrTxt = document.createTextNode(thisItem[6])
		topBigDiv.className = 'keytop'
		botBigDiv.className = 'keybot'
		rgtBigDiv.className = 'keyrgt'
		addBigDiv.className = 'keyadd'
		topChaDiv.className = 'keycap'
		botChaDiv.className = 'keycap'
		rgtChaDiv.className = 'keycap'
		botDesDiv.className = 'keydes'
		addShfDiv.className = 'addshf'
		addCtrDiv.className = 'addctr'
		addAltDiv.className = 'addalt'
		addAgrDiv.className = 'addagr'
		addXtrDiv.className = 'addxtr'
		topBigDiv.id = 'keytop_' + i
		botBigDiv.id = 'keybot_' + i
		rgtBigDiv.id = 'keyrgt_' + i
		addBigDiv.id = 'keyadd_' + i
		topChaDiv.id = 'topcap_' + i
		botChaDiv.id = 'botcap_' + i
		rgtChaDiv.id = 'rgtcap_' + i
		botDesDiv.id = 'botdes_' + i
		addShfDiv.id = 'addshf_' + i
		addCtrDiv.id = 'addctr_' + i
		addAltDiv.id = 'addalt_' + i
		addAgrDiv.id = 'addagr_' + i
		addXtrDiv.id = 'addxtr_' + i
		botDesDiv.appendChild(botDesTxt)
		addShfDiv.appendChild(addShfTxt)
		addCtrDiv.appendChild(addCtrTxt)
		addAltDiv.appendChild(addAltTxt)
		addAgrDiv.appendChild(addAgrTxt)
		addXtrDiv.appendChild(addXtrTxt)
		addBigDiv.appendChild(addShfDiv)
		addBigDiv.appendChild(addCtrDiv)
		addBigDiv.appendChild(addAltDiv)
		addBigDiv.appendChild(addAgrDiv)
		addBigDiv.appendChild(addXtrDiv)
		botBigDiv.appendChild(botDesDiv)
		topBigDiv.appendChild(topChaDiv)
		botBigDiv.appendChild(botChaDiv)
		rgtBigDiv.appendChild(rgtChaDiv)
		if (thisItem[7] !== '')
		{
			var picBigImg = document.createElement('img')
			picBigImg.className = 'keyimg'
			picBigImg.id = 'keyimg_' + i
			picBigImg.src = thisItem[7]
			keyBigDiv.appendChild(picBigImg)
		}
		keyBigDiv.appendChild(topBigDiv)
		keyBigDiv.appendChild(botBigDiv)
		keyBigDiv.appendChild(rgtBigDiv)
		keyBigDiv.appendChild(addBigDiv)
		keyBigDiv.className = thisItem[0]
	}
}

function Load_Style()
{
	var thisStyle = ItemCurrent['sty']
	switch (thisStyle)
	{
		case 'Kitty':
		case 'Kozierok':
		case 'Savard':
			document.getElementById('leg_list').style.display = 'none'
		break
	}
	for (var i = 0; i < NumberOfKeys; i++)
		document.getElementById('key_' + i).className += ' ' + Sty_Table['key'][i]
	for (var i = 0, n = Lay_Table['cut'].length; i < n; i++)
		document.getElementById('cut_' + i).className = Sty_Table['cut'][i]
}

function Load_Layout()
{
	for (var i = 0; i < NumberOfKeys; i++)
	{
		var thisItem = Lay_Table['key'][i]
		var targetDiv = document.getElementById('key_' + i)
		if (thisItem)
		{
			// thisItem.length buggy in Firefox
			for (var j = 0, o = 8; j < o; j ++)
			{
				if (thisItem[j] === undefined)
					thisItem[j] = ''
			}
			if (i !== 0)
			{
				targetDiv.style.left   = thisItem[0] * ConvertUnits + KeyPadding + CutPadding + 'px'
				targetDiv.style.top    = thisItem[1] * ConvertUnits + KeyPadding + CutPadding + 'px'
				targetDiv.style.width  = Math.max(thisItem[2] * ConvertUnits, 0) - KeyPadding * 2 + 'px'
				targetDiv.style.height = Math.max(thisItem[3] * ConvertUnits, 0) - KeyPadding * 2 + 'px'
				targetDiv.style.display = 'block'
			}
			var topChaDiv = document.getElementById('topcap_' + i)
			var botChaDiv = document.getElementById('botcap_' + i)
			var rgtChaDiv = document.getElementById('rgtcap_' + i)
			var topChaTxt = document.createTextNode(thisItem[4])
			var botChaTxt = document.createTextNode(thisItem[5])
			var rgtChaTxt = document.createTextNode(thisItem[6])
			topChaDiv.appendChild(topChaTxt)
			botChaDiv.appendChild(botChaTxt)
			rgtChaDiv.appendChild(rgtChaTxt)
		}                            
		else if (i !== 0)
			targetDiv.style.display = 'none'
	}
	var targetDiv = document.getElementById('cut_div')
	for (var i = 0, n = Lay_Table['cut'].length; i < n; i++)
	{
		var cutBigDiv = document.createElement('div')
		var thisItem = Lay_Table['cut'][i]
		cutBigDiv.className = 'cut'
		cutBigDiv.id = 'cut_' + i
		cutBigDiv.style.left   = thisItem[0] * ConvertUnits + 'px'
		cutBigDiv.style.top    = thisItem[1] * ConvertUnits + 'px'
		cutBigDiv.style.width  = Math.max(thisItem[2] * ConvertUnits, 0) + CutPadding * 2 + 'px'
		cutBigDiv.style.height = Math.max(thisItem[3] * ConvertUnits, 0) + CutPadding * 2 + 'px'
		targetDiv.appendChild(cutBigDiv)
	}
	for (var i = 0, n = TextPrefixes.length; i < n; i++)
	{
		var thisPrefix = TextPrefixes[i]
		var thisText = document.createTextNode(Lay_Table[thisPrefix] + ':')
		document.getElementById(thisPrefix + '_title').appendChild(thisText)
	}
	document.title = Lay_Table['ttl'] + ' - ' + Gam_Table['gam'][ItemCurrent['lay']] + ' - ' + Lay_Table['nam']
}

function Load_Effects()
{
	var thisEffect = ItemCurrent['eff'], thisLayout = ItemCurrent['lay']
	if ((thisEffect === 0) || (!window.ActiveXObject))
		return
	var TempEffects = []
	for (var i = Dat_Table['eff'].length; i > 0; i--)
	{
		var TempSub = thisEffect - Math.pow(2, i)
		if (TempSub >= 0)
		{
			TempEffects[i - 1] = 1
			thisEffect = TempSub
		}
		else
			TempEffects[i - 1] = 0
	}
	var DropY = KeyPadding
	var ModeR = 0
	if (TempEffects[4] === 1)
	{
		DropY = -KeyPadding
		ModeR = 3
	}
	var BasicImageFilter  = 'progid:DXImageTransform.Microsoft.BasicImage(Rotation=0,Mirror=0,Invert=' + TempEffects[0] + ',XRay=' + TempEffects[1] + ',Grayscale=' + TempEffects[2] + ',Opacity=1.00)'
	var DropShadowFilter1 = 'progid:DXImageTransform.Microsoft.dropShadow(Color=000000,offX=' + KeyPadding + ',offY=' + DropY      + ',positive=true)'
	var DropShadowFilter2 = 'progid:DXImageTransform.Microsoft.dropShadow(Color=000000,offX=' + KeyPadding + ',offY=' + KeyPadding + ',positive=true)'
	var RotationFilter    = 'progid:DXImageTransform.Microsoft.BasicImage(Rotation=' + ModeR + ',Mirror=0,Invert=0,XRay=0,Grayscale=0,Opacity=1.00)'
	if (TempEffects[4] === 1)
	{
		for (var i = 1; i < NumberOfKeys; i++)
		{
			var targetDiv = document.getElementById('key_' + i)
			var thisItem = Lay_Table['key'][i]
			if (thisItem)
			{
				targetDiv.style.top = ''
				targetDiv.style.bottom = thisItem[0] * ConvertUnits + KeyPadding + CutPadding + 'px'
				targetDiv.style.left   = thisItem[1] * ConvertUnits + KeyPadding + CutPadding + 'px'
				targetDiv.style.width  = Math.max(thisItem[2] * ConvertUnits, 0) - KeyPadding * 2 + 'px'
				targetDiv.style.height = Math.max(thisItem[3] * ConvertUnits, 0) - KeyPadding * 2 + 'px'
				targetDiv.style.filter = RotationFilter
			}
		}
		for (var i = 0, n = Lay_Table['cut'].length; i < n; i++)
		{
			var targetDiv = document.getElementById('cut_' + i)
			var thisItem = Lay_Table['cut'][i]
			targetDiv.style.top = ''
			targetDiv.style.bottom = thisItem[0] * ConvertUnits + 'px'
			targetDiv.style.left   = thisItem[1] * ConvertUnits + 'px'
			targetDiv.style.width  = Math.max(thisItem[2] * ConvertUnits, 0) + CutPadding * 2 + 'px'
			targetDiv.style.height = Math.max(thisItem[3] * ConvertUnits, 0) + CutPadding * 2 + 'px'
			targetDiv.style.filter = RotationFilter
		}
		document.getElementById('layout').style.width    =  480 + 'px'
		document.getElementById('layout').style.height   = 1800 + 'px'
		document.getElementById('spacer').style.width    =  480 + 'px'
		document.getElementById('spacer').style.height   = 1800 + 'px'
	}
	var legendLength = Gam_Table['leg'][thisLayout].length
	for (var i = 0; i < NumberOfKeys; i++)
		document.getElementById('key_' + i).style.filter += ' ' + BasicImageFilter
	for (var i = 0; i < legendLength; i ++)
		document.getElementById('legimg_' + i).style.filter += ' ' + BasicImageFilter
	if (TempEffects[3] === 1)
	{
		var thisSchemeTable = Gam_Table['key'][thisLayout]
		for (var i = 0; i < NumberOfKeys; i++)
		{
			if (thisSchemeTable[i])
				document.getElementById('key_' + i).style.filter += ' ' + DropShadowFilter1
		}
		for (var i = 0; i < legendLength; i ++)
			document.getElementById('legimg_' + i).style.filter += ' ' + DropShadowFilter2
	}
//	document.body.style.filter += BasicImageFilter
/*
	// LEGACY CODE // This didn't get along well with the other effects. It looked nice, though.
	if (TempEffects[5] === 1)
	{
		for (var i in Scheme)
		{
			var targetSch = Scheme[i]
			if (targetSch[8] != '')
			{
				var targetColor = '#e02020'	//document.getElementById(targetSch[0]).style.backgroundColor
				var targetElement = document.getElementById(targetSch[0])
				var GradientFilter = 'progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#000000,endColorStr=' + targetColor + ')'
				targetElement.style.filter += ' ' + GradientFilter
				targetElement.style.color = '#ffffff'
				targetElement.childNodes[0].childNodes[0].style.textDecoration = 'underline'
				targetElement.childNodes[1].childNodes[1].style.textDecoration = 'underline'
			}
		}
		for (var i = 0; i < 10; i ++)
		{
			var targetColor = '#e02020'	//document.getElementById('legimg_' + i).style.backgroundColor
			var targetElement = document.getElementById('legimg_' + i)
			var targetClass = targetElement.className
			var GradientFilter = 'progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#000000,endColorStr=' + targetColor + ')'
			targetElement.style.filter += ' ' + GradientFilter
		}
	}
*/
}
