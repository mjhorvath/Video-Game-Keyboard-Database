// Copyright (C) 2018  Michael Horvath

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
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 
// 02110-1301  USA

// To do:
// * Mouse, joystick, notes, etc. form elements do not work yet.
// * Need to implement captcha.
// * Need some way to actually submit the changes to the server or to me. Maybe 
//   using the same method I use for the email form on my website.
// * The 'Set Key' and 'Revert' buttons should be disabled until the key data 
//   have been changed from their initial values.
// * Need to set up the 'Image' parameter to accept image URIs.


function init_submissions()
{
	add_chart_key_events();
}

function addListener(element, eventName, handler)
{
	if (element.addEventListener)
	{
		element.addEventListener(eventName, handler, false);
	}
	else if (element.attachEvent)
	{
		element.attachEvent('on' + eventName, handler);
	}
	else
	{
		element['on' + eventName] = handler;
	}
}

function removeListener(element, eventName, handler) {
	if (element.addEventListener)
	{
		element.removeEventListener(eventName, handler, false);
	}
	else if (element.detachEvent)
	{
		element.detachEvent('on' + eventName, handler);
	}
	else
	{
		element['on' + eventName] = null;
	}
}

var current_id = null;
var current_values = {};
var last_class = null;

function click_on_chart_key(event)
{
	if (have_input_key_values_changed() == true)
	{
		alert("Stuff has changed. Set the key or revert the changes.");
		return;
	}

	var last_id = current_id;
	current_id = event.target.id.slice(7);

	capture_chart_values_into_cache();
	set_form_values_from_cache();

	// styling (could be bundled into a class and moved into a stylesheet)
	if (last_id != null)
	{
		document.getElementById('keyout_' + last_id).className = last_class;
	}
	last_class = document.getElementById('keyout_' + current_id).className;
	document.getElementById('keyout_' + current_id).className = 'keyout keysel';
}

function set_key()
{
	capture_form_values_into_cache();
	set_chart_values_from_cache();
}

function revert_changes()
{
	capture_chart_values_into_cache();
	set_form_values_from_cache();
}

function set_form_color(select_id, target_value)
{
	var this_select = document.getElementById(select_id);
	var this_options = this_select.options;
	for (var opt, i = 0; opt = this_options[i]; i++)
	{
		if (opt.value == target_value)
		{
			this_select.selectedIndex = i;
			break;
		}
	}
}

function get_form_color(select_id)
{
	var this_select = document.getElementById(select_id);
	var this_options = this_select.options;
	for (var opt, i = 0; opt = this_options[i]; i++)
	{
		if (this_select.selectedIndex == i)
		{
			return opt.value;
		}
	}
	return null;
}

function capture_chart_values_into_cache()
{
	if (current_id == null)
		return null;

	current_values.val_keynum = current_id;
	current_values.val_keyhgh = document.getElementById('keyhgh_' + current_id).getAttribute('value');
	current_values.val_keylow = document.getElementById('keylow_' + current_id).getAttribute('value');
	current_values.val_keyrgt = document.getElementById('keyrgt_' + current_id).getAttribute('value');
	current_values.val_capnor = document.getElementById('capnor_' + current_id).getAttribute('value');
	current_values.val_capshf = document.getElementById('capshf_' + current_id).getAttribute('value');
	current_values.val_capctl = document.getElementById('capctl_' + current_id).getAttribute('value');
	current_values.val_capalt = document.getElementById('capalt_' + current_id).getAttribute('value');
	current_values.val_capagr = document.getElementById('capagr_' + current_id).getAttribute('value');
	current_values.val_capxtr = document.getElementById('capxtr_' + current_id).getAttribute('value');
	current_values.col_capnor = document.getElementById('capnor_' + current_id).getAttribute('color');
	current_values.col_capshf = document.getElementById('capshf_' + current_id).getAttribute('color');
	current_values.col_capctl = document.getElementById('capctl_' + current_id).getAttribute('color');
	current_values.col_capalt = document.getElementById('capalt_' + current_id).getAttribute('color');
	current_values.col_capagr = document.getElementById('capagr_' + current_id).getAttribute('color');
	current_values.col_capxtr = document.getElementById('capxtr_' + current_id).getAttribute('color');
}

