<?php header("Content-Type: text/javascript; charset=utf8"); ?>

// Video Game Keyboard Diagrams
// Copyright (C) 2018  Michael Horvath
// 
// This file is part of Video Game Keyboard Diagrams.
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as 
// published by the Free Software Foundation, either version 3 of the 
// License, or (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful, but 
// WITHOUT ANY WARRANTY; without even the implied warranty of 
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU 
// Lesser General Public License for more details.
// 
// You should have received a copy of the GNU Lesser General Public 
// License along with this program.  If not, see 
// <https://www.gnu.org/licenses/>.

new Image().src = 'animated_loading_icon.webp';

var keycount = 106;
var colors = ['non','red','yel','grn','cyn','blu','mag','wht','gry','blk','org','olv','brn'];
var layout_id = 1;
var is_key_dirty = false;
var is_leg_dirty = false;
var is_doc_dirty = false;
var is_eml_dirty = true;
var is_cap_dirty = false;
var last_id = null;
var last_class = null;
var current_id = null;
var current_values = {};
current_values.val_keynum = '';
current_values.val_lownor = '';
current_values.val_uppnor = '';
current_values.val_lowagr = '';
current_values.val_uppagr = '';
current_values.val_capnor = '';
current_values.val_capshf = '';
current_values.val_capctl = '';
current_values.val_capalt = '';
current_values.val_capagr = '';
current_values.val_capxtr = '';
current_values.val_imgfil = '';
current_values.val_imguri = '';
current_values.col_capnor = 0;
current_values.col_capshf = 0;
current_values.col_capctl = 0;
current_values.col_capalt = 0;
current_values.col_capagr = 0;
current_values.col_capxtr = 0;

function init_submissions()
{
	layout_id = getParameterByName('lay');
	disable_input_elements();
//	enable_doc_controls();
	add_input_typing_events();
	add_window_exit_event();
	addListener(document.body, 'click', click_document_body);
	addListener(document.getElementById('game_tit'), 'input', convert_title_to_seo);
	hide_loading_image();
}

function hide_loading_image()
{
	document.getElementById('waiting').style.display = 'none';
}

function show_loading_image()
{
	document.getElementById('waiting').style.display = 'block';
}

function click_document_body(event)
{
	var elm = event.target;
	while (elm !== document.body)
	{
		// Some buttons delete themselves, so you have to check if the button still exists.
		// Is the 'matches' method case-sensitive? Need to check.
		if (elm)
		{
			if
			(
				(is_key_dirty == true) &&
				(
					elm.matches('#set_doc_button') ||
					elm.matches('#unset_doc_button') ||
					elm.matches('.email_input') ||
					elm.matches('.email_textarea') ||
					elm.matches('#email_recaptcha') ||
					(true === false)
				)
			)
			{
				key_change_warning(elm, 'E');
				return;
			}
			else if
			(
				(is_leg_dirty == true) &&
				(
					elm.matches('#set_doc_button') ||
					elm.matches('#unset_doc_button') ||
					elm.matches('.email_input') ||
					elm.matches('.email_textarea') ||
					elm.matches('#email_recaptcha') ||
					(true === false)
				)
			)
			{
				key_legend_warning(elm, 'E');
				return;
			}
			else if
			(
				elm.matches('#pane_lft') ||
				elm.matches('#table_inp') ||
				elm.matches('#button_inp') ||
				elm.matches('#tbar_lft') ||
				elm.matches('#tbar_rgt') ||
				elm.matches('.side_butt') ||
				elm.matches('.tabs_butt') ||
				(true === false)
			)
			{
				// do nothing
				return;
			}
			else if (elm.matches('.keyout'))
			{
				click_on_chart_key(elm);
				return;
			}
			// could be optimized a little
			else if
			(
				(is_leg_dirty == true) &&
				(does_ancestor_have_id(elm, 'pane_rgt') == true) &&
				(
					(elm.tagName.toUpperCase() == 'INPUT') ||
					(elm.tagName.toUpperCase() == 'BUTTON') ||
					(elm.tagName.toUpperCase() == 'TEXTAREA') ||
					(elm.tagName.toUpperCase() == 'SELECT') ||
					(elm.tagName.toUpperCase() == 'OPTION') ||
					(true === false)
				)
			)
			{
				key_legend_warning(elm, 'A');
				return;
			}
			// could be optimized a little
			else if
			(
				(is_key_dirty == true) &&
				(does_ancestor_have_id(elm, 'pane_rgt') == true) &&
				(
					(elm.tagName.toUpperCase() == 'INPUT') ||
					(elm.tagName.toUpperCase() == 'BUTTON') ||
					(elm.tagName.toUpperCase() == 'TEXTAREA') ||
					(elm.tagName.toUpperCase() == 'SELECT') ||
					(elm.tagName.toUpperCase() == 'OPTION') ||
					(true === false)
				)
			)
			{
				key_change_warning(elm, 'A');
				return;
			}
			else if (elm.matches('.butsub'))
			{
				remove_input_table_row(elm);
				flag_doc_dirty();
				return;
			}
			else if (elm.matches('.butadd'))
			{
				add_input_table_row(elm);
				flag_doc_dirty();
				return;
			}
			else if (elm.matches('.txtsub'))
			{
				remove_textarea_table_row(elm);
				flag_doc_dirty();
				return;
			}
			else if (elm.matches('.txtadd'))
			{
				add_textarea_table_row(elm);
				flag_doc_dirty();
				return;
			}
			elm = elm.parentNode;	// use parentElement instead?
		}
		else
		{
			break;
		}
	}
	click_off_chart_key(elm);
	return;
}

