// Video Game Keyboard Database
// Copyright (C) 2018  Michael Horvath
// 
// This file is part of Video Game Keyboard Database.
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

const path_lib1 = './lib/';

var is_key_dirty = false;
var is_doc_dirty = false;
var is_eml_dirty = false;
var is_cap_dirty = false;
var last_id = null;
var last_class = null;
var current_key_id = null;
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

function addOne(in_string)
{
	if (in_string == '')
		return '';
	else
		return (parseInt(in_string, 10) + 1).toString();
}
function subOne(in_string)
{
	if (in_string == '')
		return '';
	else
		return (parseInt(in_string, 10) - 1).toString();
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
					elm.matches('#set_doc_button')   ||
					elm.matches('#reset_doc_button') ||
					elm.matches('.email_input')      ||
					elm.matches('.email_textarea')   ||
					elm.matches('#email_recaptcha')  ||
					(true === false)
				)
			)
			{
				key_change_warning(elm, 'E');
				return;
			}
			else if
			(
				elm.matches('#pane_lft')   ||
				elm.matches('#table_inp')  ||
				elm.matches('#button_inp') ||
				elm.matches('#tbar_lft')   ||
				elm.matches('#tbar_rgt')   ||
				elm.matches('.side_butt')  ||
				elm.matches('.tabs_butt')  ||
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
				(is_key_dirty == true) &&
				(does_ancestor_have_id(elm, 'pane_rgt') == true) &&
				(
					(elm.tagName.toUpperCase() == 'INPUT')    ||
					(elm.tagName.toUpperCase() == 'BUTTON')   ||
					(elm.tagName.toUpperCase() == 'TEXTAREA') ||
					(elm.tagName.toUpperCase() == 'SELECT')   ||
					(elm.tagName.toUpperCase() == 'OPTION')   ||
					(true === false)
				)
			)
			{
				key_change_warning(elm, 'A');
				return;
			}
			else if (elm.matches('.grpsub'))
			{
				remove_group_table_row(elm);
				flag_doc_dirty();
				return;
			}
			else if (elm.matches('.grpadd'))
			{
				add_group_table_row(elm);
				flag_doc_dirty();
				return;
			}
			else if (elm.matches('.inpsub'))
			{
				remove_input_table_row(elm);
				flag_doc_dirty();
				return;
			}
			else if (elm.matches('.inpadd'))
			{
				add_input_table_row(elm);
				flag_doc_dirty();
				return;
			}
			else if (elm.matches('.txtsub'))
			{
				remove_texts_table_row(elm);
				flag_doc_dirty();
				return;
			}
			else if (elm.matches('.txtadd'))
			{
				add_texts_table_row(elm);
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

	last_id = current_key_id;
	current_key_id = elm.id.slice(7);

	enable_input_elements();
	push_values_from_array_into_cache();
	push_values_from_cache_into_form();

	if (last_id != null)
	{
		document.getElementById('keyout_' + last_id).className = last_class;
	}

	const current_key_object = document.getElementById('keyout_' + current_key_id);
	last_class = set_keysel(current_key_object.className, 0);
	current_key_object.className = set_keysel(current_key_object.className, 1);
}

function click_off_chart_key(elm)
{
	if (is_key_dirty == true)
	{
		key_change_warning(elm, 'B');
		return;
	}

	if (current_key_id !== null)
	{
		document.getElementById('keyout_' + current_key_id).className = last_class;
	}

	last_id = null;
	last_class = null;
	current_key_id = null;
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
	if (are_captions_in_groups() == false)
	{
		if (key_legend_warning_single('A') == false)
			return;
	}
	push_values_from_form_into_cache();
	push_values_from_cache_into_array();
	document.getElementById('set_key_button').disabled = true;
	document.getElementById('unset_key_button').disabled = true;
	flag_key_clean();
	flag_doc_dirty();
	if (current_key_id != null)
	{
		const current_key_object = document.getElementById('keyout_' + current_key_id);
		const regex = RegExp('keysel');
		const sel_match = regex.test(current_key_object.className);
		if (sel_match)
		{
			current_key_object.className = last_class + ' keysel';
		}
		else
		{
			current_key_object.className = last_class;
		}
	}
}

function key_revert_changes()
{
	push_values_from_array_into_cache();
	push_values_from_cache_into_form();
	document.getElementById('set_key_button').disabled = true;
	document.getElementById('unset_key_button').disabled = true;
	document.getElementById('export_doc_button').disabled = false;
	flag_key_clean();
}

function is_embedded_image_okay()
{
	const img_filename = document.getElementById('inp_imgfil').value;
	const img_datauri = document.getElementById('inp_imguri').value;
	if
	(
		((img_filename == '') && (img_datauri != '')) ||
		((img_filename != '') && (img_datauri == '')) ||
		(true === false)
	)
	{
		return false;
	}
	return true;
}

function set_form_color(this_select, target_value)
{
	this_select.selectedIndex = target_value;
	this_select.className = 'sel' + binding_data.color_table[target_value][1];
}

function get_form_color(this_select)
{
	return this_select.selectedIndex;
}

