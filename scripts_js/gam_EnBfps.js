sLay = 'ATUS104'
Gam_Table['gam'][sLay] = 'Earth & Beyond (FPS)'
Gam_Table['aut'][sLay] = ''
Gam_Table['key'][sLay] =
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
['cyn','Chat\nSector',,,,,,],
// row 2
['mag','Nav Map /\nCycle Channels',,,,,,],
['grn','Tilt\nUp',,,,,,],
['grn','Move\nForward',,,,,,],
['grn','Tilt\nDown',,,,,,],
['cyn','Reply\nLast','Run','Reply\nMenu',,,,],
['yel','Target\nNear\nEnemy',,'Target\nPos',,,,],
['yel','Target\nNear\nNAV',,,,,,],
['mag','Missions','Character',,,,,],
['mag','Equip-\nment','Vault','Ping',,,,],
['mag','Cargo',,,,,,],
['yel','Target\nPrev (all)',,'Player\nPos',,,,],
['grn','Stop',,,,,,],
['grn','Move\nForward\nLock',,'Move\nBack\nLock',,,,],
,
// row 3
['yel','Target\nNear\nAttacker',,,,,,],
['grn','Turn\nLeft',,,,,,],
['grn','Move\nBackward',,,'Sell\nItem',,,],
['grn','Turn\nRight',,,,,,],
['grn','Auto-\nfollow',,,,,,],
['yel','Target\nNear\nFriend',,,,,,],
['yel','Target\nNear\nObject',,,,,,],
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
['grn','Warp',,,,,,],
['yel','Target\nPrev\n(Context)',,,,,,],
['yel','Target\nNext\n(Context)',,,,,,],
['mag','Auto-level',,,,,,],
[,,,,'Buy\nItem',,,],
['yel','Target\nNext (all)',,,,,,],
['mag','Nav\nMap','Galaxy\nMap',,,,,],
['grn','Formation',,,,,,],
,
['cyn','Type\nCommand',,,,,,],
,
// row 5
,
['red','Toggle\nAlternate\nSlots',,,,,,],
['red','Fire All Weapons',,,,,,],
['red','Toggle\nAlternate\nSlots',,,,,,],
,
// middle
,
,
,
['blu','Restore\nCamera',,,,,,],
,
['grn','Move\nForward','Scroll\nUp',,,,,],
['grn','Move\nBackward','Scroll\nDown',,,,,],
['grn','Tilt\nUp','Page\nUp',,,,,],
['grn','Tilt\nDown','Page\nDown',,,,,],
,
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
['yel','Target\nNear\nPlayer',,'Send\nChat7','Save\nChat7',,,],
['yel','Target\nNear\nEnemy',,'Send\nChat8','Save\nChat8',,,],
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
Gam_Table['leg'][sLay] =
[
['red','Combat/Actions'],
['yel','Targeting'],
['grn','Movement'],
['blu','Camera'],
['cyn','Chat'],
['mag','Game Controls']
]
Gam_Table['com'][sLay] =
[
'CTRL + Mouse XY = Rotate Camera',
'SHIFT + Mouse Y = Zoom Camera In/Out'
]
Gam_Table['mou'][sLay] =
[
'Left Click on Object = Select Target',
'Right Click on Space = Move Ship',
'Right Click on VDU = Clear Target'
]
Gam_Table['joy'][sLay] =
[
]
Gam_Table['add'][sLay] =
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
Gam_Table['gam'][sLay] = Gam_Table['gam']['ATUS104']
Gam_Table['aut'][sLay] = Gam_Table['aut']['ATUS104']
Gam_Table['key'][sLay] = Gam_Table['key']['ATUS104']
Gam_Table['leg'][sLay] = Gam_Table['leg']['ATUS104']
Gam_Table['com'][sLay] = Gam_Table['com']['ATUS104']
Gam_Table['mou'][sLay] = Gam_Table['mou']['ATUS104']
Gam_Table['joy'][sLay] = Gam_Table['joy']['ATUS104']
Gam_Table['add'][sLay] = Gam_Table['add']['ATUS104']