function click_on_chart_key(elm)
{
	if (is_key_dirty == true)
	{
		key_change_warning(elm, 'C');
		return;
	}
	if (is_leg_dirty == true)
	{
		key_legend_warning(elm, 'C');
		return;
	}

	last_id = current_id;
	current_id = elm.id.slice(7);

	enable_input_elements();
	push_values_from_array_into_cache();
	push_values_from_cache_into_form();

	if (last_id != null)
	{
		document.getElementById('keyout_' + last_id).className = last_class;
	}
	last_class = document.getElementById('keyout_' + current_id).className;
	document.getElementById('keyout_' + current_id).className = 'keyout keysel';
}

function click_off_chart_key(elm)
{
	if (is_key_dirty == true)
	{
		key_change_warning(elm, 'B');
		return;
	}
	if (is_leg_dirty == true)
	{
		key_legend_warning(elm, 'B');
		return;
	}

	if (current_id !== null)
	{
		document.getElementById('keyout_' + current_id).className = last_class;
	}

	last_id = null
	last_class = null;
	current_id = null;
	current_values = {};
	current_values.val_keynum = '';
	current_values.val_lownor = '';
	current_values.val_uppnor = '';
	current_values.val_lowagr = '';
	current_values.val_uppagr = '';
	current_values.val_capnor = '';
	current_values.val_capshf = '';
	current_values.val_capctl = '';
	current_values.val_capalt = '';
	current_values.val_capagr = '';
	current_values.val_capxtr = '';
	current_values.val_imgfil = '';
	current_values.val_imguri = '';
	current_values.col_capnor = 0;
	current_values.col_capshf = 0;
	current_values.col_capctl = 0;
	current_values.col_capalt = 0;
	current_values.col_capagr = 0;
	current_values.col_capxtr = 0;

	push_values_from_cache_into_form();
	disable_input_elements();
}

function key_save_changes()
{
	if (is_embedded_image_okay() == false)
	{
		image_file_warning();
		return;
	}
	push_values_from_form_into_cache();
	push_values_from_cache_into_array();
	document.getElementById('set_key_button').disabled = true;
	document.getElementById('unset_key_button').disabled = true;
	flag_key_clean();
//	flag_leg_clean();		// this may actually confuse users
	flag_doc_dirty();
}

function key_revert_changes()
{
	push_values_from_array_into_cache();
	push_values_from_cache_into_form();
	document.getElementById('set_key_button').disabled = true;
	document.getElementById('unset_key_button').disabled = true;
	flag_key_clean();
	flag_leg_clean();		// this may actually confuse users
}

function is_embedded_image_okay()
{
	var img_filename = document.getElementById('inp_imgfil').value;
	var img_datauri = document.getElementById('inp_imguri').value;
	if
	(
		((img_filename == '') && (img_datauri != '')) ||
		((img_filename != '') && (img_datauri == ''))
	)
	{
		return false;
	}
	return true;
}

function set_form_color(this_select, target_value)
{
	this_select.selectedIndex = target_value;
	this_select.className = 'sel' + colors[target_value];
}

function get_form_color(this_select)
{
	return this_select.selectedIndex;
}

function push_values_from_array_into_cache()
{
	if (current_id == null)
		return null;

	current_values.val_keynum = current_id;
	current_values.val_lownor = binding_table[current_id][ 0];
	current_values.val_uppnor = binding_table[current_id][ 1];
	current_values.val_lowagr = binding_table[current_id][ 2];
	current_values.val_uppagr = binding_table[current_id][ 3];
	current_values.val_capnor = binding_table[current_id][ 4];
	current_values.val_capshf = binding_table[current_id][ 5];
	current_values.val_capctl = binding_table[current_id][ 6];
	current_values.val_capalt = binding_table[current_id][ 7];
	current_values.val_capagr = binding_table[current_id][ 8];
	current_values.val_capxtr = binding_table[current_id][ 9];
	current_values.val_imgfil = binding_table[current_id][10];
	current_values.val_imguri = binding_table[current_id][11];
	current_values.col_capnor = binding_table[current_id][12];
	current_values.col_capshf = binding_table[current_id][13];
	current_values.col_capctl = binding_table[current_id][14];
	current_values.col_capalt = binding_table[current_id][15];
	current_values.col_capagr = binding_table[current_id][16];
	current_values.col_capxtr = binding_table[current_id][17];
}

function push_values_from_form_into_cache()
{
	if (current_id == null)
		return null;

	current_values.val_keynum = document.getElementById('inp_keynum').value;
	current_values.val_lownor = document.getElementById('inp_lownor').value;
	current_values.val_uppnor = document.getElementById('inp_uppnor').value;
	current_values.val_lowagr = document.getElementById('inp_lowagr').value;
	current_values.val_uppagr = document.getElementById('inp_uppagr').value;
	current_values.val_capnor = document.getElementById('inp_capnor').value;
	current_values.val_capshf = document.getElementById('inp_capshf').value;
	current_values.val_capctl = document.getElementById('inp_capctl').value;
	current_values.val_capalt = document.getElementById('inp_capalt').value;
	current_values.val_capagr = document.getElementById('inp_capagr').value;
	current_values.val_capxtr = document.getElementById('inp_capxtr').value;
	current_values.val_imgfil = document.getElementById('inp_imgfil').value;
	current_values.val_imguri = document.getElementById('inp_imguri').value;
	current_values.col_capnor = get_form_color(document.getElementById('sel_capnor'));
	current_values.col_capshf = get_form_color(document.getElementById('sel_capshf'));
	current_values.col_capctl = get_form_color(document.getElementById('sel_capctl'));
	current_values.col_capalt = get_form_color(document.getElementById('sel_capalt'));
	current_values.col_capagr = get_form_color(document.getElementById('sel_capagr'));
	current_values.col_capxtr = get_form_color(document.getElementById('sel_capxtr'));
}

