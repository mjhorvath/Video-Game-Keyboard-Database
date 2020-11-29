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
var CaptionsTable = ['Normal Key','SHIFT + Key','CTRL + Key','ALT + Key','ALTGR + Key','Extra Action']
var ActionsTable = ['Key','ID','Normal Key','Group','SHIFT + Key','Group','CTRL + Key','Group','ALT + Key','Group','ALTGR + Key','Group','Extra Action','Group','Icon File','Icon URI']
var SelectsTable = ['gam','sty','lay']

var GroupColors = {non:0,red:1,yel:2,grn:3,cyn:4,blu:5,mag:6,wht:7,gry:8,blk:9,org:10,olv:11,brn:12}
var GamColumns = ['binding_id','record_id','key_number','normal_action','normal_group','shift_action','shift_group','ctrl_action','ctrl_group','alt_action','alt_group','altgr_action','altgr_group','extra_action','extra_group','image_file','image_uri']
var LayColumns = ['position_id','layout_id','key_number','position_left','position_top','position_width','position_height','symbol_norm_cap','symbol_norm_low','symbol_altgr_cap','symbol_altgr_low','lowcap_optional','numpad']

var NotesCount = {mou:0,joy:0,com:0,add:0}
var ItemWords = ['game','style','layout','effect']
var ItemPrefixes = ['gam','sty','lay','eff']
var NamePrefixes = ['aut','tit']
var NotePrefixes = ['com','mou','joy','add']
var GamePrefixes = ['com','mou','joy','add','aut','leg','key','tit']
var TextPrefixes = ['com','mou','joy','add','aut','leg','ret']
var LayoPrefixes = ['tit','uni','bla','key']
var NumberOfKeys = 0, ConvertUnits = 1, KeyPadding = 2, CutPadding = 2, DisableLayouts = true
var Dat_Table = {}, Grp_Table = {}, Gam_Table = {}, Lay_Table = {}, Sty_Table = {}, ItemDefault = {}, ItemCurrent = {}

CurrentItem['gam']
CurrentItem['sty']
CurrentItem['lay']

for (var i = 0, n = GamePrefixes.length; i < n; i++)
{
	Gam_Table[GamePrefixes[i]] = {}
}

