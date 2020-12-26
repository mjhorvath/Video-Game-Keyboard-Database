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

const path_lib1 = './lib/';

var style_id = 0;
var layout_id = 0;
var format_id = 0;
var game_id = 0;
var game_seo = '';
var ten_bool = 1;
var svg_bool = 0;
var commandouter_table = {};
var legend_table = {};
var is_key_dirty = false;
var is_doc_dirty = false;
var is_eml_dirty = false;
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
					elm.matches('#unset_doc_button') ||
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
	document.getElementById('keyout_' + current_id).className += ' keysel';
}

function click_off_chart_key(elm)
{
	if (is_key_dirty == true)
	{
		key_change_warning(elm, 'B');
		return;
	}

	if (current_id !== null)
	{
		document.getElementById('keyout_' + current_id).className = last_class;
	}

	last_id = null;
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
	if (current_id != null)
	{
		const regex = RegExp('keysel');
		const sel_match = regex.test(document.getElementById('keyout_' + current_id).className);
		if (sel_match)
			document.getElementById('keyout_' + current_id).className = last_class + ' keysel';
		else
			document.getElementById('keyout_' + current_id).className = last_class;
	}
}

function key_revert_changes()
{
	push_values_from_array_into_cache();
	push_values_from_cache_into_form();
	document.getElementById('set_key_button').disabled = true;
	document.getElementById('unset_key_button').disabled = true;
	document.getElementById('prev_doc_button').disabled = false;
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
	this_select.className = 'sel' + color_table[target_value][1];
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
	current_values.col_capnor = !binding_table[current_id][12] ? 0 : binding_table[current_id][12];
	current_values.col_capshf = !binding_table[current_id][13] ? 0 : binding_table[current_id][13];
	current_values.col_capctl = !binding_table[current_id][14] ? 0 : binding_table[current_id][14];
	current_values.col_capalt = !binding_table[current_id][15] ? 0 : binding_table[current_id][15];
	current_values.col_capagr = !binding_table[current_id][16] ? 0 : binding_table[current_id][16];
	current_values.col_capxtr = !binding_table[current_id][17] ? 0 : binding_table[current_id][17];
}

function push_values_from_form_into_cache()
{
	if (current_id == null)
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
	const this_image = document.getElementById('capimg_' + current_id);
	this_image.src = current_values.val_imguri;
	if (current_values.val_imguri != '')
		this_image.style.display = 'block';
	else
		this_image.style.display = 'none';

	last_class = 'keyout cap' + color_table[current_values.col_capnor][1];
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
	for (var i in binding_table)
	{
		const this_binding = binding_table[i];
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
		document.getElementById('prev_doc_button').disabled = true;
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
		elm.className = 'sel' + color_table[elm.selectedIndex][1];
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
	for (var i in color_table)
	{
		const new_opt_1 = document.createElement('option');
		const color_class	= color_table[i][1];
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
		document.getElementById('butt_kbd').style.backgroundColor = '#eee';
		document.getElementById('butt_sql').style.backgroundColor = '#ccc';
		document.getElementById('pane_kbd').style.display = 'block';
		document.getElementById('pane_tsv').style.display = 'none';
	}
	else if (side_tab == 1)
	{
		document.getElementById('butt_kbd').style.backgroundColor = '#ccc';
		document.getElementById('butt_sql').style.backgroundColor = '#eee';
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
	document.getElementById( 'email_6').value = process_legend_data();
	document.getElementById( 'email_7').value = process_command_data();
	document.getElementById( 'email_8').value = process_binding_data();
	document.getElementById( 'email_9').value = layout_id;
	document.getElementById('email_10').value = gamesrecord_id;
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

// https://stackoverflow.com/questions/13405129/javascript-create-and-save-file
// Browser support is flaky.
// Function to download data to a file
function download(data, filename, type) {
    var file = new Blob([data], {type: type});
    if (window.navigator.msSaveOrOpenBlob) // IE10+
        window.navigator.msSaveOrOpenBlob(file, filename);
    else { // Others
        var a = document.createElement("a"),
                url = URL.createObjectURL(file);
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(function() {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);  
        }, 0); 
    }
}

function document_export_ajax()
{
	collect_legend_data();
	collect_command_data();
//	collect_binding_data();
//	console.log(JSON.stringify(binding_table));
	const url = 'lib/chart-export-html.php';
	const data =
	{
		gamesrecord_id: gamesrecord_id,
		style_id: style_id,
		layout_id: layout_id,
		format_id: format_id,
		game_id: game_id,
		game_seo: game_seo,
		ten_bool: ten_bool,
		svg_bool: svg_bool,
		commandouter_table: commandouter_table,
		legend_table: legend_table,
		binding_table: binding_table
	}
	$.ajax
	({
		type: 'POST',
		url: url,
		data: JSON.stringify(data),
		contentType: 'application/json; charset=utf-8',
		dataType: 'html',
		success: function(msg)
		{
			do_export(msg);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			console.log(textStatus, errorThrown);
		}
	});
}

function document_export_xhr()
{
	collect_legend_data();
	collect_command_data();
//	collect_binding_data();
	const url = 'lib/chart-export-html.php';
	const data =
	{
		gamesrecord_id: gamesrecord_id,
		style_id: style_id,
		layout_id: layout_id,
		format_id: format_id,
		game_id: game_id,
		game_seo: game_seo,
		ten_bool: ten_bool,
		svg_bool: svg_bool,
		commandouter_table: commandouter_table,
		legend_table: legend_table,
		binding_table: binding_table
	}
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url, true);
	xhr.addEventListener('load', reqListener);
	xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
	xhr.send(JSON.stringify(data));
}

function reqListener()
{
	do_export(this.responseText);
}

function do_export(content)
{
	// save output to file
	download(content, game_seo + '-keyboard-diagram.html', 'text/html; charset=utf-8');
	// print output to new window
//	var myWindow = window.open('', 'myWindow', '');
//	myWindow.document.write(content);
//	myWindow.document.close();
	// print output to browser console
//	console.log(content);
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
	document.getElementById('unset_doc_button').disabled = false;
	document.getElementById('prev_doc_button').disabled = false;
}

function disable_doc_controls()
{
	document.getElementById('set_doc_button').disabled = true;
	document.getElementById('unset_doc_button').disabled = true;
	document.getElementById('prev_doc_button').disabled = true;
}

// non!
function collect_legend_data()
{
	legend_table = {};
	// skip 'non' since there is no box for 'non'
	for (var i in color_table)
	{
		const this_color = color_table[i][1];
		if (this_color != 'non')
		{
			const this_input = document.getElementById('form_cap' + this_color);
			const this_value = this_input.value;
			if (this_value != '')
			{
				legend_table[i] = this_value;
			}
		}
	}
}

// to do: keygroup_id
function collect_command_data()
{
	commandouter_table = {};
	for (var j in commandlabel_table)
	{
		commandouter_table[j] = [];
		const this_abbrv = commandlabel_table[j][1];
		const this_input = commandlabel_table[j][2];
		const this_group = commandlabel_table[j][3];
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
					commandouter_table[j].push([this_value_1, this_value_2, this_value_3]);
				}
			}
			else if (this_input == 1)
			{
				const this_value_1 = null;
				const this_value_2 = this_child.children[0].children[0].value;
				const this_value_3 = this_child.children[2].children[0].value;
				if ((this_value_2 != '') && (this_value_3 != ''))
				{
					commandouter_table[j].push([this_value_1, this_value_2, this_value_3]);
				}
			}
			else
			{
				const this_value_1 = null;
				const this_value_2 = null;
				const this_value_3 = this_child.children[0].children[0].value;
				if (this_value_3 != '')
				{
					commandouter_table[j].push([this_value_1, this_value_2, this_value_3]);
				}
			}
		}
	}
}

