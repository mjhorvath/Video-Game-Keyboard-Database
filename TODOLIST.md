# To Do List

### Incomplete
* Should maybe store the SVG patterns, filters and gradients in separate files, 
  rather than cluttering up the main SVG file. But since there are only a dozen 
  or so styles right now, it is currently not very important.
* Continue developing the "Spreadsheet View" of the submission form, and permit 
  users to alter or update the keyboard data using it if they want to.
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
  may add them. Currently, updating such a column would be a PITA.
* Should mouse, joystick, etc. commands be lined up neatly with left and right 
  parts separated by an equal '=' sign, for instance using a table? Or should I 
  instead use a definition/description list? This should not be too hard since 
  I already store the left and right texts in separate fields.
* Instead of one genre per game, I could create a tag cloud so that a game can  
  be placed into multiple genres. What type of GUI would be necessary in order 
  to facilitate this? Is this posible in SQL?
* How granular should I get WRT video game genres? Should I list sub-genres and 
  sub-sub-genres? How many? Which ones?
* Maybe rename "Win" key to "Super" or "Meta"?
* Find out if the GitHub Powershell tool can accept multi-line commit messages.
* I merged the HTML and SVG formats into one format. The diagrams are no longer 
  rendered using pure HTML code, and the SVG file is now once again embedded 
  within an HTML wrapper. (This is how the old "embed" format worked.) Not sure 
  what effect this will have on search engines, however.
* Now that I have ditched the HTML format for the principal rendering method, I 
  should maybe update the editor to render the diagram in SVG as well. This 
  will require some cross-frame JavaScript as well as SVG text input, which I'm 
  not sure is possible.
* Users should maybe be able to enter text directly onto the keyboard diagram 
  on the submission form. Have to think this through, as it could get messy.
* Users should maybe be able to press the ALT modifier key and see the bindings 
  that make use of it. There is another project on the Web that works in this 
  manner.
* File permissions recently started getting messed up when uploading new files 
  using FileZilla. Is it a bug in the software? I now have to fix the 
  permissions manually each time I upload a new file for the first time. I need 
  to figure out what is going on.
* I thought the ID for each key in the "positions" table was based on the IBM 
  position codes. However, apparently this is not the case. Should I edit the 
  position table so they *are* based on the IBM position codes?
* I would like for the "command_text" column in the "commands" table to be set 
  to NOT NULL. But I will have to come up with something else to put into that 
  column when adding "Additional Notes" to the table. Unlike the other command 
  types, the "Additional Notes" category usually leaves that column blank.
* Need to create a form to make it possible for users to create and submit new 
  keyboard layouts. Or, support layout scripts created using other software, 
  for instance those created using 
  [Keyboard Layout Editor](http://www.keyboard-layout-editor.com/).
* Whether or not to show lower case key caps should maybe be a per-game setting 
  rather than a per-layout setting.
* Since the main diagrams are now rendered in SVG, I could make the Enter key 
  in the European ISO layouts the correct shape. I.e. a polygon with six 
  vertices instead of four vertices.
* Need to switch to using something other than alert boxes for reporting 
  errors, since alert boxes can be inadvertently disabled by the user.

### Problematic
* Sub-pages should maybe not repeat the parent project's title since the title 
  already appears in the site's horizontal breadcrumbs. Not sure.
* The key caption legend should also be configurable. Right now it always says 
  "SHIFT", "CTRL", and "ALT", and can't be customized. Not sure which table to 
  put this stuff in. There will need to be a way to turn each string on and off 
  as well as insert a value. [Ed. I went ahead and created the table. However, 
  the table is empty as of now until I think of a way to let users modify these 
  strings in the submission form.]
* Automatically send confirmation emails to anyone who completes the submission 
  form. Also, do this for the email form on my main website.
* Should be able to easily create "Typing Reference" schemes for the other 
  keyboards as well. I would like to have the GUI strings translated into the 
  respective languages first, however.

### Rejected
* Implement a "languages" table. [Ed. It may be sufficient to simply tie the 
  languages the bindings texts are written in to the languages of the 
  keyboards.]
* Maybe the red in the "Warm" styles can be made deeper? [Ed. I tried this, but 
  it didn't look nice.]

### Done
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