function push_values_from_array_into_cache()
{
	if (current_key_id == null)
		return null;

	current_values.val_keynum = current_key_id;
	const this_key = binding_data.binding_table[current_key_id];
	current_values.val_lownor =  this_key[ 0];
	current_values.val_uppnor =  this_key[ 1];
	current_values.val_lowagr =  this_key[ 2];
	current_values.val_uppagr =  this_key[ 3];
	current_values.val_capnor =  this_key[ 4];
	current_values.val_capshf =  this_key[ 5];
	current_values.val_capctl =  this_key[ 6];
	current_values.val_capalt =  this_key[ 7];
	current_values.val_capagr =  this_key[ 8];
	current_values.val_capxtr =  this_key[ 9];
	current_values.val_imgfil =  this_key[10];
	current_values.val_imguri =  this_key[11];
	current_values.col_capnor = !this_key[12] ? 0 : this_key[12];
	current_values.col_capshf = !this_key[13] ? 0 : this_key[13];
	current_values.col_capctl = !this_key[14] ? 0 : this_key[14];
	current_values.col_capalt = !this_key[15] ? 0 : this_key[15];
	current_values.col_capagr = !this_key[16] ? 0 : this_key[16];
	current_values.col_capxtr = !this_key[17] ? 0 : this_key[17];
}