// cleaning!
// non!
function process_legend_data()
{
	if (Object.keys(legend_table).length > 0)
	{
		// should really fetch column names from the database instead
		var legend_string = 'insert into legends (legend_id,gamesrecord_id,keygroup_id,legend_description) values\n';
		for (var i in legend_table)
		{
			const legend_item = legend_table[i];
			legend_string += '(NULL,' + gamesrecord_id + ',' + i + ',' + cleantextTSV(legend_item) + '),\n';
		}
		return legend_string.slice(0, -2) + ';\n';
	}
	else
		return '';
}

// cleaning!
// to do: keygroup_id
function process_command_data()
{
	var temp_length = 0;
	for (var j in commandouter_table)
	{
		temp_length += Object.keys(commandouter_table[j]).length;
	}
	if (temp_length > 0)
	{
		// should really fetch column names from the database instead
		var command_string = 'insert into commands (command_id,gamesrecord_id,commandtype_id,command_text,command_description,keygroup_id) values\n';
		for (var j in commandouter_table)
		{
			const command_num = parseInt(j,10) + 1;
			const commandinner_table = commandouter_table[j];
			for (var i in commandinner_table)
			{
				var command_item = commandinner_table[i];
				var command_value_1 = !command_item[0] ? 'NULL' : parseInt(command_item[0],10);
				var command_value_2 = !command_item[1] ? 'NULL' : cleantextTSV(command_item[1]);
				var command_value_3 = !command_item[2] ? 'NULL' : cleantextTSV(command_item[2]);
				command_string += '(NULL,' + gamesrecord_id + ',' + command_num + ',' + command_value_2 + ',' + command_value_3 + ',' + command_value_1 +'),\n';
			}
		}
		return command_string.slice(0, -2) + ';\n';
	}
	else
		return '';
}

// cleaning!
function process_binding_data()
{
	if (Object.keys(binding_table).length > 0)
	{
		// should really fetch column names from the database instead
		var binding_string = 'insert into bindings (binding_id,gamesrecord_id,key_number,normal_action,normal_group,shift_action,shift_group,ctrl_action,ctrl_group,alt_action,alt_group,altgr_action,altgr_group,extra_action,extra_group,image_file,image_uri) values\n';
		for (var i in binding_table)
		{
			const binding_num = parseInt(i,10) + 1;
			const binding_item = binding_table[i];
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
				binding_string +=	'(NULL'					+ ',' +
							gamesrecord_id				+ ',' +
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
							cleantextTSV(binding_item[11])		+ '),\n';
			}
		}
		return binding_string.slice(0, -2) + ';\n';
	}
	else
		return '';
}

function fill_legend(in_id)
{
	collect_legend_data();
	document.getElementById('legend_tsv').value = process_legend_data();
}
function fill_commands()
{
	collect_command_data();
	document.getElementById('command_tsv').value = process_command_data();
}
function fill_bindings()
{
	document.getElementById('binding_tsv').value = process_binding_data();
}
function clear_legend()
{
	document.getElementById('legend_tsv').value = '';
}
function clear_commands()
{
	document.getElementById('command_tsv').value = '';
}
function clear_bindings()
{
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
    const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"), results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function update_select_style(this_select)
{
	const style_id = this_select.selectedIndex;
	this_select.className = 'sel' + color_table[style_id][1];
}

function update_group_column()
{
	flag_doc_dirty();
	update_select_style(this);
}