function push_values_from_cache_into_array()
{
	if (current_id == null)
		return null;

	binding_table[current_id][ 0] = current_values.val_lownor;
	binding_table[current_id][ 1] = current_values.val_uppnor;
	binding_table[current_id][ 2] = current_values.val_lowagr;
	binding_table[current_id][ 3] = current_values.val_uppagr;
	binding_table[current_id][ 4] = current_values.val_capnor;
	binding_table[current_id][ 5] = current_values.val_capshf;
	binding_table[current_id][ 6] = current_values.val_capctl;
	binding_table[current_id][ 7] = current_values.val_capalt;
	binding_table[current_id][ 8] = current_values.val_capagr;
	binding_table[current_id][ 9] = current_values.val_capxtr;
	binding_table[current_id][10] = current_values.val_imgfil;
	binding_table[current_id][11] = current_values.val_imguri;
	binding_table[current_id][12] = current_values.col_capnor;
	binding_table[current_id][13] = current_values.col_capshf;
	binding_table[current_id][14] = current_values.col_capctl;
	binding_table[current_id][15] = current_values.col_capalt;
	binding_table[current_id][16] = current_values.col_capagr;
	binding_table[current_id][17] = current_values.col_capxtr;

	document.getElementById('lownor_' + current_id).innerHTML = cleantextHTML(current_values.val_lownor);
	document.getElementById('uppnor_' + current_id).innerHTML = cleantextHTML(current_values.val_uppnor);
	document.getElementById('lowagr_' + current_id).innerHTML = cleantextHTML(current_values.val_lowagr);
	document.getElementById('uppagr_' + current_id).innerHTML = cleantextHTML(current_values.val_uppagr);
	document.getElementById('capnor_' + current_id).innerHTML = cleantextHTML(current_values.val_capnor);
	document.getElementById('capshf_' + current_id).innerHTML = cleantextHTML(current_values.val_capshf);
	document.getElementById('capctl_' + current_id).innerHTML = cleantextHTML(current_values.val_capctl);
	document.getElementById('capalt_' + current_id).innerHTML = cleantextHTML(current_values.val_capalt);
	document.getElementById('capagr_' + current_id).innerHTML = cleantextHTML(current_values.val_capagr);
	document.getElementById('capxtr_' + current_id).innerHTML = cleantextHTML(current_values.val_capxtr);

	// Need to make certain that both a filename *and* a data URI are present!!
	var this_image = document.getElementById('capimg_' + current_id);
	this_image.src = current_values.val_imguri;
	if (current_values.val_imguri != '')
	{
		this_image.style.display = 'block';
	}
	else
	{
		this_image.style.display = 'none';
	}

	last_class = 'keyout cap' + colors[current_values.col_capnor];
}

function push_values_from_cache_into_form()
{
	document.getElementById('inp_keynum').value = current_values.val_keynum;
	document.getElementById('inp_lownor').value = current_values.val_lownor;
	document.getElementById('inp_uppnor').value = current_values.val_uppnor;
	document.getElementById('inp_lowagr').value = current_values.val_lowagr;
	document.getElementById('inp_uppagr').value = current_values.val_uppagr;
	document.getElementById('inp_capnor').value = current_values.val_capnor;
	document.getElementById('inp_capshf').value = current_values.val_capshf;
	document.getElementById('inp_capctl').value = current_values.val_capctl;
	document.getElementById('inp_capalt').value = current_values.val_capalt;
	document.getElementById('inp_capagr').value = current_values.val_capagr;
	document.getElementById('inp_capxtr').value = current_values.val_capxtr;
	document.getElementById('inp_imgfil').value = current_values.val_imgfil;
	document.getElementById('inp_imguri').value = current_values.val_imguri;
	set_form_color(document.getElementById('sel_capnor'), current_values.col_capnor);
	set_form_color(document.getElementById('sel_capshf'), current_values.col_capshf);
	set_form_color(document.getElementById('sel_capctl'), current_values.col_capctl);
	set_form_color(document.getElementById('sel_capalt'), current_values.col_capalt);
	set_form_color(document.getElementById('sel_capagr'), current_values.col_capagr);
	set_form_color(document.getElementById('sel_capxtr'), current_values.col_capxtr);
}

function have_input_key_values_changed()
{
	if (current_id == null)
		return null;

	if
	(
		(current_values.val_lownor != document.getElementById('inp_lownor').value) ||
		(current_values.val_uppnor != document.getElementById('inp_uppnor').value) ||
		(current_values.val_lowagr != document.getElementById('inp_lowagr').value) ||
		(current_values.val_uppagr != document.getElementById('inp_uppagr').value) ||
		(current_values.val_capnor != document.getElementById('inp_capnor').value) ||
		(current_values.val_capshf != document.getElementById('inp_capshf').value) ||
		(current_values.val_capctl != document.getElementById('inp_capctl').value) ||
		(current_values.val_capalt != document.getElementById('inp_capalt').value) ||
		(current_values.val_capagr != document.getElementById('inp_capagr').value) ||
		(current_values.val_capxtr != document.getElementById('inp_capxtr').value) ||
		(current_values.val_imgfil != document.getElementById('inp_imgfil').value) ||
		(current_values.val_imguri != document.getElementById('inp_imguri').value) ||
		(current_values.col_capnor != get_form_color(document.getElementById('sel_capnor'))) ||
		(current_values.col_capshf != get_form_color(document.getElementById('sel_capshf'))) ||
		(current_values.col_capctl != get_form_color(document.getElementById('sel_capctl'))) ||
		(current_values.col_capalt != get_form_color(document.getElementById('sel_capalt'))) ||
		(current_values.col_capagr != get_form_color(document.getElementById('sel_capagr'))) ||
		(current_values.col_capxtr != get_form_color(document.getElementById('sel_capxtr'))) ||
		(true === false)
	)
	{
		return true;
	}

	return false;
}

