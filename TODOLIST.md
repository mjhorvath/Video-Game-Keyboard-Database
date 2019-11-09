## To Do List

### Incomplete
* Continue developing the "Spreadsheet View" of the submission form, and permit 
  users to alter or update the keyboard data using it.
* I should change all the various JavaScript alert boxes to something prettier.
* Add timestamp columns to the various SQL tables to record the dates and times 
  when records were added or modified.
* I would also like to create charts/diagrams for gamepads, mice and joysticks. 
  But the sheer number of different devices will make this task very difficult.
* Not sure whether the sitemap should list each game once for every platform/
  layout, or only once, period. Does it confuse the search engines to have 
  multiples of each game?
* Should the accordion menus be sorted alphabetically using PHP or JS, or 
  should I add a "displayorder" column to each SQL table? If I can come up with 
  an easy way to update such a "displayorder" column using SQL commands, then I 
  may add them. Updating such a column manually would be a PITA.
* Should the mouse, joystick, etc. commands be lined up neatly into pairs with 
  the left and right parts separated by an equal '=' sign? For instance using a 
  table? Or should I instead use a definition/description list? This should not 
  be too hard since I already store the left and right texts in separate fields 
  in the database itself.
* Instead of having one genre per game, I could maybe create a tag cloud so 
  that a game can be placed into multiple genres. What type of GUI would this 
  necessitate? Is this even possible using MySQL?
* How granular do I want to get WRT video game genres? Should I list sub-genres 
  and sub-sub-genres? How many genres? Which ones?
* Maybe rename "Win" key to "Super" or "Meta" as per some custom keyboard 
  manufacturers?
* Find out if the GitHub Powershell tool can accept multi-line commit messages.
* I merged the HTML and SVG formats into one format. The diagrams are no longer 
  rendered using pure HTML code, and the SVG file is now once again embedded 
  within an HTML wrapper. (This is how the old "embed" format worked.) Not sure 
  what effect using SVG will have on search engines, however. Do search engines 
  parse the text inside SVG files?
* Now that I have ditched the HTML format in favor of SVG for the principal 
  rendering methods, should I also update the editor to do the same? This will 
  require some cross-frame JavaScript as well as SVG text input, which I'm not 
  sure is possible or easy.
* Users should maybe be able to enter text directly onto the keyboard diagram 
  on the submission form. Have to think this through, as things could get very 
  messy.
* Users should maybe be able to press the ALT modifier key and see the bindings 
  that make use of it. There is another project on the Web that works in this 
  manner.
* File permissions recently started getting messed up when uploading new files 
  using FileZilla. Is it a bug in the software? I now have to fix the 
  permissions manually each time I upload a new file for the first time. I need 
  to figure out what is going on.
* I thought the ID for each key in the "positions" table was based on the IBM 
  position codes. However, apparently this is not the case. Should I edit the 
  position table so that they *are* based on the IBM position codes?
* I would like for the "command_text" column in the "commands" table to be set 
  to NOT NULL. But I will have to come up with something else to put into that 
  column when adding "Additional Notes" to the table. Unlike the other command 
  types, the "Additional Notes" category usually leaves that column blank.
* Need to create a form for users to create and submit new keyboard layouts 
  with. Or, support layout scripts created using other software, for instance 
  those created using 
  [Keyboard Layout Editor](http://www.keyboard-layout-editor.com/).
* Whether or not to show lower case key caps should maybe be a per-game setting 
  rather than a per-layout setting. Or, allow users toggle them on/off.
* Since the main diagrams are now rendered in SVG, I could make the Enter key 
  in the European ISO layouts the correct "L" shape. I.e. a polygon with six 
  vertices instead of four vertices. However, the database does not support non-
  rectangular keys.
* Need to switch to using something other than JavaScript alert boxes for 
  providing the users with feedback, since alert boxes can be disabled by users.
* The warning about unset/missing caption colors could benefit from a checkbox 
  to enable or disable the warning. It could go beneath the "Enter new lines by 
  typing \n" message, since there are not any other configurable settings 
  currently.
* Automatically send confirmation emails to anyone who completes the submission 
  form. Also, do this for the email form on my main website too.
* Should be able to easily create "Typing Reference" schemes for the other 
  keyboards as well. I would like to have the GUI strings translated into the 
  respective languages first, however.
* Users of the submission form should be able to specify a layout using a drop-
  down list or whatever. Users are already able to specify and edit a "blank 
  sample" for every layout. But this is insufficient, IMO.
* There needs to be additional indication on the submission page that there is 
  a "Help Pane" as well as a "Spreadsheet View".
* Should create a table of authors/contributers that automatically pulls info 
  from the database. It should show a list of authors, and the stuff they 
  contributed to.
* Experiment again with scaling and rotating the diagrams so that they are 
  easier to print.
* The TSV code on the Spreadsheet page of the submission form is not always 
  lining up properly. Need to set the container to overflow instead of wrap.
* Currently, the "layouts", "records_games" and "records_styles" tables each 
  have an "author_id" column. However, this column can only store a single 
  author ID, and does not record the time and date when an update was made. I 
  would rather log changes like Wikipedia or GitHub does, with a record of 
  users, as well as a timestamp. Recording the actual content that was added or 
  removed may be overkill, however.
* Auditing changes to the database might be easier if I write a frontend tool 
  for users to make changes with, versus doing everything myself in MySQL 
  Workbench. Also, IIRC, there are issues related to creating and running 
  stored procedures on my Web server that have to do with either the installed 
  MySQL version, the PHP version, or simply some rules imposed by the Web host.
* Not sure every table needs a single numerical index column. Using two or more 
  existing columns to form a composite key might suffice in several cases.
* Once I get auditing set up, it would be great if the submission form had an 
  additional pane showing the audit history. The tab icon could be an image of 
  a clock face.
* Hopefully, an update trigger will not be fired for every single row that gets 
  altered. That would be too much data.
* In the two "keygroups" tables, make sure that the index of each group is not 
  hardcoded. If possible, merge both tables into a single table.
* On the submission form, replace the X icon in the tool bar with arrows 
  pointing left and right.
* Need to add instructions to the effect that the SHIFT, ALT, etc. commands may 
  require colors assigned to them, but these colors will not necessarily be 
  displayed in the actual chart.
* I would like to change the text shown in the color selection boxes of the 
  submission form from "non" to "null" or something else. This might have an 
  adverse effect on some scripts, however.
* Need to create one or more styles that are accessible to people with color-
  blindness.
* Several of the PHP and JS files should not be directly accessible via the Web.

### Problematic
* Sub-pages should maybe not repeat the parent project's title since the title 
  already appears in the site's horizontal breadcrumbs. Not sure.
* The key caption legend should also be configurable. Right now it always says 
  "SHIFT", "CTRL", "ALT", etc. and can't be customized. Not sure which table to 
  put this stuff in. There will need to be a way to turn each string on and off 
  as well as insert a value. [Ed. I went ahead and created the table. However, 
  the table is empty until I think of a way to let users modify these strings 
  in the submission form.]

### Rejected
* Implement a "languages" table. [Ed. It may be sufficient to simply tie the 
  languages of the bindings to the languages of the keyboard layouts.]
* Maybe the red in the "Warm" styles can be made deeper? [Ed. I tried this, but 
  it didn't look nice.]
* Should maybe store the SVG patterns, filters and gradients in separate files, 
  rather than cluttering up the main SVG files. [Ed. separating this stuff into 
  additional files makes it harder to trade or upload the diagrams elsewhere.]

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
