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
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 
// 02110-1301  USA

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
var last_border_color = null;
var last_bg_color = null;
var current_values = {};
function select_chart_key(event)
{
	if (have_input_key_values_changed() == true)
	{
		alert("Stuff has changed. Set the key or reverse the change.");
		return;
	}

	var last_id = current_id;
	current_id = event.target.id.slice(7);

	// store values
	current_values.inp_keynum = current_id;
	current_values.inp_keyhgh = document.getElementById('keyhgh_' + current_id).innerText;
	current_values.inp_keylow = document.getElementById('keylow_' + current_id).innerText;
	current_values.inp_keyrgt = document.getElementById('keyrgt_' + current_id).innerText;
	current_values.inp_capnor = document.getElementById('capnor_' + current_id).innerText;
	current_values.inp_capshf = document.getElementById('capshf_' + current_id).innerText;
	current_values.inp_capctl = document.getElementById('capctl_' + current_id).innerText;
	current_values.inp_capalt = document.getElementById('capalt_' + current_id).innerText;
	current_values.inp_capagr = document.getElementById('capagr_' + current_id).innerText;
	current_values.inp_capxtr = document.getElementById('capxtr_' + current_id).innerText;
	current_values.sel_capnor = document.getElementById('capnor_' + current_id).getAttribute('color');
	current_values.sel_capshf = document.getElementById('capshf_' + current_id).getAttribute('color');
	current_values.sel_capctl = document.getElementById('capctl_' + current_id).getAttribute('color');
	current_values.sel_capalt = document.getElementById('capalt_' + current_id).getAttribute('color');
	current_values.sel_capagr = document.getElementById('capagr_' + current_id).getAttribute('color');
	current_values.sel_capxtr = document.getElementById('capxtr_' + current_id).getAttribute('color');

	// form inputs
	document.getElementById('inp_keynum').value = current_values.inp_keynum;
	document.getElementById('inp_keyhgh').value = current_values.inp_keyhgh;
	document.getElementById('inp_keylow').value = current_values.inp_keylow;
	document.getElementById('inp_keyrgt').value = current_values.inp_keyrgt;
	document.getElementById('inp_capnor').value = current_values.inp_capnor;
	document.getElementById('inp_capshf').value = current_values.inp_capshf;
	document.getElementById('inp_capctl').value = current_values.inp_capctl;
	document.getElementById('inp_capalt').value = current_values.inp_capalt;
	document.getElementById('inp_capagr').value = current_values.inp_capagr;
	document.getElementById('inp_capxtr').value = current_values.inp_capxtr;
	get_caption_color('sel_capnor', current_values.sel_capnor);
	get_caption_color('sel_capshf', current_values.sel_capshf);
	get_caption_color('sel_capctl', current_values.sel_capctl);
	get_caption_color('sel_capalt', current_values.sel_capalt);
	get_caption_color('sel_capagr', current_values.sel_capagr);
	get_caption_color('sel_capxtr', current_values.sel_capxtr);

	// styling (could be bundled into a class and moved into a stylesheet)
	if (last_id != null)
	{
		document.getElementById('keyout_' + last_id).style.borderColor = last_border_color;
		document.getElementById('keyout_' + last_id).style.backgroundColor = last_bg_color;
		document.getElementById('keyout_' + last_id).style.zIndex = 'initial';
		document.getElementById('keyout_' + last_id).style.filter = 'none';
	}
	last_border_color = document.getElementById('keyout_' + current_id).style.borderColor;
	last_bg_color = document.getElementById('keyout_' + current_id).style.backgroundColor;
	document.getElementById('keyout_' + current_id).style.borderColor = '#eee';
	document.getElementById('keyout_' + current_id).style.backgroundColor = '#eee';
	document.getElementById('keyout_' + current_id).style.zIndex = '2';
	document.getElementById('keyout_' + current_id).style.filter = 'drop-shadow(0px 0px 10px #eee)';
}

function get_caption_color(select_id, target_value)
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

function check_caption_color(select_id)
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
}

function set_input_key_values()
{
}
function unset_input_key_values()
{
	if (current_id != null)
	{
		// text inputs
		document.getElementById('inp_keyhgh').value = current_values.inp_keyhgh;
		document.getElementById('inp_keylow').value = current_values.inp_keylow;
		document.getElementById('inp_keyrgt').value = current_values.inp_keyrgt;
		document.getElementById('inp_capnor').value = current_values.inp_capnor;
		document.getElementById('inp_capshf').value = current_values.inp_capshf;
		document.getElementById('inp_capctl').value = current_values.inp_capctl;
		document.getElementById('inp_capalt').value = current_values.inp_capalt;
		document.getElementById('inp_capagr').value = current_values.inp_capagr;
		document.getElementById('inp_capxtr').value = current_values.inp_capxtr;

		// select inputs
		get_caption_color('sel_capnor', current_values.sel_capnor);
		get_caption_color('sel_capshf', current_values.sel_capshf);
		get_caption_color('sel_capctl', current_values.sel_capctl);
		get_caption_color('sel_capalt', current_values.sel_capalt);
		get_caption_color('sel_capagr', current_values.sel_capagr);
		get_caption_color('sel_capxtr', current_values.sel_capxtr);
	}
}

function have_input_key_values_changed()
{
	if (current_id != null)
	{
		// text inputs
		if (current_values.inp_keyhgh != document.getElementById('inp_keyhgh').value)
			return true;
		if (current_values.inp_keylow != document.getElementById('inp_keylow').value)
			return true;
		if (current_values.inp_keyrgt != document.getElementById('inp_keyrgt').value)
			return true;
		if (current_values.inp_capnor != document.getElementById('inp_capnor').value)
			return true;
		if (current_values.inp_capshf != document.getElementById('inp_capshf').value)
			return true;
		if (current_values.inp_capctl != document.getElementById('inp_capctl').value)
			return true;
		if (current_values.inp_capalt != document.getElementById('inp_capalt').value)
			return true;
		if (current_values.inp_capagr != document.getElementById('inp_capagr').value)
			return true;
		if (current_values.inp_capxtr != document.getElementById('inp_capxtr').value)
			return true;

		// select inputs
		if (current_values.sel_capnor != check_caption_color('sel_capnor'))
			return true;
		if (current_values.sel_capshf != check_caption_color('sel_capshf'))
			return true;
		if (current_values.sel_capctl != check_caption_color('sel_capctl'))
			return true;
		if (current_values.sel_capalt != check_caption_color('sel_capalt'))
			return true;
		if (current_values.sel_capagr != check_caption_color('sel_capagr'))
			return true;
		if (current_values.sel_capxtr != check_caption_color('sel_capxtr'))
			return true;
	}
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
			addListener(target_element, 'click', select_chart_key);
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
		document.getElementsByTagName('body')[0].style.marginLeft = '20em';
		side_pane_expanded = true;
	}
}

function does_not_work_yet()
{
	alert('This function does not work yet.');
}
