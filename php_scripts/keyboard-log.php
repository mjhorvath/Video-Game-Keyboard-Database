<?php
	header('Content-Type: text/html; charset=utf8');
	$path_root	= "../";
	$page_title	= "VGKD - Change Log";
	$page_desc	= "Visual keyboard hotkey & binding diagrams for video games and other software.";
	$page_keywords	= "visual,keyboard,keys,diagrams,charts,overlay,shortcuts,bindings,mapping,maps,controls,hotkeys,database,print,printable,video game,software,guide,reference";
	$foot_array	= array("copyright","license_kbd");
	$is_short	= false;
	include($path_root . 'ssi/normalpage.php');
	print($page_top);
?>
<h2>News &amp; Updates</h2>
<ul>
	<li>2019/10/16: Fixed bug in submission form.</li>
	<li>2019/10/16: Added two English bindings for <i>ATOM RPG</i>, <i>Mutant Year Zero</i>, <i>Freespace 2</i>, <i>Undead Defense</i>, <i>The Watchers</i>.</li>
	<li>2019/07/21: Added two Polish binding schemes and one Spanish binding scheme submitted by visitors to the site.</li>
	<li>2019/07/21: Added the "PL 104 Key (Programmers)" layout.</li>
	<li>2019/07/21: Added support for displaying both lowercase and uppercase AltGr commands in a layout. This is usually disabled however. I may redress this at a later date.</li>
	<li>2019/04/12: Added bindings for the "Keyboard Scan Codes" scheme and the "ES 105 Key" layout.</li>
	<li>2019/04/10: Lots of CSS tweaks. Renamed several files back to the old "embed" conventions. Created the "captions" table to store labels that go into the key cap legends, although it is currently still empty. Switched the order in which key captions are rendered in the submissions form so that the "Grayscale" theme works better.</li>
	<li>2019/04/09: Switched to using flexbox for combo boxes.</li>
	<li>2019/04/06: CSS tweaks. Added an "layout_language" column to the database. Fixed icon images not showing up behind keys. Link to "normalize.css" got broken at some point.</li>
	<li>2019/04/04: Added bindings for <i>Secret World Legends</i>. This brings the total number of games up to 200, and the total number of bindings to 233!</li>
	<li>2019/04/03: Added bindings for <i>Rebel Galaxy</i>.</li>
	<li>2019/04/03: Fixed many minor errors in CSS color definitions.</li>
	<li>2019/04/02: Created bindings for the "Test Sample" and "Database Key Numbers" reference materials for the "US 104 Key (Dvorak)", "UK 105 Key (ISO)" and "ES 105 Key (ISO)" keyboards.</li>
	<li>2019/04/02: Added bindings for <i>X Rebirth</i> and <i>X4: Foundations</i>.</li>
	<li>2019/04/02: Merged the HTML and SVG formats into one format. The diagrams are no longer rendered using pure HTML code, and the SVG file is now once again embedded within an HTML wrapper. (This is how the old "embed" format worked.) Not sure what effect this will have on search engines, however.</li>
	<li>2019/04/01: Tweaked diagram dimensions. The gaps between the different sections of keys are now 1/2 unit in width. The borders around the edges are now 1/4 unit wide.</li>
	<li>2019/04/01: Added bindings for <i>Apex Legends</i> and <i>Tom Clancy's Rainbow Six Siege</i>.</li>
	<li>2019/03/30: Fixed alphabetical sort order of acronyms on front page.</li>
	<li>2019/03/30: Fixed some minor issues with the alphabetical list.</li>
	<li>2019/03/28: Moved all font files to a different folder.</li>
	<li>2019/03/28: Added the "US 104 Key (Dvorak)" layout.</li>
	<li>2019/03/28: Added bindings for <i>World of Tanks</i> and <i>Counter-Strike: Global Offensive</i>.</li>
	<li>2019/03/27: The diagram legends are now sorted by legend group.</li>
	<li>2019/03/27: Updated the <i>Elite Dangerous</i> keyboard and mouse controls.</li>
	<li>2019/03/26: Style selection dialog in page footers is now alphabetized and organized into groups.</li>
	<li>2019/03/25: Replaced loading icon with one I created myself.</li>
	<li>2019/03/24: Added the "UK 105 Key (ISO)" and "ES 105 Key (ISO)" keyboards. They only have the "Blank Starter" bindings at the moment, however.</li>
	<li>2019/03/24: Fixed one of the columns in the "positions" table was not set to auto-increment.</li>
	<li>2019/03/24: Discovered and documented an issue related to the bit(1) datatype and importing of CSV files.</li>
	<li>2019/03/24: Added bindings for <i>Kenshi</i>.</li>
	<li>2019/03/24: Reversed the order of the "Game" and "Theme" selection dialogs.</li>
	<li>2019/03/24: Made changes to the "Spawn New Diagram" button in the frontend.</li>
	<li>2019/03/24: Moved several stylesheets from the website's main directory to the local keyboard directory.</li>
	<li>2019/03/23: Added OBS Production Profile bindings by Josiah Stearns.</li>
	<li>2019/03/22: Fixed key ID less by 1 when making a submission.</li>
	<li>2019/03/21: Tweaked LCH styles.</li>
	<li>2019/03/21: Fixed missing leading zeros in "Keyboard Scan Codes" scheme.</li>
	<li>2019/03/21: Fixed legend order bug in submission form.</li>
	<li>2019/03/19: Renamed the "Blank Sample" scheme to "Blank Starter".</li>
	<li>2019/03/19: Adjusted the bottom row of keys so they better match standard ANSI/ISO layouts.</li>
	<li>2019/03/18: Genre names were missing from the alphabetical master list.</li>
	<li>2019/03/18: Updated scripts to reflect that there is no longer a display order column in the genres table.</li>
	<li>2019/03/18: You now select the keyboard before the game in the main page.</li>
	<li>2019/03/11: You now need to fill the message form completely and correctly before data is sent to my mailbox.</li>
	<li>2019/03/11: Loading image should load a little faster.</li>
	<li>2019/03/11: The submission form now sends the data to me using UTF-8 encoding. (Sorry!)</li>
	<li>2019/03/11: The script now checks first if the reCaptcha box has been checked before enabling the "Submit Data" button.</li>
	<li>2019/03/11: There was a bug preventing the code from signaling that the legend had been altered.</li>
	<li>2018/06/28: Had to replace all stored procedures with regular SELECT calls since my Web host disabled the creation (and storage) of new routines.</li>
	<li>2018/06/24: Made several JavaScript-related fixes to the submission form.</li>
	<li>2018/06/23: Switched to Google's reCAPTCHA for the submission form.</li>
	<li>2018/06/21: Started making a "to do" list. You can view it <a href="to_do_list.txt">here</a>.</li>
	<li>2018/06/20: Fixed some PHP loop code that was spawning warnings in the error log.</li>
	<li>2018/06/20: Changed the license for the PHP and JavaScript code to GPLv3. (The database contents and chart designs remain licensed under the CC BY-SA 3.0 license.)</li>
	<li>2018/06/19: Finished the graphical submission form I started working on. Start with a <a href="keyboard-diagram-blank-sample.php?sty=15&lay=1&fmt=3">blank sample</a>.</li>
	<li>2018/06/06: Taz sent me updated bindings for <i>The Lord of the Rings Online</i>. Thanks Taz!</li>
	<li>2018/06/05: Started working on a GUI tool to submit bindings to this project. It is incomplete, but you can <s><a href="keyboard-submit.html">view my progress so far</a></s>. [ed. dead link]</li>
	<li>2018/06/05: Tweaked some HTML and CSS code. Added box borders to the default style.</li>
	<li>2018/06/04: You can now select between the HTML, SVG and MediaWiki formats from the front page and charts. Hopefully, I can get PDF output working sometime soon.</li>
	<li>2018/06/04: Renamed the "svg" URL query to "fmt" since there are now more than two formats.</li>
	<li>2018/06/04: Fixed a bug in URL query detection.</li>
	<li>2018/05/30: Created a <a target="_blank" href="https://github.com/mjhorvath/vgkd">GitHub repository</a> for this project.</li>
	<li>2018/05/29: Renamed the project from "Video Game Hotkey Maps" back to "Video Game Keyboard Diagrams".</li>
	<li>2018/05/29: Updated the printing tips with new info on potential problems and workarounds.</li>
	<li>2018/05/25: Made tweaks to style sheets and meta tags in order to (somewhat) better support mobile browsers.</li>
	<li>2018/05/25: Fixed a bug that was causing some browsers to not be able to switch between the HTML and SVG versions of the diagrams.</li>
	<li>2018/05/25: Old URLs are now being properly converted to the new URL syntax.</li>
	<li>2018/05/23: Added bindings for <i>PlayerUnknown's Battlegrounds</i>, <i>Fortnite</i>, <i>Overwatch</i>, <i>Far Cry 5</i> and <i>Everspace</i>. I've only played the last one, but hopefully people will appreciate the others.</li>
	<li>2018/05/18: Made changes to the accordion menu in the front page. Made changes to the alphabetical list (now renamed to "master list").</li>
	<li>2018/05/14: Added a button to the bottom of each chart to quickly switch between SVG and HTML rendering.</li>
	<li>2018/05/14: Added bindings for <i>Battletech</i> and <i>Stellar Tactics</i>.</li>
	<li>2018/05/14: Fixed a bug preventing charts from being loaded if the <tt>gam</tt>, <tt>sty</tt>, <tt>lay</tt> and <tt>svg</tt> parameters were missing from the URL.</li>
	<li>2017/05/29: I removed the Dvorak layout, since it was only used once, and was never properly implemented in the first place.</li>
	<li>2017/05/29: You can now switch back and forth between SVG and HTML versions of the charts by simply changing the <tt>&amp;svg=</tt> URL parameter to equal 1 or 0.</li>
	<li>2017/01/04: Altered several styles' appearances.</li>
	<li>2017/01/04: Created SVG versions of the charts (<a href="keyboard-chart.php?gam=1&sty=15&lay=1&svg=1">here's a sample</a>). They are less compatible with older browsers, but are better compatible with different wiki software. Once again, remember to change the numbers after the "gam=" and "lay=" portions of the URL to the correct game and layout ID.</li>
	<li>2017/01/04: Renamed the website from "Video Game Keyboard Control Charts" to "Video Game Keyboard Diagrams".</li>
	<li>2017/01/04: Switched the order of the "Games", "Styles" and "Layouts" menus on the front page to "Games", "Layouts" and "Styles".</li>
	<li>2017/01/04: Checks are now done to make sure each style is only applied to the proper layout. Non-matching styles are now grayed-out in the front page menu, and error messages are now printed to the chart screen.</li>
	<li>2016/12/23: Added bindings for <i>Windows 7</i>, <i>Neverwinter Nights</i>, <i>Neverwinter Nights 2</i>, <i>Psychonauts</i>, <i>The Age of Decadence</i>, <i>Shadowrun: Dragonfall</i> and <i>Pillars of Eternity</i>.</li>
	<li>2016/12/22: The <a href="keyboard-list.php">alphabetical game list</a> now shows multiple platforms/layouts for each game.</li>
	<li>2016/12/22: The alphabet letter keys no longer show the lowcaps letter. This adds a bit of extra room on each key for the caption strings.</li>
	<li>2016/12/22: Solved a many-to-many relationship issue by adding a new associative table to the database. As a bonus, it's now possible to credit people who submit bindings, layouts and stylesheets directly on the chart pages.</li>
	<li>2016/12/20: Renamed "Blank Sample" to "Test Sample".</li>
	<li>2016/12/20: Made a version of this test sample for every layout.</li>
	<li>2016/12/20: Added bindings for <i>Icewind Dale Enhanced Edition</i>.</li>
	<li>2016/12/20: Renamed the Apple keyboard layouts to include the keyboard model number. Also fixed a bug preventing the last key on these keyboards to be displayed.</li>
	<li>2016/12/20: Added an "Database Key Numbers" item to the "Reference" category. It shows the default key numbers for each keyboard layout.</li>
	<li>2016/12/20: Removed all bindings for the Dvorak keyboard. I didn't approach this properly, and need to start over from scratch.</li>
	<li>2016/12/18: Updated the URLs to "SEO friendly" URLs. Old links should still work, however.</li>
	<li>2016/12/16: Created a PHP script to autogenerate the MediaWiki code for each game. This can then be used with the MediaWiki template I created earlier.</li>
	<li>2016/07/28: Added an <a href="keyboard-list.php">alphabetical game list</a> of charts for those people who do not have JavaScript enabled in their browser.</li>	
	<li>2016/07/20: You can now select a different visual style from within the chart itself.</li>
	<li>2016/05/19: Tweaked the text at the bottom of the charts page.</li>
	<li>2016/05/19: Updated printing tips, and linked to the relevant section on the main page instead of a dedicated "Printing Tips" page.</li>
	<li>2016/05/19: Renamed the "First-Person Shooters" category to "Action".</li>
	<li>2016/05/19: Updated bindings for <i>Star Wolves</i> series.</li>
	<li>2016/05/19: Added bindings for <i>TES III</i>, <i>TES V</i>, <i>Metal Gear Rising: Revengeance</i>, <i>Metal Gear Solid V: The Phantom Pain</i> and <i>The Witcher 3</i>. This brings the total up to 150 games!</li>
	<li>2015/01/09: Updated all PHP scripts to a newer version.</li>
	<li>2015/01/09: Deleted the submissions page. Submissions should be made using the provided spreadsheet instead.</li>
	<li>2014/12/31: Added bindings for <i>Kerbal Space Program</i>, <i>Elite: Dangerous</i>, <i>The Witcher</i>, <i>The Witcher 2</i>, <i>Star Citizen</i>, <i>Divinity: Original Sin</i>, <i>The Dark Mod</i>, <i>OpenXcom</i>, <i>RPG Maker VX Ace</i>, <i>Xenonauts</i>, <i>S.T.A.L.K.E.R.: Shadow of Chernobyl</i>.</li>
	<li>2014/12/31: Added the Shiny Glass style.</li>
	<li>2014/12/28: Added bindings for <i>Blackguards</i>, <i>Minecraft</i>, <i>Shadowrun Returns</i>, <i>KotOR</i>, <i>Euro Truck Simulator 2</i>, <i>Wasteland 2</i>.</li>
	<li>2014/12/26: Updated from PHP MySQL to PHP MySQLi. Repaired the submissions page. Implemented stored procedures.</li>
	<li>2011/01/03: Added bindings for <i>The Lord of the Rings Online</i> and <i>Counter-Strike</i>.</li>
	<li>2010/07/05: Added bindings for <i>Celestia</i> and <i>GeoGebra</i>.</li>
	<li>2010/05/30: Added bindings for <i>Avernum 5</i>.</li>
	<li>2010/05/29: Removed unused keys from the database schema to reduce space.</li>
	<li>2010/05/27: Added bindings for <i>Prelude to Darkness</i>. Added two new pastel-colored skins.</li>
	<li>2010/05/14: Switched to jQuery instead of plain HTML for the form elements on the main page. Still have to do the submission page...</li>
	<li>2010/05/13: Added bindings for <i>ADOM</i>, <i>Elder Scrolls: Oblivion</i>, <i>Fallout 3</i> and <i>Empire: Total War</i>.</li>
	<li>2010/05/12: Added bindings for <i>Diablo</i>, <i>Diablo 2</i>, <i>Halo</i>, <i>Halo 2</i>, <i>Civilization II</i>, <i>Civilization IV</i>, <i>Crysis</i> and <i>Deus Ex</i>.</li>
	<li>2010/05/02: Fixed a few bugs and updated the submission form to PHP. Form fields should no longer become emptied when pressing the Back button after failing the captcha image test. (Doh!)</li>
	<li>2010/04/28: Updated the site to use PHP and MySQL instead of pure JavaScript. Hopefully search engines will start indexing it now.</li>
	<li>2010/04/21: Added bindings for <i>X-Wing Alliance</i>.</li>
	<li>2010/02/11: Added bindings for <i>Earth &amp; Beyond</i>.</li>
	<li>2009/11/29: Added bindings for <i>The Sims 2</i>, <i>Half-Life</i>, <i>Half-Life 2</i>, <i>World of Warcraft</i> and <i>StarCraft</i>.</li>
	<li>2009/10/19: Added bindings for <i>Space Empires IV</i>.</li>
	<li>2009/09/22: The site move has finally finished. The new site uses PHP, and I may also switch over to PHP/MySQL for generating the actual charts.</li>
	<li>2009/06/06: Added bindings for several new games.</li>
	<li>2009/06/06: Added three new skins and updated the Printer-Friendly skin.</li>
	<li>2009/05/31: I did another overhaul of the scripts, as well as added the newly created online submission form.</li>
	<li>2009/05/24: Added a bunch of new bindings. Also, another minor overhaul involving the renaming of a lot of variables. Hopefully, there are no bugs...</li>
	<li>2009/05/21: I added several Macintosh layouts, including one for the new Aluminium iMac keyboard.</li>
	<li>2009/05/21: Fixed the measurements for most of the other layouts which were off by some fractions of an inch.</li>
	<li>2009/05/16: Added over a dozen new bindings as well as French and German PC keyboard layouts. The new layouts are not compatible with most games, however, until someone contacts me with a list of corresponding French and German bindings.</li>
	<li>2009/05/11: After several years of neglect, I've done a major overhaul of the project's scripts. Please forgive (and report) any errors! Also, feel free to submit new bindings, layouts or styles.</li>
	<li>This project was initially started circa 2004 when my site was hosted on GeoCities. Back then it was mostly pure JavaScript and HTML. (Free GeoCities accounts were not permitted to use PHP or MySQL.) It has come a long way since then. ;)</li>