function capture_form_values_into_cache()
{
	if (current_id == null)
		return null;

	current_values.val_keynum = current_id;
	current_values.val_keyhgh = document.getElementById('inp_keyhgh').value;
	current_values.val_keylow = document.getElementById('inp_keylow').value;
	current_values.val_keyrgt = document.getElementById('inp_keyrgt').value;
	current_values.val_capnor = document.getElementById('inp_capnor').value;
	current_values.val_capshf = document.getElementById('inp_capshf').value;
	current_values.val_capctl = document.getElementById('inp_capctl').value;
	current_values.val_capalt = document.getElementById('inp_capalt').value;
	current_values.val_capagr = document.getElementById('inp_capagr').value;
	current_values.val_capxtr = document.getElementById('inp_capxtr').value;
	current_values.col_capnor = get_form_color('sel_capnor');
	current_values.col_capshf = get_form_color('sel_capshf');
	current_values.col_capctl = get_form_color('sel_capctl');
	current_values.col_capalt = get_form_color('sel_capalt');
	current_values.col_capagr = get_form_color('sel_capagr');
	current_values.col_capxtr = get_form_color('sel_capxtr');
}

function set_chart_values_from_cache()
{
	if (current_id == null)
		return null;

	document.getElementById('keyhgh_' + current_id).innerHTML = cleantextHTML(current_values.val_keyhgh);
	document.getElementById('keylow_' + current_id).innerHTML = cleantextHTML(current_values.val_keylow);
	document.getElementById('keyrgt_' + current_id).innerHTML = cleantextHTML(current_values.val_keyrgt);
	document.getElementById('capnor_' + current_id).innerHTML = cleantextHTML(current_values.val_capnor);
	document.getElementById('capshf_' + current_id).innerHTML = cleantextHTML(current_values.val_capshf);
	document.getElementById('capctl_' + current_id).innerHTML = cleantextHTML(current_values.val_capctl);
	document.getElementById('capalt_' + current_id).innerHTML = cleantextHTML(current_values.val_capalt);
	document.getElementById('capagr_' + current_id).innerHTML = cleantextHTML(current_values.val_capagr);
	document.getElementById('capxtr_' + current_id).innerHTML = cleantextHTML(current_values.val_capxtr);
	document.getElementById('keyhgh_' + current_id).setAttribute('value', current_values.val_keyhgh);
	document.getElementById('keylow_' + current_id).setAttribute('value', current_values.val_keylow);
	document.getElementById('keyrgt_' + current_id).setAttribute('value', current_values.val_keyrgt);
	document.getElementById('capnor_' + current_id).setAttribute('value', current_values.val_capnor);
	document.getElementById('capshf_' + current_id).setAttribute('value', current_values.val_capshf);
	document.getElementById('capctl_' + current_id).setAttribute('value', current_values.val_capctl);
	document.getElementById('capalt_' + current_id).setAttribute('value', current_values.val_capalt);
	document.getElementById('capagr_' + current_id).setAttribute('value', current_values.val_capagr);
	document.getElementById('capxtr_' + current_id).setAttribute('value', current_values.val_capxtr);
	document.getElementById('capnor_' + current_id).setAttribute('color', current_values.col_capnor);
	document.getElementById('capshf_' + current_id).setAttribute('color', current_values.col_capshf);
	document.getElementById('capctl_' + current_id).setAttribute('color', current_values.col_capctl);
	document.getElementById('capalt_' + current_id).setAttribute('color', current_values.col_capalt);
	document.getElementById('capagr_' + current_id).setAttribute('color', current_values.col_capagr);
	document.getElementById('capxtr_' + current_id).setAttribute('color', current_values.col_capxtr);

	last_class = 'keyout cap' + current_values.col_capnor;
}