function are_all_captions_in_groups()
{
	if
	(
		((document.getElementById('inp_capnor').value != '') && (get_form_color(document.getElementById('sel_capnor')) == 0)) ||
		((document.getElementById('inp_capshf').value != '') && (get_form_color(document.getElementById('sel_capshf')) == 0)) ||
		((document.getElementById('inp_capctl').value != '') && (get_form_color(document.getElementById('sel_capctl')) == 0)) ||
		((document.getElementById('inp_capalt').value != '') && (get_form_color(document.getElementById('sel_capalt')) == 0)) ||
		((document.getElementById('inp_capagr').value != '') && (get_form_color(document.getElementById('sel_capagr')) == 0)) ||
		((document.getElementById('inp_capxtr').value != '') && (get_form_color(document.getElementById('sel_capxtr')) == 0)) ||
		(true === false)
	)
	{
		return false;
	}

	return true;
}

// Need to rename this function since its purpose has changed slightly.
function toggle_set_and_revert_buttons(event)
{
	var val_change = have_input_key_values_changed();
	var leg_change = are_all_captions_in_groups();

	if (val_change == true)
		flag_key_dirty();
	else
		flag_key_clean();
	if (leg_change == false)
		flag_leg_dirty();
	else
		flag_leg_clean();

	if ((val_change == true) || (leg_change == false))
	{
		document.getElementById('set_key_button').disabled = false;
		document.getElementById('unset_key_button').disabled = false;
	}
	else
	{
		document.getElementById('set_key_button').disabled = true;
		document.getElementById('unset_key_button').disabled = true;
	}

	var elm = event.target;
	if (elm.tagName.toUpperCase() == 'SELECT')
	{
		var this_index = elm.selectedIndex;
		elm.className = 'sel' + colors[this_index];
	}
}

function add_window_exit_event()
{
	addListener(window, 'beforeunload', window_leave_warning);
}

