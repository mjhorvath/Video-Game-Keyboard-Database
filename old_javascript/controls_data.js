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


var CurrentItem = {}, AuthorEmail = '', AuthorApprv = ''
var ClassesTableAbbr = ['non','red','yel','grn','cyn','blu','mag','wht','gry','blk','org','olv','brn']
var ClassesTableFull = ['None','Red','Yellow','Green','Cyan','Blue','Magenta','White','Gray','Black','Orange','Olive','Brown']
var CaptionsTableL = ['Normal Key','SHIFT + Key','CTRL + Key','ALT + Key','ALTGR + Key','Extra Action']
var CaptionsTableR = ['Caption','Shift','Ctrl','Alt','AltGr','Extra']
var OldActionsTable = ['Key Color','Normal Key','SHIFT + Key','CTRL + Key','ALT + Key','ALTGR + Key','Extra Action','Icon File']
var ActionsTable = ['Key','ID','Normal Key','Group','SHIFT + Key','Group','CTRL + Key','Group','ALT + Key','Group','ALTGR + Key','Group','Extra Action','Group','Icon File','Icon URI']
var SelectsTable = ['sch','sty','lay']
var NotesCount = {mou:0,joy:0,com:0,add:0}
var ItemPrefixes = ['sch','sty','lay','eff']
var NamePrefixes = ['aut','gam']
var NotePrefixes = ['com','mou','joy','add']
var SchmPrefixes = ['com','mou','joy','add','aut','leg','gam','key']
var TextPrefixes = ['com','mou','joy','add','aut','leg','ret']
var NumberOfKeys = 0, ConvertUnits = 1, KeyPadding = 2, CutPadding = 2, DisableLayouts = true
var Dat_Table = {}, Grp_Table = {}, Sch_Table = {}, Lay_Table = {}, Sty_Table = {}, ItemDefault = {}, ItemCurrent = {}