</ul>
<h2>Credits</h2>
<ul>
	<li>If you have submitted layouts, bindings or styles, then your name will also appear at the bottom of each diagram.</li>
	<li>Kozierok style: <i>Keyboard Key Groupings</i> by Charles M. Kozierok (<a target="_blank" href="http://www.pcguide.com/ref/kb/group-c.html">link</a>)</li>
	<li>Savard style: <i>Scan Codes Demystified</i> by John J. G. Savard (<a target="_blank" href="http://www.quadibloc.com/comp/scan.htm">link</a>)</li>
	<li>Hello Kitty style: <i>Hello Kitty Keyboard</i> by DreamKitty.com (<a target="_blank" href="http://www.dreamkitty.com/Merchant2/merchant.mv?Screen=PROD&Store_Code=DK2000&Product_Code=K-FB109141&Category_Code=HK">link</a>)</li>
	<li>Doraemon style: <i>Doraemon Keyboard</i> by DreamKitty.com (<a target="_blank" href="http://www.dreamkitty.com/Merchant5/merchant.mvc?Screen=PROD&Store_Code=DK2000&Product_Code=O-FB761011&Category_Code=">link</a>)</li>
	<li>FunKeyBoard style: <i>FunKeyBoard</i> by Chester Creek Technologies (<a target="_blank" href="http://www.venturaes.com/index_new.asp?http://www.venturaes.com/chestercreek/index.html">link</a>)</li>
	<li>"Simple JQuery Accordion Menu" by Marco van Hylckama Vlieg. (<a target="_blank" href="http://www.i-marco.nl/weblog/">link</a>)</li>
	<li>CSS checkbox code by W3Schools. (<a target="_blank" href="https://www.w3schools.com/howto/howto_css_custom_checkbox.asp">link</a>)</li>
	<li>JavaScript table sorting routine by W3Schools. (<a target="_blank" href="https://www.w3schools.com/howto/howto_js_sort_table.asp">link</a>)</li>
	<li>Recaptcha script from Stack Overflow. (<a target="_blank" href="https://stackoverflow.com/questions/30006081/recaptcha-2-0-with-ajax">link</a>)</li>
	<li>Several icons from the Noun Project. (<a target="_blank" href="https://thenounproject.com/">link</a>)
		<ul>
			<li>"Spreadsheet" by Arthur Shlain</li>
			<li>"Keyboard" by Alexey Ivanov</li>
			<li>"Information" by AnsteyDesign</li>
			<li>"Edit" by Garrett Knoll</li>
			<li>"Plus" by P.J. Onori</li>
			<li>"Delete" by P.J. Onori</li>
		</ul>
	</li>
	<li>Binding schemes submitted by:
		<ul>
			<li>Agnes Beste</li>
			<li>Taz</li>
			<li>Josiah Stearns</li>
			<li>Cris</li>
			<li>Daniel</li>
			<li>etejam</li>
		</ul>
	</li>