function push_values_from_form_into_cache()
{
	if (current_key_id == null)
		return null;

	current_values.val_keynum = subOne(document.getElementById('inp_keynum').value);
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

// cleaning!
function push_values_from_cache_into_array()
{
	if (current_key_id == null)
		return null;

	binding_data.binding_table[current_key_id][ 0] = current_values.val_lownor;
	binding_data.binding_table[current_key_id][ 1] = current_values.val_uppnor;
	binding_data.binding_table[current_key_id][ 2] = current_values.val_lowagr;
	binding_data.binding_table[current_key_id][ 3] = current_values.val_uppagr;
	binding_data.binding_table[current_key_id][ 4] = current_values.val_capnor;
	binding_data.binding_table[current_key_id][ 5] = current_values.val_capshf;
	binding_data.binding_table[current_key_id][ 6] = current_values.val_capctl;
	binding_data.binding_table[current_key_id][ 7] = current_values.val_capalt;
	binding_data.binding_table[current_key_id][ 8] = current_values.val_capagr;
	binding_data.binding_table[current_key_id][ 9] = current_values.val_capxtr;
	binding_data.binding_table[current_key_id][10] = current_values.val_imgfil;
	binding_data.binding_table[current_key_id][11] = current_values.val_imguri;
	binding_data.binding_table[current_key_id][12] = current_values.col_capnor;
	binding_data.binding_table[current_key_id][13] = current_values.col_capshf;
	binding_data.binding_table[current_key_id][14] = current_values.col_capctl;
	binding_data.binding_table[current_key_id][15] = current_values.col_capalt;
	binding_data.binding_table[current_key_id][16] = current_values.col_capagr;
	binding_data.binding_table[current_key_id][17] = current_values.col_capxtr;

	document.getElementById('lownor_' + current_key_id).innerHTML = cleantextHTML(current_values.val_lownor);
	document.getElementById('uppnor_' + current_key_id).innerHTML = cleantextHTML(current_values.val_uppnor);
	document.getElementById('lowagr_' + current_key_id).innerHTML = cleantextHTML(current_values.val_lowagr);
	document.getElementById('uppagr_' + current_key_id).innerHTML = cleantextHTML(current_values.val_uppagr);
	document.getElementById('capnor_' + current_key_id).innerHTML = cleantextHTML(current_values.val_capnor);
	document.getElementById('capshf_' + current_key_id).innerHTML = cleantextHTML(current_values.val_capshf);
	document.getElementById('capctl_' + current_key_id).innerHTML = cleantextHTML(current_values.val_capctl);
	document.getElementById('capalt_' + current_key_id).innerHTML = cleantextHTML(current_values.val_capalt);
	document.getElementById('capagr_' + current_key_id).innerHTML = cleantextHTML(current_values.val_capagr);
	document.getElementById('capxtr_' + current_key_id).innerHTML = cleantextHTML(current_values.val_capxtr);

	// Need to make certain that both a filename *and* a data URI are present!!
	const this_image = document.getElementById('capimg_' + current_key_id);
	this_image.src = current_values.val_imguri;
	if (current_values.val_imguri != '')
		this_image.style.display = 'block';
	else
		this_image.style.display = 'none';

	last_class = last_class.replace(/cap\w\w\w/, 'cap' + binding_data.color_table[current_values.col_capnor][1]);
}

function push_values_from_cache_into_form()
{
	document.getElementById('inp_keynum').value = addOne(current_values.val_keynum);
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
	if (current_key_id == null)
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

function are_captions_in_groups()
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

// I have not tested yet whether this produces false positives. No real harm done if it does, though.
function check_all_caption_groups()
{
	for (var i in binding_data.binding_table)
	{
		const this_binding = binding_data.binding_table[i];
		if
		(
			((this_binding[ 4] != '') && (this_binding[12] == 0)) ||
			((this_binding[ 5] != '') && (this_binding[13] == 0)) ||
			((this_binding[ 6] != '') && (this_binding[14] == 0)) ||
			((this_binding[ 7] != '') && (this_binding[15] == 0)) ||
			((this_binding[ 8] != '') && (this_binding[16] == 0)) ||
			((this_binding[ 9] != '') && (this_binding[17] == 0)) ||
			(true === false)
		)
		{
			return false;
		}
	}
	return true;
}

// Need to rename this function since its purpose has changed slightly.
function toggle_set_and_revert_buttons(event)
{
	if (have_input_key_values_changed() == true)
	{
		document.getElementById('set_key_button').disabled = false;
		document.getElementById('unset_key_button').disabled = false;
		document.getElementById('export_doc_button').disabled = true;
		flag_key_dirty();
	}
	else
	{
		document.getElementById('set_key_button').disabled = true;
		document.getElementById('unset_key_button').disabled = true;
		flag_key_clean();
	}

	const elm = event.target;
	if (elm.tagName.toUpperCase() == 'SELECT')
		elm.className = 'sel' + binding_data.color_table[elm.selectedIndex][1];
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

function remove_group_table_row(button_node)
{
	const parent_node = button_node.parentNode.parentNode;
	const ancest_node = parent_node.parentNode;
	ancest_node.removeChild(parent_node);
}

function add_group_table_row(button_node)
{
	const parent_node = button_node.parentNode.parentNode;
	const ancest_node = parent_node.parentNode;
	const new_node = create_group_table_row();
	ancest_node.removeChild(parent_node);
	ancest_node.appendChild(new_node);
	ancest_node.appendChild(parent_node);
}

function create_group_table_row()
{
	const new_row = document.createElement('div');
	new_row.className = 'notrow';
	const new_cll_1 = document.createElement('div');
	new_cll_1.className = 'notcll grpone';
	const new_sel_1 = document.createElement('select');
	new_sel_1.className = 'selnon';
	new_sel_1.setAttribute('size',1);
	new_sel_1.setAttribute('autocomplete','off');
	new_sel_1.onchange = update_group_column;
	for (var i in binding_data.color_table)
	{
		const new_opt_1 = document.createElement('option');
		const color_class	= binding_data.color_table[i][1];
		new_opt_1.className = 'opt' + color_class;
		const new_txt_1 = document.createTextNode(color_class);
		new_opt_1.appendChild(new_txt_1);
		new_sel_1.appendChild(new_opt_1);
	}
	new_cll_1.appendChild(new_sel_1);
	new_row.appendChild(new_cll_1);
	const new_cll_2 = document.createElement('div');
	new_cll_2.className = 'notcll grptwo';
	const new_inp_2 = document.createElement('input');
	new_inp_2.setAttribute('type','text');
	new_inp_2.setAttribute('maxlength','100');
//	new_inp_2.setAttribute('placeholder','blah');
	new_inp_2.setAttribute('autocomplete','off');
	new_inp_2.onchange = flag_doc_dirty;
	new_cll_2.appendChild(new_inp_2);
	new_row.appendChild(new_cll_2);
	const new_cll_3 = document.createElement('div');
	new_cll_3.className = 'notcll grpthr';
	const new_txt_3 = document.createTextNode('=');
	new_cll_3.appendChild(new_txt_3);
	new_row.appendChild(new_cll_3);
	const new_cll_4 = document.createElement('div');
	new_cll_4.className = 'notcll grpfor';
	const new_inp_4 = document.createElement('input');
	new_inp_4.setAttribute('type','text');
	new_inp_4.setAttribute('maxlength','100');
//	new_inp_4.setAttribute('placeholder','blah');
	new_inp_4.setAttribute('autocomplete','off');
	new_inp_4.onchange = flag_doc_dirty;
	new_cll_4.appendChild(new_inp_4);
	new_row.appendChild(new_cll_4);
	const new_cll_5 = document.createElement('div');
	new_cll_5.className = 'notcll grpfiv';
	const new_but_5 = document.createElement('button');
	new_but_5.className = 'grpsub';
	new_but_5.setAttribute('type','button');
	const new_txt_5 = document.createTextNode('-');
	new_but_5.appendChild(new_txt_5);
	new_cll_5.appendChild(new_but_5);
	new_row.appendChild(new_cll_5);
	return new_row;
}

function remove_input_table_row(button_node)
{
	const parent_node = button_node.parentNode.parentNode;
	const ancest_node = parent_node.parentNode;
	ancest_node.removeChild(parent_node);
}

function add_input_table_row(button_node)
{
	const parent_node = button_node.parentNode.parentNode;
	const ancest_node = parent_node.parentNode;
	const new_node = create_input_table_row();
	ancest_node.removeChild(parent_node);
	ancest_node.appendChild(new_node);
	ancest_node.appendChild(parent_node);
}

function create_input_table_row()
{
	const new_row = document.createElement('div');
	new_row.className = 'notrow';
	const new_cll_1 = document.createElement('div');
	new_cll_1.className = 'notcll inpone';
	const new_inp_1 = document.createElement('input');
	new_inp_1.setAttribute('type','text');
	new_inp_1.setAttribute('maxlength','100');
//	new_inp_1.setAttribute('placeholder','blah');
	new_inp_1.setAttribute('autocomplete','off');
	new_inp_1.onchange = flag_doc_dirty;
	new_cll_1.appendChild(new_inp_1);
	new_row.appendChild(new_cll_1);
	const new_cll_2 = document.createElement('div');
	new_cll_2.className = 'notcll inptwo';
	const new_txt_2 = document.createTextNode('=');
	new_cll_2.appendChild(new_txt_2);
	new_row.appendChild(new_cll_2);
	const new_cll_3 = document.createElement('div');
	new_cll_3.className = 'notcll inpthr';
	const new_inp_3 = document.createElement('input');
	new_inp_3.setAttribute('type','text');
	new_inp_3.setAttribute('maxlength','100');
//	new_inp_3.setAttribute('placeholder','blah');
	new_inp_3.setAttribute('autocomplete','off');
	new_inp_3.onchange = flag_doc_dirty;
	new_cll_3.appendChild(new_inp_3);
	new_row.appendChild(new_cll_3);
	const new_cll_4 = document.createElement('div');
	new_cll_4.className = 'notcll inpfor';
	const new_but_4 = document.createElement('button');
	new_but_4.className = 'inpsub';
	new_but_4.setAttribute('type','button');
	const new_txt_4 = document.createTextNode('-');
	new_but_4.appendChild(new_txt_4);
	new_cll_4.appendChild(new_but_4);
	new_row.appendChild(new_cll_4);
	return new_row;
}

function remove_texts_table_row(button_node)
{
	const parent_node = button_node.parentNode.parentNode;
	const ancest_node = parent_node.parentNode;
	ancest_node.removeChild(parent_node);
}

function add_texts_table_row(button_node)
{
	const parent_node = button_node.parentNode.parentNode;
	const ancest_node = parent_node.parentNode;
	const new_node = create_texts_table_row();
	ancest_node.removeChild(parent_node);
	ancest_node.appendChild(new_node);
	ancest_node.appendChild(parent_node);
}

function create_texts_table_row()
{
	const new_row = document.createElement('div');
	new_row.className = 'notrow';
	const new_cll_1 = document.createElement('div');
	new_cll_1.className = 'notcll txtone';
	const new_inp_1 = document.createElement('textarea');
//	new_inp_1.setAttribute('placeholder','blah');
	new_inp_1.setAttribute('autocomplete','off');
	new_inp_1.setAttribute('maxlength','1024');
	new_inp_1.onchange = flag_doc_dirty;
	new_cll_1.appendChild(new_inp_1);
	new_row.appendChild(new_cll_1);
	const new_cll_2 = document.createElement('div');
	new_cll_2.className = 'notcll txttwo';
	const new_but_2 = document.createElement('button');
	new_but_2.className = 'txtsub';
	new_but_2.setAttribute('type','button');
	const new_txt_2 = document.createTextNode('-');
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
		document.getElementById('butt_inp').style.backgroundColor = '#ffffff';
		document.getElementById('butt_hlp').style.backgroundColor = '#c0c0c0';
		document.getElementById('pane_inp').style.display = 'block';
		document.getElementById('pane_hlp').style.display = 'none';
	}
	else if (side_tab == 1)
	{
		document.getElementById('butt_inp').style.backgroundColor = '#c0c0c0';
		document.getElementById('butt_hlp').style.backgroundColor = '#ffffff';
		document.getElementById('pane_inp').style.display = 'none';
		document.getElementById('pane_hlp').style.display = 'block';
	}
}

function switch_right_pane(side_tab)
{
	if (side_tab == 0)
	{
		document.getElementById('butt_kbd').style.backgroundColor = '#ffffff';
		document.getElementById('butt_sql').style.backgroundColor = '#c0c0c0';
		document.getElementById('pane_kbd').style.display = 'block';
		document.getElementById('pane_tsv').style.display = 'none';
	}
	else if (side_tab == 1)
	{
		document.getElementById('butt_kbd').style.backgroundColor = '#c0c0c0';
		document.getElementById('butt_sql').style.backgroundColor = '#ffffff';
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
		element.addEventListener(eventName, handler, false);
	else if (element.attachEvent)
		element.attachEvent('on' + eventName, handler);
	else
		element['on' + eventName] = handler;
}

function removeListener(element, eventName, handler) {
	if (element.addEventListener)
		element.removeEventListener(eventName, handler, false);
	else if (element.detachEvent)
		element.detachEvent('on' + eventName, handler);
	else
		element['on' + eventName] = null;
}

function key_change_warning(elm, letter)
{
	elm.blur();
	document.body.focus();
	alert('A key has been altered. Please save or revert any changes to this key before proceeding. (' + letter + ')');
}

function key_legend_warning_single(letter)
{
	// is this message too annoying?
	return confirm('A caption for this key has been set, but its color has not also been set. Please make sure every key caption has a corresponding color, even if the color is not displayed in the chart. Do you wish to proceed anyway? (' + letter + ')');
	// if it is too annoying, do this instead.
//	return 1;
}

function key_legend_warning_multiple(letter)
{
	return confirm('One or more key captions have been set, but their colors have not also been set. Please make sure every key caption has a corresponding color, even if the color is not displayed in the chart. Do you wish to proceed anyway? (' + letter + ')');
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
	return confirm('Pressing this button will post the keyboard bindings data to the site admin. You will not be able to return later and continue where you left off. Do you wish to proceed? (' + letter + ')');
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
		// this message actually gets spawned earlier anyway
//		var elm = document.getElementById('set_doc_button');
//		key_change_warning(elm, 'D');
		return;
	}
	if (check_all_caption_groups() == false)
	{
		if (key_legend_warning_multiple('D') == false)
			return;
	}
	if (document_save_warning('D') == false)
		return;
	collect_legend_data();
	collect_command_data();
	document.getElementById( 'email_4').value = document.getElementById('game_tit').value;
	document.getElementById( 'email_5').value = document.getElementById('game_url').value;
	document.getElementById( 'email_6').value = process_legend_sql();
	document.getElementById( 'email_7').value = process_command_sql();
	document.getElementById( 'email_8').value = process_binding_sql();
	document.getElementById( 'email_9').value = binding_data.layout_id;
	document.getElementById('email_10').value = binding_data.gamesrecord_id;
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
	const name	= $('#email_1');
	const email	= $('#email_2');
	const message	= $('#email_3');
	const titletxt	= $('#email_4');
	const titleurl	= $('#email_5');
	const legend	= $('#email_6');
	const command	= $('#email_7');
	const binding	= $('#email_8');
	const layout	= $('#email_9');
	const record	= $('#email_10');
	const newsub	= $('#email_11');
	const captcha	= grecaptcha.getResponse();		// THIS WILL TELL THE FORM IF THE USER IS CAPTCHA VERIFIED.

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
			url: path_lib1 + "scripts-recaptcha.php",
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
				record:		record.val(),
				newsub:		newsub.val(),
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
//				$('#email_10').val('');
//				$('#email_11').val('');
				if (is_doc_dirty == true)
					flag_doc_clean();
				console.log('RECAPTCHA: mail was sent');
				hide_loading_image();
			}
			else
			{
				alert('There were problems with reCAPTCHA. Please try again.');
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
	const okay = document_change_warning('A');
	if (okay == true)
		location.reload();
}

function document_change_style(game_id, layout_id, game_seo)
{
	const okay = document_change_warning('B');
	if (okay == true)
		reloadThisPage(game_id, layout_id, game_seo);
}

// there is an analogous function written in PHP in "./lib/scripts-common.php"
// need to keep the two functions synced
function seo_url(input)
{
	input = input.toLowerCase();					// convert to lowercase
	input = input.replace(/'|"/gm, '');				// remove single and double quotes
	input = input.replace(/[^a-zA-Z0-9]+/gm, '-');			// replace everything non-alphanumeric with dashes
	input = input.replace(/\-+/gm, '-');				// replace multiple dashes with one dash
	input = input.replace(/^\-+|\-+$/gm, '');			// trim dashes from the beginning and end of the string if any
	return input;
}

function convert_title_to_seo(event)
{
	const elm = event.target;
	const left_value = elm.value;
	const right_value = seo_url(left_value);
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

function flag_doc_dirty()
{
	is_doc_dirty = true;
	toggle_doc_controls();
}

function flag_doc_clean()
{
	is_doc_dirty = false;
	toggle_doc_controls();
}

function flag_cap_dirty()
{
	is_cap_dirty = true;
	toggle_doc_controls();
}

function flag_cap_clean()
{
	is_cap_dirty = false;
	toggle_doc_controls();
}

function flag_eml_dirty()
{
	const name	= $('#email_1');
	const email	= $('#email_2');
	const message	= $('#email_3');
	if
	(
		name.val() != ''	||
		email.val() != ''	||
		message.val() != ''	||
		(true === false)
	)
	{
		is_eml_dirty = true;
	}
	else
	{
		is_eml_dirty = false;
	}
	toggle_doc_controls();
}

function check_eml_valid()
{
	const name	= $('#email_1');
	const email	= $('#email_2');
	const message	= $('#email_3');
	if
	(
		name.get(0).validity.valid	&&
		email.get(0).validity.valid	&&
		message.get(0).validity.valid	&&
		(true === true)
	)
	{
		return true;
	}
	return false;
}

function toggle_doc_controls()
{
	if
	(
		(is_doc_dirty == true) ||
		(is_cap_dirty == true) ||
		(is_eml_dirty == true) ||
		(true === false)
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
	if (check_eml_valid() == true)
		document.getElementById('set_doc_button').disabled = false;
	else
		document.getElementById('set_doc_button').disabled = true;
	document.getElementById('reset_doc_button').disabled = false;
	document.getElementById('export_doc_button').disabled = false;
}

function disable_doc_controls()
{
	document.getElementById('set_doc_button').disabled = true;
	document.getElementById('reset_doc_button').disabled = true;
	document.getElementById('export_doc_button').disabled = true;
}

// non!
function collect_legend_data()
{
	binding_data.legend_table = {};
	// skip 'non' since there is no box for 'non'
	for (var i in binding_data.color_table)
	{
		const this_color = binding_data.color_table[i][1];
		if (this_color != 'non')
		{
			const this_input = document.getElementById('form_cap' + this_color);
			const this_value = this_input.value;
			if (this_value != '')
			{
				binding_data.legend_table[i] = this_value;
			}
		}
	}
}

// to do: keygroup_id
function collect_command_data()
{
	binding_data.commandouter_table = {};
	for (var j in binding_data.commandlabel_table)
	{
		binding_data.commandouter_table[j] = [];
		const this_abbrv = binding_data.commandlabel_table[j][2];
		const this_input = binding_data.commandlabel_table[j][3];
		const this_group = binding_data.commandlabel_table[j][4];
		const this_parent = document.getElementById('table_' + this_abbrv);
		const this_children = this_parent.children;
		// ignore the last child since it only contains the button
		for (var i = 0, n = this_children.length - 1; i < n; i++)
		{
			const this_child = this_children[i];
			if (this_group == 1)
			{
				const this_value_1 = this_child.children[0].children[0].selectedIndex;
				const this_value_2 = this_child.children[1].children[0].value;
				const this_value_3 = this_child.children[3].children[0].value;
				if ((this_value_2 != '') && (this_value_3 != ''))
				{
					binding_data.commandouter_table[j].push([this_value_1, this_value_2, this_value_3]);
				}
			}
			else if (this_input == 1)
			{
				const this_value_1 = null;
				const this_value_2 = this_child.children[0].children[0].value;
				const this_value_3 = this_child.children[2].children[0].value;
				if ((this_value_2 != '') && (this_value_3 != ''))
				{
					binding_data.commandouter_table[j].push([this_value_1, this_value_2, this_value_3]);
				}
			}
			else
			{
				const this_value_1 = null;
				const this_value_2 = null;
				const this_value_3 = this_child.children[0].children[0].value;
				if (this_value_3 != '')
				{
					binding_data.commandouter_table[j].push([this_value_1, this_value_2, this_value_3]);
				}
			}
		}
	}
}

function apply_all_json()
{
	const code_text = document.getElementById('code_all_json').value;
	binding_data = JSON.parse(code_text);
	// keys
	for (var i in binding_data.binding_table)
	{
		const key_outer = document.getElementById('keyout_' + i);
		if (key_outer)
		{
			const this_key = binding_data.binding_table[i];
			const val_lownor =  this_key[ 0];
			const val_uppnor =  this_key[ 1];
			const val_lowagr =  this_key[ 2];
			const val_uppagr =  this_key[ 3];
			const val_capnor =  this_key[ 4];
			const val_capshf =  this_key[ 5];
			const val_capctl =  this_key[ 6];
			const val_capalt =  this_key[ 7];
			const val_capagr =  this_key[ 8];
			const val_capxtr =  this_key[ 9];
			const val_imgfil =  this_key[10];
			const val_imguri =  this_key[11];
			const col_capnor = !Number(this_key[12]) ? 0 : Number(this_key[12]);
			const col_capshf = !Number(this_key[13]) ? 0 : Number(this_key[13]);
			const col_capctl = !Number(this_key[14]) ? 0 : Number(this_key[14]);
			const col_capalt = !Number(this_key[15]) ? 0 : Number(this_key[15]);
			const col_capagr = !Number(this_key[16]) ? 0 : Number(this_key[16]);
			const col_capxtr = !Number(this_key[17]) ? 0 : Number(this_key[17]);

			document.getElementById('lownor_' + i).innerHTML = cleantextHTML(val_lownor);
			document.getElementById('uppnor_' + i).innerHTML = cleantextHTML(val_uppnor);
			document.getElementById('lowagr_' + i).innerHTML = cleantextHTML(val_lowagr);
			document.getElementById('uppagr_' + i).innerHTML = cleantextHTML(val_uppagr);
			document.getElementById('capnor_' + i).innerHTML = cleantextHTML(val_capnor);
			document.getElementById('capshf_' + i).innerHTML = cleantextHTML(val_capshf);
			document.getElementById('capctl_' + i).innerHTML = cleantextHTML(val_capctl);
			document.getElementById('capalt_' + i).innerHTML = cleantextHTML(val_capalt);
			document.getElementById('capagr_' + i).innerHTML = cleantextHTML(val_capagr);
			document.getElementById('capxtr_' + i).innerHTML = cleantextHTML(val_capxtr);

			// Need to make certain that both a filename *and* a data URI are present!!
			const this_image = document.getElementById('capimg_' + i);
			this_image.src = val_imguri;
			if (val_imguri != '')
				this_image.style.display = 'block';
			else
				this_image.style.display = 'none';

			const key_class = 'keyout cap' + binding_data.color_table[col_capnor][1];
			key_outer.className = key_class;
		}
	}
	// commands (basically duplicates what appears in "output-submit-html.php")
	for (var i in binding_data.commandlabel_table)
	{
		const commandlabel_value = binding_data.commandlabel_table[i];
		const commandlabel_label = commandlabel_value[1];
		const commandlabel_abbrv = commandlabel_value[2];
		const commandlabel_input = commandlabel_value[3];			// does the command at least have two parts?
		const commandlabel_group = commandlabel_value[4];			// does the command have a keygroup?
		const commanddiv_object = document.getElementById('table_' + commandlabel_abbrv);
		var commanddiv_string = '';
		while (commanddiv_object.firstChild)
		{
			commanddiv_object.removeChild(commanddiv_object.firstChild);
		}
		const commandinner_table = binding_data.commandouter_table[i];
		for (var j in commandinner_table)
		{
			const commandinner_value = commandinner_table[j];
			const commandinner_input = commandinner_value[1];
			const commandinner_combo = commandinner_value[2];
			const commandinner_group = commandinner_value[3];
			// does the command have a keygroup?
			if (commandlabel_group == 1)
			{
				// non!
				var commandoption_string = '';
				var select_class = 'non';
				for (var k in binding_data.color_table)
				{
					const binding_color_value = binding_data.color_table[k];
					const color_id		= binding_color_value[0];
					const color_class	= binding_color_value[1];
					const color_sort	= binding_color_value[2];	// not used right now
					if (color_id == commandinner_group)
					{
						select_class = color_class;
					}
				}
				if (select_class == 'non')
				{
					commandoption_string += '<option class="optnon" selected="selected">non</option>';
				}
				else
				{
					commandoption_string += '<option class="optnon">non</option>';
				}
				// non!
				for (var k in binding_data.color_table)
				{
					const binding_color_value = binding_data.color_table[k];
					const color_id		= binding_color_value[0];	// not used right now
					const color_class	= binding_color_value[1];
					const color_sort	= binding_color_value[2];	// not used right now
					if (select_class == color_class)
					{
						commandoption_string += '<option class="opt' + color_class + '" selected="selected">' + color_class + '</option>';
					}
					else
					{
						commandoption_string += '<option class="opt' + color_class + '">' + color_class + '</option>';
					}
				}
				commanddiv_string +=
'							<div class="notrow">\n'+
'								<div class="notcll grpone"><select class="sel' + select_class + '" size="1" autocomplete="off" onchange="flag_doc_dirty();update_select_style(this);">' + commandoption_string + '</select></div>\n'+
'								<div class="notcll grptwo"><input type="text" maxlength="100" autocomplete="off" onchange="flag_doc_dirty();" value="' + commandinner_input + '"/></div>\n'+
'								<div class="notcll grpthr">=</div>\n'+
'								<div class="notcll grpfor"><input type="text" maxlength="100" autocomplete="off" onchange="flag_doc_dirty();" value="' + commandinner_combo + '"/></div>\n'+
'								<div class="notcll grpfiv"><button class="grpsub" type="button">-</button></div>\n'+
'							</div>\n';
			}
			// does the command at least have two parts?
			else if (commandlabel_input == 1)
			{
				commanddiv_string +=
'							<div class="notrow">\n'+
'								<div class="notcll inpone"><input type="text" maxlength="100" autocomplete="off" onchange="flag_doc_dirty();" value="' + commandinner_input + '"/></div>\n'+
'								<div class="notcll inptwo">=</div>\n'+
'								<div class="notcll inpthr"><input type="text" maxlength="100" autocomplete="off" onchange="flag_doc_dirty();" value="' + commandinner_combo + '"/></div>\n'+
'								<div class="notcll inpfor"><button class="inpsub" type="button">-</button></div>\n'+
'							</div>\n';
			}
			// "additional notes" are a special case requiring a textarea instead of input
			else
			{
				commanddiv_string +=
'							<div class="notrow">\n'+
'								<div class="notcll txtone"><textarea maxlength="1024" autocomplete="off" onchange="flag_doc_dirty();">' + commandinner_combo + '</textarea></div>\n'+
'								<div class="notcll txttwo"><button class="txtsub" type="button">-</button></div>\n'+
'							</div>\n';
			}
		}
		// does the command have a keygroup?
		if (commandlabel_group == 1)
		{
			commanddiv_string +=
'							<div class="notrow">\n'+
'								<div class="notcll grpone"></div>\n'+
'								<div class="notcll grptwo"></div>\n'+
'								<div class="notcll grpthr"></div>\n'+
'								<div class="notcll grpfor"></div>\n'+
'								<div class="notcll grpfiv"><button class="grpadd" type="button">+</button></div>\n'+
'							</div>\n';
		}
		// does the command at least have two parts?
		else if (commandlabel_input == 1)
		{
			commanddiv_string +=
'							<div class="notrow">\n'+
'								<div class="notcll inpone"></div>\n'+
'								<div class="notcll inptwo"></div>\n'+
'								<div class="notcll inpthr"></div>\n'+
'								<div class="notcll inpfor"><button class="inpadd" type="button">+</button></div>\n'+
'							</div>\n';
		}
		// "additional notes" are a special case requiring a textarea instead of input
		else
		{
			commanddiv_string +=
'							<div class="notrow">\n'+
'								<div class="notcll txtone"></div>\n'+
'								<div class="notcll txttwo"><button class="txtadd" type="button">+</button></div>\n'+
'							</div>\n';
		}
		commanddiv_object.innerHTML = commanddiv_string;
	}
	// non!
	// legend
	for (var i in binding_data.color_table)
	{
		if (Number(i) > 0)
		{
			const color_value = binding_data.color_table[i];
			const legend_value = binding_data.legend_table[i];
			const leg_color = color_value[1];
			const leg_input = document.getElementById('form_cap' + leg_color);
			leg_input.value = legend_value ? legend_value : '';
		}
	}
}

function process_all_json()
{
	const pretty_check = document.getElementById('code_pretty');
	if (pretty_check.checked == true)
	{
		return JSON.stringify(binding_data, null, '\t');
	}
	else
	{
		return JSON.stringify(binding_data);
	}
}

// cleaning!
// non!
function process_legend_sql()
{
	if (Object.keys(binding_data.legend_table).length > 0)
	{
		// should really fetch column names from the database instead
		var legend_string = '';
		for (var i in binding_data.legend_table)
		{
			const legend_item = binding_data.legend_table[i];
			legend_string += 'CALL add_legend(' + binding_data.gamesrecord_id + ',' + i + ',' + cleantextTSV(legend_item) + ');\n';
		}
		return legend_string;
	}
	else
		return '';
}

// cleaning!
// to do: keygroup_id
function process_command_sql()
{
	var temp_length = 0;
	for (var j in binding_data.commandouter_table)
	{
		temp_length += Object.keys(binding_data.commandouter_table[j]).length;
	}
	if (temp_length > 0)
	{
		// should really fetch column names from the database instead
		var command_string = '';
		for (var j in binding_data.commandouter_table)
		{
			const command_num = parseInt(j,10) + 1;
			const commandinner_table = binding_data.commandouter_table[j];
			for (var i in commandinner_table)
			{
				const command_item = commandinner_table[i];
				const command_value_1 = !command_item[0] ? 'NULL' : parseInt(command_item[0],10);
				const command_value_2 = !command_item[1] ? 'NULL' : cleantextTSV(command_item[1]);
				const command_value_3 = !command_item[2] ? 'NULL' : cleantextTSV(command_item[2]);
				command_string += 'CALL add_command(' + binding_data.gamesrecord_id + ',' + command_num + ',' + command_value_2 + ',' + command_value_3 + ',' + command_value_1 +');\n';
			}
		}
		return command_string;
	}
	else
		return '';
}

// cleaning!
function process_binding_sql()
{
	if (Object.keys(binding_data.binding_table).length > 0)
	{
		// should really fetch column names from the database instead
		var binding_string = '';
		for (var i in binding_data.binding_table)
		{
			const binding_num = parseInt(i,10) + 1;
			const binding_item = binding_data.binding_table[i];
			const binding_concat =	binding_item[ 4] +
						binding_item[ 5] +
						binding_item[ 6] +
						binding_item[ 7] +
						binding_item[ 8] +
						binding_item[ 9] +
						binding_item[10] +
						binding_item[11];
			// need to eliminate empty bindings
			if (binding_concat != '')
			{
				binding_string +=	'CALL add_binding('			+
							binding_data.gamesrecord_id		+ ',' +
							binding_num				+ ',' +
							cleantextTSV(binding_item[ 4])		+ ',' +
							cleannumberTSV(binding_item[12])	+ ',' +
							cleantextTSV(binding_item[ 5])		+ ',' +
							cleannumberTSV(binding_item[13])	+ ',' +
							cleantextTSV(binding_item[ 6])		+ ',' +
							cleannumberTSV(binding_item[14])	+ ',' +
							cleantextTSV(binding_item[ 7])		+ ',' +
							cleannumberTSV(binding_item[15])	+ ',' +
							cleantextTSV(binding_item[ 8])		+ ',' +
							cleannumberTSV(binding_item[16])	+ ',' +
							cleantextTSV(binding_item[ 9])		+ ',' +
							cleannumberTSV(binding_item[17])	+ ',' +
							cleantextTSV(binding_item[10])		+ ',' +
							cleantextTSV(binding_item[11])		+ ');\n';
			}
		}
		return binding_string;
	}
	else
		return '';
}

function fill_all_json()
{
	collect_legend_data();
	collect_command_data();
	document.getElementById('code_all_json').value = process_all_json();
}
function fill_legend_sql()
{
	collect_legend_data();
	document.getElementById('code_legend_sql').value = process_legend_sql();
}
function fill_commands_sql()
{
	collect_command_data();
	document.getElementById('code_command_sql').value = process_command_sql();
}
function fill_bindings_sql()
{
	document.getElementById('code_binding_sql').value = process_binding_sql();
}
function clear_all_json()
{
	document.getElementById('code_all_json').value = '';
}
function clear_legend_sql()
{
	document.getElementById('code_legend_sql').value = '';
}
function clear_commands_sql()
{
	document.getElementById('code_command_sql').value = '';
}
function clear_bindings_sql()
{
	document.getElementById('code_binding_sql').value = '';
}
function text_select_and_copy(in_id)
{
	document.getElementById(in_id).select();
	document.execCommand('copy');
}

//https://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
function getParameterByName(name, url)
{
    if (!url) {url = window.location.href}
    name = name.replace(/[\[\]]/g, "\\$&");
    const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"), results = regex.exec(url);
    if (!results) {return null}
    if (!results[2]) {return ''}
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function update_select_style(this_select)
{
	const style_id = this_select.selectedIndex;
	this_select.className = 'sel' + binding_data.color_table[style_id][1];
}

function update_group_column()
{
	flag_doc_dirty();
	update_select_style(this);
}

function set_keysel(in_class, set_bool)
{
	var out_string = in_class.replace('keysel', '');
	if (set_bool == 1)
	{
		out_string += ' keysel';
	}
	return out_string.replace(/\s+/gi, ' ');
}
