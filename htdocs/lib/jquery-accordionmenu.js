/* 
   Simple JQuery Accordion menu.
   HTML structure to use:

   <ul id="menu">
     <li><a href="#">Sub menu heading</a>
     <ul>
       <li><a href="http://site.com/">Link</a></li>
       <li><a href="http://site.com/">Link</a></li>
       <li><a href="http://site.com/">Link</a></li>
       ...
       ...
     </ul>
     <li><a href="#">Sub menu heading</a>
     <ul>
       <li><a href="http://site.com/">Link</a></li>
       <li><a href="http://site.com/">Link</a></li>
       <li><a href="http://site.com/">Link</a></li>
       ...
       ...
     </ul>
     ...
     ...
   </ul>

Copyright 2007 by Marco van Hylckama Vlieg

web: http://www.i-marco.nl/weblog/
email: marco@i-marco.nl

Free for non-commercial use
*/

function initMenu(thisSelect)
{
	// these should already be set in 'keyboard.php'
//	$('#' + thisSelect + '_menu li ul').hide();
//	$('#' + thisSelect + '_menu li ul:first').show();
//	$('#' + thisSelect + '_menu li:first a .arrw_a').css('display', 'none');
//	$('#' + thisSelect + '_menu li:first a .arrw_b').css('display', 'inline');
	$('#' + thisSelect + '_menu li a').click
	(
		function()
		{
			var getSelect = this.getAttribute('menu');
			var checkElement = $(this).next();
			if((checkElement.is('ul')) && (checkElement.is(':visible')))
			{
				return false;
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible')))
			{
				$('#' + getSelect + '_menu li a .arrw_a').css('display', 'inline');
				$('#' + getSelect + '_menu li a .arrw_b').css('display', 'none');
				// I would like to replace the next two lines with jquery
				this.children[0].style.display = 'none';
				this.children[1].style.display = 'inline';
				$('#' + getSelect + '_menu ul:visible').slideUp('normal');
				checkElement.slideDown('normal');
				return false;
			}
		}
	);
	$('#' + thisSelect + '_menu li ul li a').click
	(
		function ()
		{
			if (this.className != 'acc_dis')
			{
				var getSelect = this.getAttribute('menu');
				if (lastClicked[getSelect])
					lastClicked[getSelect].className = 'acc_nrm'
				this.className = 'acc_sel'
				lastClicked[getSelect] = this
			}
			return false;
		}
	);
}
