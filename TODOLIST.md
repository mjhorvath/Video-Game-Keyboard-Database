## To Do List

### Submission Form
* Continue developing the "Spreadsheet View" of the submission form, and permit 
  users to alter or update the keyboard bindings directly using it.
* There needs to be additional indication on the submission page that there is 
  a "Help Pane" as well as a "Spreadsheet View".
* "Spreadsheet View" needs to be renamed to something else since I have not 
  created an actual spreadsheet. Maybe "TSV View" would be a better name.
* Users should maybe be able to enter text directly onto the keyboard diagram 
  on the submission form. Have to think this through, as things could get very 
  messy.
* Need to create a form for users to create and submit new keyboard layouts 
  with. Or, support layout scripts generated using other software such as 
  [Keyboard Layout Editor](http://www.keyboard-layout-editor.com/). Or, I could 
  write a desktop application for this.
* Need to switch to using something other than JavaScript alert boxes for 
  providing users with feedback. Alert boxes can be disabled by users in some 
  browsers, which is bad. Custom alert boxes could also be made prettier.
* On the submission form, replace the X icon in the tool bar with arrows 
  pointing left and right.
* Need to add instructions to the effect that the SHIFT, ALT, etc. commands may 
  require colors assigned to them, but these colors will not necessarily be 
  displayed in the chart. (At least, in the current version of the chart.)
* I would like to change the text shown in the color selection boxes of the 
  submission form from "non" to "null" or something else. This might have an 
  adverse effect on some scripts, however. Also, the drop down list would take 
  up more space.
* On the submission form, draw an animated dotted line around the border of the 
  selected key. [See this example](https://stackoverflow.com/questions/28365839/dashed-border-animation-in-css3-animation).
* Should make the size of the left pane adjustable and make the text input form 
  fields shrink or grow with the size of the pane.
* Maybe add a checkbox to the submission form so that users can indicate to the 
  admin that bindings for a brand new game are being created. The "record_id" 
  field should then contain "\N" for each record instead of the existing game's 
  "record_ID".
* Link to the Excel sheet directly from the submission form, so that those who 
  might prefer using it over the default methods will have easier access to it.
* On the "spreadsheet view" of the submission form, add buttons to update the 
  "keyboard view" using TSV in the text boxes. Leave the buttons disabled until 
  the update feature is fully implemented.

### MediaWiki Code
* Generate MediaWiki code for layouts and styles in addition to games.
* Add support in the MediaWiki template for toggling the numpad on/off. [Ed. it 
  may actually be possible to do this already. Need to double check.]
* Add support in the MediaWiki template for Shift/CTRL/Alt command colors, even 
  if it is not actually possible to display these colors properly at this time.
  [Ed. the reasoning being that it may become possible to display these colors 
  properly at some point in the future.]

### Auditing, Authors, Submissions, Etc.
* Should create a table of authors/contributers that automatically pulls info 
  from the database. It should show a list of authors, and the stuff they 
  contributed to.
* Automatically send confirmation emails to anyone who completes the submission 
  form. Also, do this for the email form on my main website too.
* I would like to audit changes to the database with a record of who made each 
  change and when. Recording the actual content that was changed may be 
  overkill, though. Maybe I can install a versioning system meant to work 
  specifically with databases.
* Auditing changes to the database might be easier if I write a front-end tool 
  for users to make changes with, versus doing everything myself in MySQL 
  Workbench. Also, IIRC, there are issues related to creating and running 
  stored procedures on my Web server that have to do with either the installed 
  MySQL version, the PHP version, or simply some rules imposed by the Web host.
* Once I get auditing set up, it would be great if the submission form had an 
  additional pane showing the change history. The tab icon could be an image of 
  a clock face.
* Hopefully, an update trigger will not be fired for every single row that gets 
  altered. That would be too much data.
* Fix the RDF stuff in SVG files so that author names are not duplicated 
  multiple times.
* The tables "contrib_layouts", "contrib_games" and "contrib_styles" are 
  missing time and date stamps properly indicating when items were added or 
  updated.

### Database Schema
* Right now it may only be incidental that bindings and positions match up with 
  each other properly. Need to make sure I am not relying on hard-coded values.
* Maybe add a new "keys" table and have "key_number" be the index column. The 
  "key_number" is referenced by the "bindings" and "keystyles" tables. Not sure 
  what else to put in the "keys" table besides the index, however.
* The "record_id" column name is used in two different contexts within the 
  database: once as a game record, once as a style record. Need to rename the 
  columns in both contexts to prevent confusion.
* Should calculate the correct key IDs dynamically based on what is in the 
  "bindings" table versus hard-coding the max key ID in the "layouts" table.
* I thought the ID for each key in the "positions" table was based on the IBM 
  position codes. However, apparently this is not the case. Should I edit the 
  position table so that they *are* based on the IBM position codes?
* I would like for the "command_text" column in the "commands" table to be set 
  to NOT NULL. But I will have to come up with something else to put into that 
  column when adding "Additional Notes" to the table. Unlike the other command 
  types, the "Additional Notes" category usually leaves that column blank.
* Not sure every table needs a single numerical index column. Using two or more 
  existing columns to form a composite key might suffice in many cases.
* In the two "keygroups" tables, make sure that the index of each group is not 
  hardcoded. If possible, merge both tables into a single table.
* I would like to re-index the "layouts" table since a gap exists between ID #1 
  and ID #3. This would break links to many pages, however. Better to simply re-
  use this ID in a future layout.
* Rename the "contrib" tables to "contribs" in order to use a consistent naming 
  scheme.
* On the submission form, the column names for the TSV data in the "Spreadsheet 
  View" should be fetched from the database instead of hardcoded.
* The "entities" table should be renamed to "url_queries" or something similar. 
  The current name is too ambiguous.
* The PHP and JS "color" and "class" tables are currently sorted by the index 
  columns of the "keygroups" tables in the database. But what happens if new 
  colors or classes are added, or the index columns somehow get corrupted? Can 
  I sort and re-index the "keygroups" tables without affecting any other tables 
  that might have foreign key constraints? What's the best method of specifying 
  a custom sort order?

### Optimizations
* Should maybe store the SVG patterns, filters and gradients in separate files 
  to be included only when needed, rather than cluttering up "keyboard-svg.php".
* Currently, all the SQL queries are very simple, and all processing of data is 
  done using PHP scripts. A lot of these tasks could probably be performed more 
  efficiently using SQL and joins.
* I moved most PHP routines and SQL statements to two dedicated files. But this 
  may be worse for performance since only a subset of the routines are needed 
  at any given time, yet the entire files are loaded each time. Should I split 
  the two big files into several smaller ones?

### Miscellaneous
* I would also like to create charts/diagrams for gamepads, mice and joysticks. 
  But the sheer number of different devices will make this task very difficult.
* Not sure whether the XML sitemap should list each game once for every 
  platform/layout, or only once, period. Does it confuse search engines to have 
  multiple diagrams for each game?
* Should the accordion menus on the front-end page be sorted alphabetically, or 
  should I add a "displayorder" column to each SQL table? If I can come up with 
  an easy way to update a "displayorder" column using SQL commands, then I may 
  add one to each of the tables. Updating such a column manually would be a 
  PITA, however.
* Should the mouse, joystick, etc. commands be lined up neatly into pairs with 
  the left and right parts separated by an equal '=' sign? For instance using a 
  table? Or should I instead use a definition/description list? This should not 
  be too hard since I already store the left and right texts in separate fields 
  within the database itself.
* Instead of having one genre per game, I could maybe create a tag cloud so 
  that a game can be placed in multiple genres. What type of GUI would this 
  necessitate? Is this even possible using MySQL? [Ed. it is possible but would 
  require a lengthy bridge table.]
* How granular should I get with respect to video game genres? Should I list 
  sub-genres and sub-sub-genres? How many genres? Which ones?
* I merged the HTML and SVG formats into one format. The diagrams are no longer 
  rendered using pure HTML code, and the SVG files are now once again embedded 
  within an HTML wrapper. (This is also how the old "embed" format worked.) Not 
  sure what effect using SVG will have on search engines, however. Do search 
  engines parse text embedded within SVG files? What if those SVG files are 
  themselves embedded within HTML files?
* Now that I have ditched the HTML format in favor of SVG for the principal 
  rendering method, should I update the submission editor to use SVG as well? 
  This will require some cross-frame JavaScript, and may require some method of 
  text input for the SVG files. Not sure this is going to be easy.
* Users should maybe be able to press the ALT modifier key and see the bindings 
  that make use of that modifier. There is another project on the Internet that 
  works in this manner. Need to check it out.
* File permissions recently started getting messed up when uploading new files 
  using FileZilla. Is it a bug or setting in the software? I now have to fix 
  the permissions manually each time I upload a new file. I need to figure out 
  what is going on.
* Since the main diagrams are now rendered in SVG instead of HTML, I could make 
  it so that the Enter keys on the European ISO layouts have the correct "L" 
  shape. I.e. a polygon with six vertices instead a rectangle with four 
  vertices. However, the database currently only stores a key's XY position, 
  width and height - not the coordinates of its four vertices.
* I should be able to easily create "Typing Reference" schemes for every 
  keyboard layout. I would like to have the GUI strings translated into their 
  respective languages before I do this, however.
* Experiment again with scaling and rotating the diagrams so that they become 
  easier to print. IIRC, last time I tried this it didn't work in every browser.
* Need to create one or more styles that are accessible to people with color-
  blindness.
* The "Create New Diagram" button on the front-end page should have a "hover" 
  effect that changes the button's color.
* Maybe replace the "up", "down", "right" and "left" key captions with arrow 
  icons? (Or Unicode arrow characters?)
* The PHP scripts get kind of flaky when both an SEO string *and* a game ID are 
  provided in the URL. Not sure which of these should override the other.
* Not sure if the GUI string language should be a per-keyboard layout setting, 
  or something the user can configure manually. The latter would require yet 
  another URL query parameter.
* Whether or not to show lower-case key caps should maybe be a per-game setting 
  rather than a per-layout setting. Or, allow users toggle them on/off manually.
* Maybe merge the "Completed" tasks on this page back into the other categories 
  and use strike-through text to indicate whether the task has been completed. 
  Or use GitHub's "Issues" interface. Can GitHub "Issues" be categorized?
* Formats are now listed in the database. I could use the database to generate 
  the format list in the page footer now if I wanted to. This is also true for 
  the default numpad state.
* Make sure there are no more characters that are still escaped in the page 
  title, since page titles do not benefit from having characters escaped.
* Make sure I did not overlook any characters that need to be escaped. Is there 
  a library I can use to do this?
* Need to add "header", "footer", "main" and other HTML5 semantic tags to every 
  generated page.
* All HTML code should be echo'd using PHP in order to be consistent, IMO.
* Search the source code for instances of the word "hardcoded", since they 
  represent data that should really be put in/retrieved from the database.
* Should the "non" color be added to the color tables too?
* Tweak the "cleantext" functions and/or the query functions so that separate 
  copies of every query function are required for HTML and SVG.

### Problematic
* Sub-pages should maybe not repeat the parent page's title in the page headers 
  since the title already appears in the site's horizontal breadcrumbs.
* The key caption legend should also be configurable. Right now it always says 
  "SHIFT", "CTRL", "ALT", etc. and can't be customized. Not sure which table to 
  put this stuff in. There will need to be a way to turn each string on and off 
  as well as insert a value.
* Several of the PHP and JS files should not be directly accessible via the Web.
  [Ed. this can be done easily for PHP files, but not JS or CSS files as far as 
  I can tell.]
* It would be nice to split the background coloring for when a key has more 
  than one use. For instance, one could subdivide each rectangular key into two 
  triangles and color each triangle differently. [Ed. this may be difficult to 
  implement in both HTML and SVG, and may confuse visitors.]
* It would be nice to stretch the text on each key so that it fits without the 
  need for line breaks, like in a spreadsheet program. [Ed. I don't think this 
  can be accomplished without lots of JavaScript, which I would rather avoid.]
* Experiment more with CSS effect filters. [Ed. May not work in all browsers.]
* There remain some meta header tags that have not been added to the PHP pages, 
  yet. [Ed. right now I can't remember which tags I was talking about, however.]
* On the submission form, replace key code labels such as "capnor" and "lowagr" 
  with actual English text. [Ed. space is at a premium. Not sure I can fit the 
  English text in there without stretching the page or triggering a scroll bar.]
* Maybe the red in the "Warm" styles can be made deeper? [Ed. I tried this, but 
  it didn't look very nice.]

### Rejected
* Users of the submission form should be able to specify a layout using a drop-
  down list or whatever within the submission form itself. Users are already 
  able to specify and edit a "Blank Sample" for every layout starting from the 
  frontend page. But this is insufficient, IMO. [Ed. this is a bad idea.]

### Completed
* In the submission form, selecting a colored OPTION element should change the 
  color of the corresponding parent SELECT element as well.
* A warning should be thrown ONBEFOREUNLOAD if someone attempts to leave the 
  submission form and there is any unsaved or unsubmitted data.
* Make sure all buttons in the submission form have their TITLE attributes set 
  so that tooltips appear when hovering the mouse over them.
* The Creative Commons and GNU icons need to have their ALT attributes set.
* The CAPTCHA dialog should check for a correct security code *before* sending 
  the user on to the next page. At the very least, it should warn the user that 
  he or she will not be able to return to the form page after clicking the 
  submit button.
* Convert input TABLEs to DIVs.
* Need a loading icon since it takes a while for all the AJAX stuff (e.g. 
  reCAPTCHA) to download and load fully.
* The "required" attribute of the email INPUT elements should cause the 
  submission form to not post if those INPUTs are not filled properly. This 
  includes the email address field, which requires special syntax.
* The footer area is the same for many of the views. It could be stored in a 
  separate file and simply included when needed.
* It may be better to reverse the order of the selection dialogs on the front 
  page. E.g. select the Keyboard first, then the Style, and then the Game. 
  Otherwise, finding bindings for the German or French keyboards will be like 
  searching for a needle in a haystack.
* Remake the loading icon in animated WebP format. Does this graphics format 
  work properly in other browsers besides Google Chrome?
* Experiment with flexbox in the "CSS3 Testing" theme. The "Command" DIVs could 
  make use of it to automatically wrap or resize themselves based on the amount 
  of space available to them.
* Embedded icon images not showing up anymore. See the bindings for Homeworld 2 
  for an example.
* "Patterned Grayscale" theme has several newly-introduced issues since I 
  switched from HTML to SVG rendering. [Ed. On second thought, it's not so bad. 
  Maybe I will leave things the way they are.]
* Optgroup styles are messed up in dark/black themes in Firefox. [Ed. I ended 
  up removing all styling from the select lists and instead set them back to 
  their defaults.]
* Rename "html_XXXXX.css" files to "submit_XXXXX.css" and/or rename 
  "keyboard-html.php" to "keyboard-embed.php".
* The HTML editor should throw an error if a legend group has not been assigned 
  to each and every binding.
* Submission form may not be generating enough table columns for the "bindings" 
  table.
* Add a toggle to turn the numpad on and off.
* Maybe rename "Win" key to "Super" or "Meta" as per many Linuxes and custom 
  keyboards?
* Currently, the "layouts", "records_games" and "records_styles" tables each 
  have an "author_id" column. However, this column can only store a single 
  author ID whereas multiple people might have actually worked on an item.
* On the frontend page, replace the asterisk used to indicate "default" 
  selections with an icon of a star or something else.
* Move all "support" scripts to another directory, and use htaccess to block 
  all external access to them. [Ed. I created the "lib" directory, but I don't 
  think there's a way to block graphic images, javascript, css, etc. without 
  blocking the files everywhere else too.]
* Direct links to SVG file will not load other formats when changing the "fmt" 
  URL parameter. Conversely, changing the "fmt" URL parameter on a PHP page can 
  load an SVG file, but the extension remains PHP and does not change to SVG.
* I would like to move "keyboard-embed.php", "keyboard-svg.php", 
  "keyboard-wiki.php" and "keyboard-submit.php" into the "lib" directory. 
  However, there are issues involving paths to these files. When the files are 
  included inside "keyboard-chart.php", they are treated as if they were in the 
  same folder as "keyboard-chart.php". But when "keyboard-svg.php" is loaded 
  into an IFRAME in "keyboard-embed.php", it is correctly treated as if it were 
  in the "lib" directory. A lot of stuff gets messed up when this happens. One 
  solution might be to not use an IFRAME for the SVG files, but instead insert 
  the SVG code directly into the HTML wrapper file code. Or, maybe there is a 
  way to not include the files inside "keyboard-chart.php", and use some means 
  of changing the page location instead.
* The file "keyboard-sitemap.php" is a tool and does not need to use the site's 
  styling. It should also be moved into the "lib" directory.
* The TSV code on the "Spreadsheet View" of the submission form is not always 
  lining up neatly. Need to set the container to overflow instead of wrap.
* On the submission form the displayed value for "inp_keynum" should start at 
  one instead of zero like in the database. In JavaScript the number should 
  remain the same as it appears now, however.
* The submission form does not indicate anywhere which layout and style are 
  selected.
* Need to fetch the contents of the "colors" JavaScript array from the database 
  instead of hardcoding the values.
* The "$legend_count" PHP variable is hardcoded. Maybe needs to be calculated 
  based on size of "$color_array".
* Implement a "languages" table.
* The value of the "html" tag's "lang" attribute should be a short two-letter 
  string instead of an integer as it is now. It should not be hardcoded either.