Grp_Table['gam'] = ['Default','First-Person Shooters','Simulation','Strategy','Roleplaying','Software','Reference']
// short name, long name, is default, optgroup, layouts
Dat_Table['gam'] =
[
	['Sample',		'Blank Sample',							1, 0,	[0,2,3,5,6]],
	['VStrike',		'Vega Strike',							0, 2,	[0,2]]

//	['Sample',		'Blank Sample',							1, 0,	[0,2,3,5,6]],
//	['AVP',			'Alien vs. Predator',					0, 1,	[0]],
//	['Alice',		'American McGee\'s Alice',				0, 1,	[0]],
//	['Doom',		'DOOM & DOOM II: Hell on Earth',		0, 1,	[0]],
//	['FarCry',		'FarCry',								0, 1,	[0]],
//	['MBlade',		'Mount & Blade',						0, 1,	[0]],
//	['SShock',		'System Shock',							0, 1,	[0]],
//	['SShock2',		'System Shock 2',						0, 1,	[0]],
//	['ThiefTDP',	'Thief: The Dark Project',				0, 1,	[0]],
//	['Thief2TMA',	'Thief 2: The Metal Age',				0, 1,	[0]],
//	['Tron20',		'TRON 2.0',								0, 1,	[0]],
//	['Allegience',	'Allegience',							0, 2,	[0]],
//	['AquaNox',		'AquaNox',								0, 2,	[0]],
//	['BCM',			'Battlecruiser Millenium',				0, 2,	[0]],
//	['BBlock',		'Beta Blocker',							0, 2,	[0]],
//	['Elite2',		'Elite 2',								0, 2,	[0]],
//	['EVNova',		'Escape Velocity: Nova',				0, 2,	[0]],
//	['FST',			'Flight Sim Toolkit',					0, 2,	[0]],
//	['FreeLancer',	'FreeLancer',							0, 2,	[0]],
//	['FreeSpace',	'FreeSpace',							0, 2,	[0]],
//	['FreeSpace2',	'FreeSpace 2',							0, 2,	[0]],
//	['IWar',		'Independence War',						0, 2,	[0]],
//	['IWar2',		'Independence War 2: Edge of Chaos',	0, 2,	[0]],
//	['JUSAF',		'Jane\'s USAF',							0, 2,	[0]],
//	['Nexus',		'Nexus: The Jupiter Incident',			0, 2,	[0]],
//	['Privateer',	'Privateer',							0, 2,	[0]],
//	['SHunter2',	'Silent Hunter 2',						0, 2,	[0]],
//	['SC2K',		'SimCity 2000 (Win95)',					0, 2,	[0]],
//	['SC3KU',		'SimCity 3000 Unlimited',				0, 2,	[0]],
//	['SCity4',		'SimCity 4',							0, 2,	[0]],
//	['STrans',		'SimuTrans',							0, 2,	[0]],
//	['StarLancer',	'StarLancer',							0, 2,	[0]],
//	['StarShatter',	'StarShatter',							0, 2,	[0]],
//	['XWing',		'Star Wars: X-Wing',					0, 2,	[0]],
//	['Tachyon',		'Tachyon: The Fringe',					0, 2,	[0]],
//	['VStrike',		'Vega Strike',							0, 2,	[0,2]],
//	['XTension',	'X-Tension',							0, 2,	[0]],
//	['BFWesnoth',	'Battle for Wesnoth',					0, 3,	[0]],
//	['BrigadeE5',	'Brigade E5: New Jagged Union',			0, 3,	[0]],
//	['FForce',		'Freedom Force',						0, 3,	[0]],
//	['GalCiv',		'Galactic Civilizations',				0, 3,	[0]],
//	['GalCiv2',		'Galactic Civilizations II: Dread Lords',	0, 3,	[0]],
//	['Homeworld',	'Homeworld & Homeworld: Cataclysm',		0, 3,	[0]],
//	['Homeworld2',	'Homeworld 2',							0, 3,	[0]],
//	['JAlliance',	'Jagged Alliance',						0, 3,	[0]],
//	['JA2',			'Jagged Alliance 2',					0, 3,	[0]],
//	['JA2113',		'Jagged Alliance 2 (1.13)',				0, 3,	[0]],
//	['LSNemesis',	'Laser Squad: Nemesis',					0, 3,	[0]],
//	['LoM',			'Lords of Magic',						0, 3,	[0]],
//	['MythTFL',		'Myth: The Fallen Lords',				0, 3,	[0]],
//	['ProjectE',	'Project Earth: Starmageddon',			0, 3,	[0]],
//	['RTycoon2',	'Railroad Tycoon II',					0, 3,	[0]],
//	['RomeTW',		'Rome: Total War',						0, 3,	[0]],
//	['SStorm',		'Silent Storm',							0, 3,	[0]],
//	['TitansOS',	'Titans of Steel: Warring Suns',		0, 3,	[0]],
//	['TotalA',		'Total Annihilation',					0, 3,	[0]],
//	['TAKingdoms',	'Total Annihilation: Kingdoms',			0, 3,	[0]],
//	['Trop2',		'Tropico 2: Pirate Cove',				0, 3,	[0]],
//	['UFOAI',		'UFO: Alien Invasion (Stable)',			0, 3,	[0]],
//	['UFOAIdev',	'UFO: Alien Invasion (Development)',	0, 3,	[0]],
//	['XApocBatt',	'X-COM: Apocalypse (Tactical)',			0, 3,	[0]],
//	['XApocCity',	'X-COM: Apocalypse (Cityscape)',		0, 3,	[0]],
//	['XConq',		'Xconq',								0, 3,	[0]],
//	['BGate2',		'Baldur\'s Gate II: Shadows of Amn',	0, 4,	[0]],
//	['DDivinity',	'Divine Divinity',						0, 4,	[0]],
//	['Fallout',		'Fallout & Fallout 2',					0, 4,	[0]],
//	['Fate',		'FATE & FATE: Undiscovered Realms',		0, 4,	[0]],
//	['GearHead',	'GearHead',								0, 4,	[0]],
//	['Gothic',		'Gothic',								0, 4,	[0]],
//	['Gothic2',		'Gothic 2',								0, 4,	[0]],
//	['Gothic3',		'Gothic 3',								0, 4,	[0]],
//	['NetHack',		'NetHack',								0, 4,	[0]],
//	['SRang2Main',	'Space Rangers 2 (Main)',				0, 4,	[0]],
//	['SRang2Plan',	'Space Rangers 2 (RTS)',				0, 4,	[0]],
//	['StarWolves',	'Star Wolves & Star Wolves 2',			0, 4,	[0]],
//	['ToEE',		'Temple of Elemental Evil',				0, 4,	[0]],
//	['LDView',		'LDView',								0, 5,	[0]],
//	['Windows',		'Microsoft Windows',					0, 5,	[0]],
//	['WAmp',		'Winamp',								0, 5,	[0]],
//	['ScanCode',	'PC-AT Scan Codes',						0, 6,	[0,2,3]],
//	['ScanCodeMac',	'Apple Virtual Key Codes',				0, 6,	[5,6,7]],
//	['ASCII',		'ASCII/Unicode/HTML Codes',				0, 6,	[0,1,2,3]],
//	['Flash',		'ActionScript 3.0 Key Codes',			0, 6,	[0,1]],
//	['Typing',		'Typing Reference',						0, 6,	[0,2,3]],
//	['Preview',		'Submission Preview',					0, 6,	[0,2,3,5,6]]			// Internal use only
]
Grp_Table['sty'] = ['Dynamic Colors','Fixed Colors']
Dat_Table['sty'] =
[
	['SatWhite',	'Saturated Colors on White',			1, 0]

//	['SatWhite',	'Saturated Colors on White',			1, 0],
//	['SatBlack',	'Saturated Colors on Black',			0, 0],
//	['WarmWhite',	'Warm Colors on White',					0, 0],
//	['WarmBlack',	'Warm Colors on Black',					0, 0],
//	['GradeWht',	'Gradients on White',					0, 0],
//	['GradeBlk',	'Gradients on Black',					0, 0],
//	['Printer',		'Printer-Friendly',						0, 0],
//	['FunKey',		'FunKeyBoard',							0, 1],
//	['Kitty',		'Hello Kitty',							0, 1],
//	['Doraemon',	'Doraemon',								0, 1],
//	['Kozierok',	'Kozierok',								0, 1],
//	['Savard',		'Savard',								0, 1]
]
Grp_Table['lay'] = ['PC/AT','Macintosh']
Dat_Table['lay'] =
[
	['ATUS104',		'US 104 Key (ANSI)',		1, 0],
	null,
	['ATDE105',		'DE 105 Key (ISO)',			0, 0]

//	['ATUS104',		'US 104 Key (ANSI)',		1, 0],
//	null,
//	['ATDE105',		'DE 105 Key (ISO)',			0, 0]
//	['ATFR105',		'FR 105 Key (ISO)',			0, 0],
//	['MACUS109OLD',	'US 109 Key (old)',			0, 1],
//	['MACUS109NEW',	'US 109 Key (new)',			0, 1],
//	['MACUK110OLD',	'UK 110 Key (old)',			0, 1]
]
Grp_Table['eff'] = []
// could start this table with 1 instead of 2
Dat_Table['eff'] =
[
	[1,		'Invert Colors',		0],
	[2,		'X-Ray',				0],
	[4,		'Grayscale',			0],
	[8,		'Drop-Shadow',			0],
	[16,	'Rotate 90\u00b0',		0]
]