function add_input_typing_events()
{
	addListener(document.getElementById('inp_keynum'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_lownor'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_uppnor'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_lowagr'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_uppagr'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_capnor'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_capshf'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_capctl'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_capalt'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_capagr'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_capxtr'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_imgfil'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('inp_imguri'), 'input', toggle_set_and_revert_buttons);
	addListener(document.getElementById('sel_capnor'), 'change', toggle_set_and_revert_buttons);
	addListener(document.getElementById('sel_capshf'), 'change', toggle_set_and_revert_buttons);
	addListener(document.getElementById('sel_capctl'), 'change', toggle_set_and_revert_buttons);
	addListener(document.getElementById('sel_capalt'), 'change', toggle_set_and_revert_buttons);
	addListener(document.getElementById('sel_capagr'), 'change', toggle_set_and_revert_buttons);
	addListener(document.getElementById('sel_capxtr'), 'change', toggle_set_and_revert_buttons);
}

// add new escape entities as needed
function cleantextHTML(in_string)
{
	return in_string.replace(/\&/g,'&amp;').replace(/\>/g,'&gt;').replace(/\</g,'&lt;').replace(/\\n/g,'<br>');
}

function cleantextTSV(in_string)
{
	in_string = in_string.replace(/\\/g,'\\\\');
	if (in_string == '')
	{
		in_string = '\\N';
	}
	return in_string;
}

function cleannumTSV(in_number)
{
	if (in_number == '0')
	{
		in_number = '\\N';
	}
	return in_number;
}

// needs work
//function cleantextJS(in_string)
//{
//	return in_string.replace('\'','\\\'');
//}

function does_not_work_yet()
{
	alert('This feature does not work yet.');
}

function test_function()
{
	alert('Hello world!');
}

function remove_input_table_row(button_node)
{
	var parent_node = button_node.parentNode.parentNode;
	var ancest_node = parent_node.parentNode;
	ancest_node.removeChild(parent_node);
}

function add_input_table_row(button_node)
{
	var parent_node = button_node.parentNode.parentNode;
	var ancest_node = parent_node.parentNode;
	var new_node = create_input_table_row();
	ancest_node.removeChild(parent_node);
	ancest_node.appendChild(new_node);
	ancest_node.appendChild(parent_node);
}

function create_input_table_row()
{
	var new_row = document.createElement('div');
	new_row.className = 'notrow';
	var new_cll_1 = document.createElement('div');
	new_cll_1.className = 'notcll cllone';
	var new_inp_1 = document.createElement('input');
	new_inp_1.setAttribute('type','text');
	new_inp_1.setAttribute('maxlength','100');
	new_inp_1.setAttribute('placeholder','blah');
	new_inp_1.setAttribute('autocomplete','off');
	new_inp_1.onchange = flag_doc_dirty;
	new_cll_1.appendChild(new_inp_1);
	new_row.appendChild(new_cll_1);
	var new_cll_2 = document.createElement('div');
	new_cll_2.className = 'notcll clltwo';
	var new_txt_2 = document.createTextNode('=');
	new_cll_2.appendChild(new_txt_2);
	new_row.appendChild(new_cll_2);
	var new_cll_3 = document.createElement('div');
	new_cll_3.className = 'notcll cllthr';
	var new_inp_3 = document.createElement('input');
	new_inp_3.setAttribute('type','text');
	new_inp_3.setAttribute('maxlength','100');
	new_inp_3.setAttribute('placeholder','blah');
	new_inp_3.setAttribute('autocomplete','off');
	new_inp_3.onchange = flag_doc_dirty;
	new_cll_3.appendChild(new_inp_3);
	new_row.appendChild(new_cll_3);
	var new_cll_4 = document.createElement('div');
	new_cll_4.className = 'notcll cllfor';
	var new_but_4 = document.createElement('button');
	new_but_4.className = 'butsub';
	new_but_4.setAttribute('type','button');
	var new_txt_4 = document.createTextNode('-');
	new_but_4.appendChild(new_txt_4);
	new_cll_4.appendChild(new_but_4);
	new_row.appendChild(new_cll_4);
	return new_row;
}

function remove_textarea_table_row(button_node)
{
	var parent_node = button_node.parentNode.parentNode;
	var ancest_node = parent_node.parentNode;
	ancest_node.removeChild(parent_node);
}

function add_textarea_table_row(button_node)
{
	var parent_node = button_node.parentNode.parentNode;
	var ancest_node = parent_node.parentNode;
	var new_node = create_textarea_table_row();
	ancest_node.removeChild(parent_node);
	ancest_node.appendChild(new_node);
	ancest_node.appendChild(parent_node);
}

function create_textarea_table_row()
{
	var new_row = document.createElement('div');
	new_row.className = 'notrow';
	var new_cll_1 = document.createElement('div');
	new_cll_1.className = 'notcll txtone';
	var new_txt_1 = document.createElement('textarea');
	new_txt_1.setAttribute('placeholder','blah');
	new_txt_1.onchange = flag_doc_dirty;
	new_cll_1.appendChild(new_txt_1);
	new_row.appendChild(new_cll_1);
	var new_cll_2 = document.createElement('div');
	new_cll_2.className = 'notcll txttwo';
	var new_but_2 = document.createElement('button');
	new_but_2.className = 'txtsub';
	new_but_2.setAttribute('type','button');
	var new_txt_2 = document.createTextNode('-');
	new_but_2.appendChild(new_txt_2);
	new_cll_2.appendChild(new_but_2);
	new_row.appendChild(new_cll_2);
	return new_row;
}

function toggle_left_pane(side_but)
{
	if (side_but == 0)
	{
		document.getElementById('pane_lft').style.left = '-20em';
		document.getElementById('pane_rgt').style.left = '0em';
		document.getElementById('pane_rgt').style.width = '100%';
		document.getElementById('butt_max').style.display = 'block';
		document.getElementById('butt_min').style.display = 'none';
		document.getElementById('butt_max').style.left = '0em';
		document.getElementById('butt_min').style.left = '0em';
	}
	else if (side_but == 1)
	{
		document.getElementById('pane_lft').style.left = '0em';
		document.getElementById('pane_rgt').style.left = '20em';
		document.getElementById('pane_rgt').style.width = 'calc(100% - 20em)';
		document.getElementById('butt_max').style.display = 'none';
		document.getElementById('butt_min').style.display = 'block';
		document.getElementById('butt_max').style.left = '20em';
		document.getElementById('butt_min').style.left = '20em';
	}
}

function switch_left_pane(side_tab)
{
	if (side_tab == 0)
	{
		document.getElementById('butt_inp').style.backgroundColor = '#eee';
		document.getElementById('butt_hlp').style.backgroundColor = '#ccc';
		document.getElementById('pane_inp').style.display = 'block';
		document.getElementById('pane_hlp').style.display = 'none';
	}
	else if (side_tab == 1)
	{
		document.getElementById('butt_inp').style.backgroundColor = '#ccc';
		document.getElementById('butt_hlp').style.backgroundColor = '#eee';
		document.getElementById('pane_inp').style.display = 'none';
		document.getElementById('pane_hlp').style.display = 'block';
	}
}

function switch_right_pane(side_tab)
{
	if (side_tab == 0)
	{
		document.getElementById('butt_kbd').style.backgroundColor = '#eee';
		document.getElementById('butt_csv').style.backgroundColor = '#ccc';
		document.getElementById('pane_kbd').style.display = 'block';
		document.getElementById('pane_tsv').style.display = 'none';
	}
	else if (side_tab == 1)
	{
		document.getElementById('butt_kbd').style.backgroundColor = '#ccc';
		document.getElementById('butt_csv').style.backgroundColor = '#eee';
		document.getElementById('pane_kbd').style.display = 'none';
		document.getElementById('pane_tsv').style.display = 'block';
	}
}

function enable_input_elements()
{
	document.getElementById('inp_capnor').disabled = false;
	document.getElementById('inp_capshf').disabled = false;
	document.getElementById('inp_capctl').disabled = false;
	document.getElementById('inp_capalt').disabled = false;
	document.getElementById('inp_capagr').disabled = false;
	document.getElementById('inp_capxtr').disabled = false;
	document.getElementById('inp_imgfil').disabled = false;
	document.getElementById('inp_imguri').disabled = false;
	document.getElementById('sel_capnor').disabled = false;
	document.getElementById('sel_capshf').disabled = false;
	document.getElementById('sel_capctl').disabled = false;
	document.getElementById('sel_capalt').disabled = false;
	document.getElementById('sel_capagr').disabled = false;
	document.getElementById('sel_capxtr').disabled = false;
}

function disable_input_elements()
{
	document.getElementById('inp_capnor').disabled = true;
	document.getElementById('inp_capshf').disabled = true;
	document.getElementById('inp_capctl').disabled = true;
	document.getElementById('inp_capalt').disabled = true;
	document.getElementById('inp_capagr').disabled = true;
	document.getElementById('inp_capxtr').disabled = true;
	document.getElementById('inp_imgfil').disabled = true;
	document.getElementById('inp_imguri').disabled = true;
	document.getElementById('sel_capnor').disabled = true;
	document.getElementById('sel_capshf').disabled = true;
	document.getElementById('sel_capctl').disabled = true;
	document.getElementById('sel_capalt').disabled = true;
	document.getElementById('sel_capagr').disabled = true;
	document.getElementById('sel_capxtr').disabled = true;
}

function does_ancestor_have_id(elm, elmid)
{
	if (elm.id == elmid) return true;
	return elm.parentElement && does_ancestor_have_id(elm.parentElement, elmid);
}

if (!Element.prototype.matches)
{
	Element.prototype.matches = Element.prototype.msMatchesSelector;
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

function key_change_warning(elm, letter)
{
	elm.blur();
	document.body.focus();
	alert('A key has been altered. Please save or revert any changes to this key before proceeding. (' + letter + ')');
}

function key_legend_warning(elm, letter)
{
	elm.blur();
	document.body.focus();
	alert('A caption has been set, but its color has not also been set as well. Please make sure every caption has a corresponding color. This includes SHIFT, CTRL, ALT and ALTGR captions. Alternatively, you revert any changes to the key. (' + letter + ')');
}

function document_change_warning(letter)
{
	return confirm('Pressing this button will reset the submission form to its orignal state, discarding any changes you may have made. Do you wish to proceed? (' + letter + ')');
}

function image_file_warning()
{
	alert('An image file name and data URI need to both be provided.');	
}

function document_save_warning(letter)
{
//	return confirm('Pressing this button will post the keyboard bindings data to the site admin. You will not be able to return later and continue where you left off. Do you wish to proceed? (' + letter + ')');
	return confirm('Pressing this button will post the keyboard bindings data to the site admin. Do you wish to proceed? (' + letter + ')');
}

function window_leave_warning(event)
{
	if (is_doc_dirty == true)
	{
		var confirmationMessage = 'You are about to leave the page, but have unsaved data. Do you wish to proceed?';
		event.returnValue = confirmationMessage;	// Gecko, Trident, Chrome >34
		return confirmationMessage;			// Gecko, WebKit, Chrome <34
	}
}

function document_save_changes()
{
	if (is_key_dirty == true)
	{
		var elm = document.getElementById('set_doc_button');
		key_change_warning(elm, 'D');
		return;
	}
	if (is_leg_dirty == true)
	{
		var elm = document.getElementById('set_doc_button');
		key_legend_warning(elm, 'D');
		return;
	}
	if (document_save_warning('A') == false)
	{
		return;
	}
	collect_nonkey_data();
	document.getElementById('email_4').value = document.getElementById('game_tit').value;
	document.getElementById('email_5').value = document.getElementById('game_url').value;
	document.getElementById('email_6').value = process_legend_data();
	document.getElementById('email_7').value = process_command_data();
	document.getElementById('email_8').value = process_binding_data();
	document.getElementById('email_9').value = layout_id;
	do_recaptcha();
}

// https://stackoverflow.com/questions/30006081/recaptcha-2-0-with-ajax
// May need a polyfill for all the validity check stuff so they work in as many browsers as possible.
function do_recaptcha()
{
	show_loading_image();

	console.log('RECAPTCHA: clicked submit'); // --> works

	var valid	= true;
	var errors	= '';
	var issues	= '';
	var name	= $('#email_1');
	var email	= $('#email_2');
	var message	= $('#email_3');
	var titletxt	= $('#email_4');
	var titleurl	= $('#email_5');
	var legend	= $('#email_6');
	var command	= $('#email_7');
	var binding	= $('#email_8');
	var layout	= $('#email_9');
	var captcha	= grecaptcha.getResponse();		// THIS WILL TELL THE FORM IF THE USER IS CAPTCHA VERIFIED.

	if (!name.get(0).validity.valid || !email.get(0).validity.valid || !message.get(0).validity.valid)
	{
		valid = false;
		errors = 'ERROR: The name, email and message fields need to be filled in properly before you may proceed. Please go back and try again.';
		issues = 'RECAPTCHA: malformed email input data';
	}

//	console.log('RECAPTCHA: captcha response\n' + captcha);

	if (valid == true)
	{
		// ajax to the php file to send the mail
		$.ajax
		({
			type: "POST",
			url: "keyboard-recaptcha.php",
			data:
			{
				name:		name.val(),
				email:		email.val(),
				message:	message.val(),
				titletxt:	titletxt.val(),
				titleurl:	titleurl.val(),
				legend:		legend.val(),
				command:	command.val(),
				binding:	binding.val(),
				layout:		layout.val(),
				captcha:	captcha
			}
		}).done(function(status)
		{
			if (status == "ok")
			{
				alert('Thanks! Your message has been sent, and an admin will contact you shortly.');
				// clear the form fields
//				$('#email_1').val('');
//				$('#email_2').val('');
//				$('#email_3').val('');
//				$('#email_4').val('');
//				$('#email_5').val('');
//				$('#email_6').val('');
//				$('#email_7').val('');
//				$('#email_8').val('');
//				$('#email_9').val('');
				if (is_doc_dirty == true)
				{
					flag_doc_clean();
				}
				console.log('RECAPTCHA: mail was sent');
				hide_loading_image();
			}
			else
			{
				alert('There were problems with reCAPTCHA. Try again.');
				console.log('RECAPTCHA: mail was not sent');
				hide_loading_image();
			}
		});
		return;
	}
	else
	{
		alert(errors);
		console.log(issues);
		hide_loading_image();
		return;
	}
}

function document_revert_changes()
{
	var okay = document_change_warning('A');
	if (okay == true)
	{
		location.reload();
	}
}

function document_change_style(game_id, layout_id, game_seo)
{
	var okay = document_change_warning('B');
	if (okay == true)
	{
		reloadThisPage(game_id, layout_id, game_seo);
	}
}

// there is an analogous function written in PHP in "keyboard-common.php"
// need to keep the two functions synced
function seo_url(input)
{
	input = input.toLowerCase();					//convert to lowercase
	input = input.replace(/'|"/gm, '');				//remove single and double quotes
	input = input.replace(/[^a-zA-Z0-9]+/gm, '-');			//replace everything non-alphanumeric with dashes
	input = input.replace(/\-+/gm, '-');				//replace multiple dashes with one dash
	input = input.replace(/^\-+|\-+$/gm, '');			//trim dashes from the beginning and end of the string if any
	return input;
}

function convert_title_to_seo(event)
{
	var elm = event.target;
	var left_value = elm.value;
	var right_value = seo_url(left_value);
	document.getElementById('game_url').value = right_value;
}

function flag_key_dirty()
{
	is_key_dirty = true;
}

function flag_key_clean()
{
	is_key_dirty = false;
}

function flag_leg_dirty()
{
	is_leg_dirty = true;
}

function flag_leg_clean()
{
	is_leg_dirty = false;
}

function flag_doc_dirty()
{
	is_doc_dirty = true;
	check_all_dirty();
}

function flag_doc_clean()
{
	is_doc_dirty = false;
	check_all_dirty();
}

function flag_cap_dirty()
{
	is_cap_dirty = true;
	check_all_dirty();
}

function flag_cap_clean()
{
	is_cap_dirty = false;
	check_all_dirty();
}

function flag_eml_clean()
{
	var name	= $('#email_1');
	var email	= $('#email_2');
	var message	= $('#email_3');
	if
	(
		name.val() == ''	||
		email.val() == ''	||
		message.val() == ''	||
		!name.get(0).validity.valid	||
		!email.get(0).validity.valid	||
		!message.get(0).validity.valid	||
		(true === false)
	)
	{
		is_eml_dirty = true;
	}
	else
	{
		is_eml_dirty = false;
	}
	check_all_dirty();
}

function check_all_dirty()
{
	if
	(
		(is_doc_dirty == true)  &&
		(is_cap_dirty == true)  &&
		(is_eml_dirty == false) &&
		(true === true)
	)
	{
		enable_doc_controls();
	}
	else
	{
		disable_doc_controls();
	}
}

function enable_doc_controls()
{
	document.getElementById('set_doc_button').disabled = false;
	document.getElementById('unset_doc_button').disabled = false;
}

function disable_doc_controls()
{
	document.getElementById('set_doc_button').disabled = true;
	document.getElementById('unset_doc_button').disabled = true;
}

function collect_nonkey_data()
{
	legend_table = [];
	combo_table = [];
	mouse_table = [];
	joystick_table = [];
	note_table = [];
	cheat_table = [];
	console_table = [];
	emote_table = [];

	// legend
	for (var i = 0; i < 12; i++)
	{
		var this_color = colors[i+1];
		var this_input = document.getElementById('form_cap' + this_color);
		var this_value = this_input.value;
		if (this_value != '')
		{
			legend_table.push([i+1, this_value]);
		}
	}
	// combo - ignore the last child
	var this_parent = document.getElementById('table_combo');
	for (var i = 0, n = this_parent.children.length - 1; i < n; i++)
	{
		var this_child = this_parent.children[i];
		var this_input_1 = this_child.children[0].children[0];
		var this_input_2 = this_child.children[2].children[0];
		var this_value_1 = this_input_1.value;
		var this_value_2 = this_input_2.value;
		if ((this_value_1 != '') && (this_value_2 != ''))
		{
			combo_table.push([this_value_1, this_value_2]);
		}
	}
	// mouse - ignore the last child
	var this_parent = document.getElementById('table_mouse');
	for (var i = 0, n = this_parent.children.length - 1; i < n; i++)
	{
		var this_child = this_parent.children[i];
		var this_input_1 = this_child.children[0].children[0];
		var this_input_2 = this_child.children[2].children[0];
		var this_value_1 = this_input_1.value;
		var this_value_2 = this_input_2.value;
		if ((this_value_1 != '') && (this_value_2 != ''))
		{
			mouse_table.push([this_value_1, this_value_2]);
		}
	}
	// joystick - ignore the last child
	var this_parent = document.getElementById('table_joystick');
	for (var i = 0, n = this_parent.children.length - 1; i < n; i++)
	{
		var this_child = this_parent.children[i];
		var this_input_1 = this_child.children[0].children[0];
		var this_input_2 = this_child.children[2].children[0];
		var this_value_1 = this_input_1.value;
		var this_value_2 = this_input_2.value;
		if ((this_value_1 != '') && (this_value_2 != ''))
		{
			joystick_table.push([this_value_1, this_value_2]);
		}
	}
	// note - ignore the last child
	var this_parent = document.getElementById('table_note');
	for (var i = 0, n = this_parent.children.length - 1; i < n; i++)
	{
		var this_child = this_parent.children[i];
		var this_textarea = this_child.children[0].children[0];
		var this_value = this_textarea.value;
		if (this_value != '')
		{
			note_table.push(['', this_value]);
		}
	}
	// cheat - ignore the last child
	var this_parent = document.getElementById('table_cheat');
	for (var i = 0, n = this_parent.children.length - 1; i < n; i++)
	{
		var this_child = this_parent.children[i];
		var this_input_1 = this_child.children[0].children[0];
		var this_input_2 = this_child.children[2].children[0];
		var this_value_1 = this_input_1.value;
		var this_value_2 = this_input_2.value;
		if ((this_value_1 != '') && (this_value_2 != ''))
		{
			cheat_table.push([this_value_1, this_value_2]);
		}
	}
	// console - ignore the last child
	var this_parent = document.getElementById('table_console');
	for (var i = 0, n = this_parent.children.length - 1; i < n; i++)
	{
		var this_child = this_parent.children[i];
		var this_input_1 = this_child.children[0].children[0];
		var this_input_2 = this_child.children[2].children[0];
		var this_value_1 = this_input_1.value;
		var this_value_2 = this_input_2.value;
		if ((this_value_1 != '') && (this_value_2 != ''))
		{
			console_table.push([this_value_1, this_value_2]);
		}
	}
	// emote - ignore the last child
	var this_parent = document.getElementById('table_emote');
	for (var i = 0, n = this_parent.children.length - 1; i < n; i++)
	{
		var this_child = this_parent.children[i];
		var this_input_1 = this_child.children[0].children[0];
		var this_input_2 = this_child.children[2].children[0];
		var this_value_1 = this_input_1.value;
		var this_value_2 = this_input_2.value;
		if ((this_value_1 != '') && (this_value_2 != ''))
		{
			emote_table.push([this_value_1, this_value_2]);
		}
	}
}

function process_legend_data()
{
	var legend_string = 'legend_id\trecord_id\tlegend_group\tlegend_description\n';
	for (var i = 0, n = legend_table.length; i < n; i++)
	{
		var legend_item = legend_table[i];
		legend_string += '\\N\t' + record_id + '\t' + legend_item[0] + '\t' + cleantextTSV(legend_item[1]) + '\n';
	}
	return legend_string;
}

function process_command_data()
{
	var command_string = 'command_id\trecord_id\tcommandtype_id\tcommand_text\tcommand_description\n';
	for (var i = 0, n = combo_table.length; i < n; i++)
	{
		var command_item = combo_table[i];
		command_string += '\\N\t' + record_id + '\t1\t' + cleantextTSV(command_item[0]) + '\t' + cleantextTSV(command_item[1]) + '\n';
	}
	for (var i = 0, n = mouse_table.length; i < n; i++)
	{
		var command_item = mouse_table[i];
		command_string += '\\N\t' + record_id + '\t2\t' + cleantextTSV(command_item[0]) + '\t' + cleantextTSV(command_item[1]) + '\n';
	}
	for (var i = 0, n = joystick_table.length; i < n; i++)
	{
		var command_item = joystick_table[i];
		command_string += '\\N\t' + record_id + '\t3\t' + cleantextTSV(command_item[0]) + '\t' + cleantextTSV(command_item[1]) + '\n';
	}
	for (var i = 0, n = note_table.length; i < n; i++)
	{
		var command_item = note_table[i];
		command_string += '\\N\t' + record_id + '\t4\t' + cleantextTSV(command_item[0]) + '\t' + cleantextTSV(command_item[1]) + '\n';
	}
	for (var i = 0, n = cheat_table.length; i < n; i++)
	{
		var command_item = cheat_table[i];
		command_string += '\\N\t' + record_id + '\t5\t' + cleantextTSV(command_item[0]) + '\t' + cleantextTSV(command_item[1]) + '\n';
	}
	for (var i = 0, n = console_table.length; i < n; i++)
	{
		var command_item = console_table[i];
		command_string += '\\N\t' + record_id + '\t6\t' + cleantextTSV(command_item[0]) + '\t' + cleantextTSV(command_item[1]) + '\n';
	}
	for (var i = 0, n = emote_table.length; i < n; i++)
	{
		var command_item = emote_table[i];
		command_string += '\\N\t' + record_id + '\t7\t' + cleantextTSV(command_item[0]) + '\t' + cleantextTSV(command_item[1]) + '\n';
	}
	return command_string;
}

function process_binding_data()
{
	var binding_string = 'binding_id\trecord_id\tkey_number\tnormal_action\tnormal_group\tshift_action\tshift_group\tctrl_action\tctrl_group\talt_action\talt_group\taltgr_action\taltgr_group\textra_action\textra_group\timage_file\timage_uri\n';
	for (var i = 0, n = binding_table.length; i < n; i++)
	{
		// need to eliminate empty bindings
		var binding_item = binding_table[i];
		if (binding_item)
		{
			var binding_concat = binding_item[ 3] + binding_item[ 4] + binding_item[ 5] + binding_item[ 6] + binding_item[ 7] + binding_item[ 8] + binding_item[ 9] + binding_item[10];
			if (binding_concat != '')
			{
				binding_string +=	'\\N' + '\t' +
							record_id + '\t' +
							(i+1) + '\t' +
							cleantextTSV(binding_item[ 3]) + '\t' +
							 cleannumTSV(binding_item[11]) + '\t' +
							cleantextTSV(binding_item[ 4]) + '\t' +
							 cleannumTSV(binding_item[12]) + '\t' +
							cleantextTSV(binding_item[ 5]) + '\t' +
							 cleannumTSV(binding_item[13]) + '\t' +
							cleantextTSV(binding_item[ 6]) + '\t' +
							 cleannumTSV(binding_item[14]) + '\t' +
							cleantextTSV(binding_item[ 7]) + '\t' +
							 cleannumTSV(binding_item[15]) + '\t' +
							cleantextTSV(binding_item[ 8]) + '\t' +
							 cleannumTSV(binding_item[16]) + '\t' +
							cleantextTSV(binding_item[ 9]) + '\t' +
							cleantextTSV(binding_item[10]) + '\n';
			}
		}
	}
	return binding_string;
}

function fill_spreadsheet()
{
	collect_nonkey_data();
	document.getElementById('legend_tsv').value = process_legend_data();
	document.getElementById('command_tsv').value = process_command_data();
	document.getElementById('binding_tsv').value = process_binding_data();
}

function clear_spreadsheet()
{
	document.getElementById('legend_tsv').value = '';
	document.getElementById('command_tsv').value = '';
	document.getElementById('binding_tsv').value = '';
}

function text_select_and_copy(in_id)
{
	document.getElementById(in_id).select();
	document.execCommand('copy');
}

//https://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
function getParameterByName(name, url)
{
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