function set_form_values_from_cache()
{
	if (current_id == null)
		return null;

	document.getElementById('inp_keynum').value = current_values.val_keynum;
	document.getElementById('inp_keyhgh').value = current_values.val_keyhgh;
	document.getElementById('inp_keylow').value = current_values.val_keylow;
	document.getElementById('inp_keyrgt').value = current_values.val_keyrgt;
	document.getElementById('inp_capnor').value = current_values.val_capnor;
	document.getElementById('inp_capshf').value = current_values.val_capshf;
	document.getElementById('inp_capctl').value = current_values.val_capctl;
	document.getElementById('inp_capalt').value = current_values.val_capalt;
	document.getElementById('inp_capagr').value = current_values.val_capagr;
	document.getElementById('inp_capxtr').value = current_values.val_capxtr;
	set_form_color('sel_capnor', current_values.col_capnor);
	set_form_color('sel_capshf', current_values.col_capshf);
	set_form_color('sel_capctl', current_values.col_capctl);
	set_form_color('sel_capalt', current_values.col_capalt);
	set_form_color('sel_capagr', current_values.col_capagr);
	set_form_color('sel_capxtr', current_values.col_capxtr);
}

function have_input_key_values_changed()
{
	if (current_id == null)
		return null;

	if
	(
		(current_values.val_keyhgh != document.getElementById('inp_keyhgh').value) ||
		(current_values.val_keylow != document.getElementById('inp_keylow').value) ||
		(current_values.val_keyrgt != document.getElementById('inp_keyrgt').value) ||
		(current_values.val_capnor != document.getElementById('inp_capnor').value) ||
		(current_values.val_capshf != document.getElementById('inp_capshf').value) ||
		(current_values.val_capctl != document.getElementById('inp_capctl').value) ||
		(current_values.val_capalt != document.getElementById('inp_capalt').value) ||
		(current_values.val_capagr != document.getElementById('inp_capagr').value) ||
		(current_values.val_capxtr != document.getElementById('inp_capxtr').value) ||
		(current_values.col_capnor != get_form_color('sel_capnor')) ||
		(current_values.col_capshf != get_form_color('sel_capshf')) ||
		(current_values.col_capctl != get_form_color('sel_capctl')) ||
		(current_values.col_capalt != get_form_color('sel_capalt')) ||
		(current_values.col_capagr != get_form_color('sel_capagr')) ||
		(current_values.col_capxtr != get_form_color('sel_capxtr'))
	)
		return true;

	return false;
}

function add_chart_key_events()
{
	// keys number is hardcoded but should be dynamic
	for (var i = 0; i < 106; i++)
	{
		var target_element = document.getElementById('keyout_' + i);
		if (target_element != null)
		{
			addListener(target_element, 'click', click_on_chart_key);
		}
	}
}

var side_pane_expanded = true;
function toggle_side_pane()
{
	if (side_pane_expanded == true)
	{
		document.getElementById('side_pane').style.width = '0em';
		document.getElementById('side_pane').style.padding = '0em';
		document.getElementById('side_pane').style.display = 'none';
		document.getElementById('side_max').style.display = 'block';
		document.getElementById('side_min').style.display = 'none';
		document.getElementById('side_max').style.left = '0em';
		document.getElementById('side_min').style.left = '0em';
		document.getElementById('side_hlp').style.left = '0em';
		document.getElementsByTagName('body')[0].style.marginLeft = '0em';
		side_pane_expanded = false;
	}
	else
	{
		document.getElementById('side_pane').style.width = '20em';
		document.getElementById('side_pane').style.padding = '1em';
		document.getElementById('side_pane').style.display = 'block';
		document.getElementById('side_max').style.display = 'none';
		document.getElementById('side_min').style.display = 'block';
		document.getElementById('side_max').style.left = '20em';
		document.getElementById('side_min').style.left = '20em';
		document.getElementById('side_hlp').style.left = '20em';
		document.getElementsByTagName('body')[0].style.marginLeft = '20em';
		side_pane_expanded = true;
	}
}

// add new escape entities as needed
function cleantextHTML(in_string)
{
	return in_string.replace(/\&/g,"&amp;").replace(/\>/g,"&gt;").replace(/\</g,"&lt;").replace(/\\\\n/g,"<br>");
}

// needs work
function cleantextJS(in_string)
{
	return in_string.replace("<br>","\\\\n").replace("&lt;","<").replace("&gt;",">").replace("&amp;","&");
}

function does_not_work_yet()
{
	alert('This function does not work yet.');
}