Grp_Table['sch'] = ['Default','First-Person Shooters','Simulation','Strategy','Roleplaying','Software','Reference']
// short name, long name, is default, layouts, optgroup
Dat_Table['sch'] =
[
	['Sample',		'Blank Sample',							1, 0,	[1,2,3,4,6,7,8]],
	['AVP',			'Alien vs. Predator',					0, 1,	[1,2]],
	['Alice',		'American McGee\'s Alice',				0, 1,	[1,2]],
	['Doom',		'DOOM & DOOM II: Hell on Earth',		0, 1,	[1,2]],
	['FarCry',		'FarCry',								0, 1,	[1,2]],
	['MBlade',		'Mount & Blade',						0, 1,	[1,2]],
	['SShock',		'System Shock',							0, 1,	[1,2]],
	['SShock2',		'System Shock 2',						0, 1,	[1,2]],
	['ThiefTDP',	'Thief: The Dark Project',				0, 1,	[1,2]],
	['Thief2TMA',	'Thief 2: The Metal Age',				0, 1,	[1,2]],
	['Tron20',		'TRON 2.0',								0, 1,	[1,2]],
	['Allegience',	'Allegience',							0, 2,	[1,2]],
	['AquaNox',		'AquaNox',								0, 2,	[1,2]],
	['BCM',			'Battlecruiser Millenium',				0, 2,	[1,2]],
	['BBlock',		'Beta Blocker',							0, 2,	[1,2]],
	['Elite2',		'Elite 2',								0, 2,	[1,2]],
	['EVNova',		'Escape Velocity: Nova',				0, 2,	[1,2]],
	['FST',			'Flight Sim Toolkit',					0, 2,	[1,2]],
	['FreeLancer',	'FreeLancer',							0, 2,	[1,2]],
	['FreeSpace',	'FreeSpace',							0, 2,	[1,2]],
	['FreeSpace2',	'FreeSpace 2',							0, 2,	[1,2]],
	['IWar',		'Independence War',						0, 2,	[1,2]],
	['IWar2',		'Independence War 2: Edge of Chaos',	0, 2,	[1,2]],
	['JUSAF',		'Jane\'s USAF',							0, 2,	[1,2]],
	['Nexus',		'Nexus: The Jupiter Incident',			0, 2,	[1,2]],
	['Privateer',	'Privateer',							0, 2,	[1,2]],
	['SHunter2',	'Silent Hunter 2',						0, 2,	[1,2]],
	['SC2K',		'SimCity 2000 (Win95)',					0, 2,	[1,2]],
	['SC3KU',		'SimCity 3000 Unlimited',				0, 2,	[1,2]],
	['SCity4',		'SimCity 4',							0, 2,	[1,2]],
	['STrans',		'SimuTrans',							0, 2,	[1,2]],
	['StarLancer',	'StarLancer',							0, 2,	[1,2]],
	['StarShatter',	'StarShatter',							0, 2,	[1,2]],
	['XWing',		'Star Wars: X-Wing',					0, 2,	[1,2]],
	['Tachyon',		'Tachyon: The Fringe',					0, 2,	[1,2]],
	['VStrike',		'Vega Strike',							0, 2,	[1,2,3]],
	['XTension',	'X-Tension',							0, 2,	[1,2]],
	['BFWesnoth',	'Battle for Wesnoth',					0, 3,	[1,2]],
	['BrigadeE5',	'Brigade E5: New Jagged Union',			0, 3,	[1,2]],
	['FForce',		'Freedom Force',						0, 3,	[1,2]],
	['GalCiv',		'Galactic Civilizations',				0, 3,	[1,2]],
	['GalCiv2',		'Galactic Civilizations II: Dread Lords',	0, 3,	[1,2]],
	['Homeworld',	'Homeworld & Homeworld: Cataclysm',		0, 3,	[1,2]],
	['Homeworld2',	'Homeworld 2',							0, 3,	[1,2]],
	['JAlliance',	'Jagged Alliance',						0, 3,	[1,2]],
	['JA2',			'Jagged Alliance 2',					0, 3,	[1,2]],
	['JA2113',		'Jagged Alliance 2 (1.13)',				0, 3,	[1,2]],
	['LSNemesis',	'Laser Squad: Nemesis',					0, 3,	[1,2]],
	['LoM',			'Lords of Magic',						0, 3,	[1,2]],
	['MythTFL',		'Myth: The Fallen Lords',				0, 3,	[1,2]],
	['ProjectE',	'Project Earth: Starmageddon',			0, 3,	[1,2]],
	['RTycoon2',	'Railroad Tycoon II',					0, 3,	[1,2]],
	['RomeTW',		'Rome: Total War',						0, 3,	[1,2]],
	['SStorm',		'Silent Storm',							0, 3,	[1,2]],
	['TitansOS',	'Titans of Steel: Warring Suns',		0, 3,	[1,2]],
	['TotalA',		'Total Annihilation',					0, 3,	[1,2]],
	['TAKingdoms',	'Total Annihilation: Kingdoms',			0, 3,	[1,2]],
	['Trop2',		'Tropico 2: Pirate Cove',				0, 3,	[1,2]],
	['UFOAI',		'UFO: Alien Invasion (Stable)',			0, 3,	[1,2]],
	['UFOAIdev',	'UFO: Alien Invasion (Development)',	0, 3,	[1,2]],
	['XApocBatt',	'X-COM: Apocalypse (Tactical)',			0, 3,	[1,2]],
	['XApocCity',	'X-COM: Apocalypse (Cityscape)',		0, 3,	[1,2]],
	['XConq',		'Xconq',								0, 3,	[1,2]],
	['BGate2',		'Baldur\'s Gate II: Shadows of Amn',	0, 4,	[1,2]],
	['DDivinity',	'Divine Divinity',						0, 4,	[1,2]],
	['Fallout',		'Fallout & Fallout 2',					0, 4,	[1,2]],
	['Fate',		'FATE & FATE: Undiscovered Realms',		0, 4,	[1,2]],
	['GearHead',	'GearHead',								0, 4,	[1,2]],
	['Gothic',		'Gothic',								0, 4,	[1,2]],
	['Gothic2',		'Gothic 2',								0, 4,	[1,2]],
	['Gothic3',		'Gothic 3',								0, 4,	[1,2]],
	['NetHack',		'NetHack',								0, 4,	[1,2]],
	['SRang2Main',	'Space Rangers 2 (Main)',				0, 4,	[1,2]],
	['SRang2Plan',	'Space Rangers 2 (RTS)',				0, 4,	[1,2]],
	['StarWolves',	'Star Wolves & Star Wolves 2',			0, 4,	[1,2]],
	['ToEE',		'Temple of Elemental Evil',				0, 4,	[1,2]],
	['LDView',		'LDView',								0, 5,	[1,2]],
	['Windows',		'Microsoft Windows',					0, 5,	[1,2]],
	['WAmp',		'Winamp',								0, 5,	[1,2]],
	['ScanCode',	'PC-AT Scan Codes',						0, 6,	[1,2,3,4]],
	['ScanCodeMac',	'Apple Virtual Key Codes',				0, 6,	[6,7,8]],
	['ASCII',		'ASCII/Unicode/HTML Codes',				0, 6,	[1,2,3,4]],
	['Flash',		'ActionScript 3.0 Key Codes',			0, 6,	[1,2]],
	['Typing',		'Typing Reference',						0, 6,	[1,3,4]],
	// Internal use only
	['Preview',		'Submission Preview',					0, 6,	[1,2,3,4,6,7,8]]
]
Grp_Table['sty'] = ['Dynamic Colors','Fixed Colors']
Dat_Table['sty'] =
[
	['SatWhite',	'Saturated Colors on White',			1, 0],
	['SatBlack',	'Saturated Colors on Black',			0, 0],
	['WarmWhite',	'Warm Colors on White',					0, 0],
	['WarmBlack',	'Warm Colors on Black',					0, 0],
	['GradeWht',	'Gradients on White',					0, 0],
	['GradeBlk',	'Gradients on Black',					0, 0],
	['Printer',		'Printer-Friendly',						0, 0],
	['FunKey',		'FunKeyBoard',							0, 1],
	['Kitty',		'Hello Kitty',							0, 1],
	['Doraemon',	'Doraemon',								0, 1],
	['Kozierok',	'Kozierok',								0, 1],
	['Savard',		'Savard',								0, 1]
//	['PlainWhite',	'Plain White',							0, 1],
//	['PlainBlack',	'Plain Black',							0, 1]
]
Grp_Table['lay'] = ['PC/AT','Macintosh']
Dat_Table['lay'] =
[
	['ATUS104',		'US 104 Key (ANSI)',		1, 0],
	['ATUS104DVO',	'US 104 Key (Dvorak)',		0, 0],
	['ATDE105',		'DE 105 Key (ISO)',			0, 0],
	['ATFR105',		'FR 105 Key (ISO)',			0, 0],
	['MACUS109OLD',	'US 109 Key (old)',			0, 1],
	['MACUS109NEW',	'US 109 Key (new)',			0, 1],
	['MACUK110OLD',	'UK 110 Key (old)',			0, 1]
]
Grp_Table['eff'] = []
Dat_Table['eff'] =
[
	[2,		'Invert Colors',		0],
	[4,		'X-Ray',				0],
	[8,		'Grayscale',			0],
	[16,	'Drop-Shadow',			0],
	[32,	'Rotate 90\u00b0',		0]
]
