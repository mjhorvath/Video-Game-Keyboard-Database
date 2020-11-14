sLay = 'ATUS104'
Sch_Table['gam'][sLay] = 'Earth & Beyond (PSW)'
Sch_Table['aut'][sLay] = ''
Sch_Table['key'][sLay] =
[
// class, caption, shift, ctrl, alt, altgr, extra, image
// legend
,
// row 1
['yel','Target\nSelf',,,,,,],
['red','Use\nSlot 1',,,,,,],
['red','Use\nSlot 2',,,,,,],
['red','Use\nSlot 3',,,,,,],
['red','Use\nSlot 4',,,,,,],
['red','Use\nSlot 5',,,,,,],
['red','Use\nSlot 6',,,,,,],
,
,
,
,
['mag','Zoom\nMap Out',,,,,,],
['mag','Zoom\nMap In',,,,,,],
['cyn','Sector Message',,,,,,],
// row 2
['mag','Nav Map /\nCycle Channel',,,,,,],
['grn','Warp',,,,,,],
['yel','Target\nNear\nNAV',,,,,,],
['yel','Target\nNear\nEnemy',,,,,,],
['cyn','Reply\nLast','Run','Reply\nMenu',,,,],
['grn','Formation',,'Target\nPos',,,,],
['mag','Character',,,,,,],
['mag','Missions',,,,,,],
['mag','Equipment','Vault','Ping',,,,],
['mag','Cargo',,,,,,],
['yel','Target\nPrev (All)',,'Player\nPos',,,,],
['grn','Stop',,,,,,],
['grn','Move\nForward\nLock',,'Move\nBack\nLock',,,,],
,
// row 3
,
['grn','Move\nForward',,,,,,],
['yel','Target\nNear\nFriend',,,'Sell\nItem',,,],
['yel','Target\nNext\n(Context)',,,,,,],
['red','Fire All\nWeapons',,,,,,],
['mag','Galaxy\nMap',,,,,,],
['yel','Target\nNear\nAttacker',,,,,,],
['cyn','Chat\nTarget',,,,,,],
['cyn','Chat\nLocal',,,,,,],
['cyn','Chat\nGroup',,,,,,],
['cyn','Chat\nGuild',,,,,,],
['cyn','Chat\nRadio',,,,,,],
,
['cyn','Channel\nMessage',,,,,,],
// row 4
,
,
['grn','Move\nBackward',,,,,,],
['yel','Target\nNear\nObject',,,,,,],
['yel','Target\nPrev\n(Context)',,,,,,],
['mag','Toggle\nAuto-level',,,,,,],
[,,,,'Buy\nItem',,,],
['yel','Target\nNext (All)',,,,,,],
['mag','Nav\nMap',,,,,,],
,
,
['cyn','Type\nCommand',,,,,,],
,
// row 5
,
['red','Toggle\nAlternate\nSlots',,,,,,],
['grn','Auto-follow',,,,,,],
['red','Toggle\nAlternate\nSlots',,,,,,],
,
// middle
,
,
['grn','Turn\nLeft',,,,,,],
['blu','Restore\nCamera',,,,,,],
,
['grn','Tilt\nUp','Scroll\nUp',,'Prev\nEphm',,,],
['grn','Tilt\nDown','Scroll\nDown',,'Next\nEphm',,,],
[,,'Page\nUp',,,,,],
[,,'Page\nDown',,,,,],
['grn','Turn\nRight',,,,,,],
// numpad
['grn','Toggle\nForward',,,,,,],
,
,
,
,
,
,
,
,
,
,
,
,
,
,
,
,
// row 0
['mag','Quit Game /\nCancel\nChat',,,,,,],
['mag','Help',,'Send\nChat1','Save\nChat1',,,],
['yel','Group\n1',,'Send\nChat2','Save\nChat2',,,],
['yel','Group\n2',,'Send\nChat3','Save\nChat3',,,],
['yel','Group\n3',,'Send\nChat4','Save\nChat4',,,],
['yel','Group\n4',,'Send\nChat5','Save\nChat5',,,],
['yel','Group\n5',,'Send\nChat6','Save\nChat6',,,],
['yel','Near\nPlayer',,'Send\nChat7','Save\nChat7',,,],
['yel','Near\nEnemy',,'Send\nChat8','Save\nChat8',,,],
['blu','1st/3rd\nPerson\nCamera',,'Send\nChat9','Save\nChat9',,,],
['blu','Next\nCamera','Prev\nCam','Save\nCam',,,,],
,
,
,
['mag','Screen-\nshot',,,,,,],
,
// special
,
,
,
// extra Mac keys
,
,
,
,
,
,
,
,
,
,
,

]
Sch_Table['leg'][sLay] =
[
['red','Combat/Actions'],
['yel','Targeting'],
['grn','Movement'],
['blu','Camera'],
['cyn','Chat'],
['mag','Game Controls']
]
Sch_Table['com'][sLay] =
[
'CTRL + Mouse XY = Rotate Camera',
'SHIFT + Mouse Y = Zoom Camera In/Out'
]
Sch_Table['mou'][sLay] =
[
'Left Click on Object = Select Target',
'Right Click on Space = Move Ship',
'Right Click on VDU = Clear Target'
]
Sch_Table['joy'][sLay] =
[
]
Sch_Table['add'][sLay] =
[
'/abort logoff = Aborts a logoff sequence.',
'/accept = Accepts a formation request issued by your group leader.',
'/assist = Acquires the target of your current target.',
'/assistme = Sends a help request to all members of your group.',
'/break = Breaks formation.',
'/credit kill = Allows you to designate another player to receive credit for any damage you contribute toward the kill of a mob.',
'Damage credited this way is only considered in determining looting rights.',
'/dive = Causes your ship to perform an inverted loop.',
'/follow = Causes you to follow your current target.  May only be used on non-players and players within your group.',
'/formation <type> = If group leader, allows setting of formation type.',
'/fps = Toggles the framerate display.',
'/gc create = Creates a new guild.',
'/gc promote = Promotes the targeted player one rank within your guild.',
'/gc demote = Demotes the targeted player one rank within your guild.',
'/group accept = Accepts a grouping invitation.',
'/group disband = Disbands your group if leader, otherwise causes you to leave your group.',
'/group help = Displays a list of grouping commands.',
'/group invite = Issues a grouping invitation to your current target.',
'/group kick = If leader, removes targeted member from group.',
'/group refuse = Refuses a grouping invitation.',
'/logoff = Initiates a logoff.',
'/loop = Causes your ship to execute a vertical loop.',
'/played = Displays your character\'s creation date and total time played.',
'/r = Opens the \'reply\' interface to allow replying to someone who has spoken to you recently.',
'/register = Displays the base that will rescue you should you become incapacitated.',
'/reject = Rejects a formation request issued by your group leader.',
'/reply = Opens the \'reply\' interface to allow replying to someone who has spoken to you recently.',
'/resetcolor = Resets all message types to the game-default colors.',
'/resethud = Resets HUD values set by /sethud.',
'/roll = Causes your ship to execute a barrel roll. ',
'/seecolor = Displays the current color setting for various messaging types.  Type /seecolor for usage.',
'/self tag <on|off> = Displays or hides your avatar\'s overhead name from your view.',
'/setcolor = Allows setting of colors for various messaging types.  Type /setcolor for usage.',
'/sethud = Allows setting of various HUD display values.  Type /sethud for usage.',
'/spin = Causes your ship to spin on its axis.',
'/starbase reset  = Resets your character to a safe spot if stuck in a starbase.',
'/t <player> <msg> = Sends the specified player a private message.',
'/tell <player> <msg> = Sends the specified player a private message.',
'/time = Gives the current local time and the server time (GMT).',
'/who = Displays a list of all players in the starbase or sector you are in.',
'/list credit kill = Reports who a player is reassigning kill credit to and who is assigning kill credit to that player.'
]
sLay = 'ATUS104DVO'
Sch_Table['gam'][sLay] = Sch_Table['gam']['ATUS104']
Sch_Table['aut'][sLay] = Sch_Table['aut']['ATUS104']
Sch_Table['key'][sLay] = Sch_Table['key']['ATUS104']
Sch_Table['leg'][sLay] = Sch_Table['leg']['ATUS104']
Sch_Table['com'][sLay] = Sch_Table['com']['ATUS104']
Sch_Table['mou'][sLay] = Sch_Table['mou']['ATUS104']
Sch_Table['joy'][sLay] = Sch_Table['joy']['ATUS104']
Sch_Table['add'][sLay] = Sch_Table['add']['ATUS104']