</ul>
<h2>Links to Further Reading</h2>
<ul>

	<li>ShortcutMapper (<a target="_blank" href="http://waldobronchart.github.io/ShortcutMapper/">link</a>)</li>
	<li>Keyboard Layout Editor (<a target="_blank" href="http://www.keyboard-layout-editor.com/">link</a>)</li>
	<li>KeyXL Keyboard Shortcuts (<a target="_blank" href="http://www.keyxl.com/">link</a>)</li>
	<li>AllHotkeys.com (<a target="_blank" href="http://allhotkeys.com/">link</a>)</li>
	<li>replacementdocs (<a target="_blank" href="http://www.replacementdocs.com/">link</a>)</li>
	<li>Character sets (<a target="_blank" href="http://www.alanwood.net/demos/wgl4.html">link</a>)</li>
	<li>REALDev - The Keyboard (<a target="_blank" href="http://classicteck.com/rbarticles/mackeyboard.php">link</a>)</li>
	<li>Key Support, Keyboard Scan Codes, and Windows (<a href="http://www.microsoft.com/whdc/archive/scancode.mspx">link</a>)</li>
	<li>Virtual-Key Codes (<a target="_blank" href="http://msdn.microsoft.com/en-us/library/ms645540.aspx">link</a>)</li>
	<li>Generate a Heatmap of your Keystrokes (<a target="_blank" href="http://www.blendedtechnologies.com/visualization-tricks-generate-a-heatmap-of-your-keystrokes/">link</a>)</li>
</ul>
<?php print($page_bot); ?>
