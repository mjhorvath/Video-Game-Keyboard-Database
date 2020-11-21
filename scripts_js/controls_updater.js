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
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

var binding_source
var layout_source
var sLay
window.addEventListener('DOMContentLoaded', init)

function init()
{
	load_binding()
	load_layout()
	document.getElementById('button01_bin').addEventListener('click', update_binding)
	document.getElementById('button01_lay').addEventListener('click', update_layout)
//	button02 Export to TSV
//	button03 Export to SQL
	document.getElementById('button04_bin').addEventListener('click', clear_binding)
	document.getElementById('button04_lay').addEventListener('click', clear_layout)
	document.getElementById('button05_bin').addEventListener('click', copy_binding)
	document.getElementById('button05_lay').addEventListener('click', copy_layout)
	document.getElementById('button06_bin').addEventListener('click', load_binding)
	document.getElementById('button06_lay').addEventListener('click', load_layout)
	document.getElementById('button02_bin').addEventListener('click', print_binding_tsv)
	document.getElementById('button02_lay').addEventListener('click', print_layout_tsv)
	document.getElementById('button03_bin').addEventListener('click', print_binding_sql)
	document.getElementById('button03_lay').addEventListener('click', print_layout_sql)
}
function load_binding()
{
	var layout_file = document.getElementById('input_layout_bin').value
	var binding_file = document.getElementById('input_binding_bin').value
	sLay = layout_file.toUpperCase().replace('LAY_','').replace('.JS','')
	binding_source = document.createElement('script')
	binding_source.setAttribute('src', binding_file)
	binding_source.setAttribute('type', 'text/javascript')
	document.head.appendChild(binding_source)
	binding_source.addEventListener('load', start_binding)
}
function load_layout()
{
	var layout_file = document.getElementById('input_layout_lay').value
	layout_source = document.createElement('script')
	layout_source.setAttribute('src', layout_file)
	layout_source.setAttribute('type', 'text/javascript')
	document.head.appendChild(layout_source)
	layout_source.addEventListener('load', start_layout)
}
function check_binding()
{
	if (!Gam_Table[sLay] || !Gam_Table[sLay]['key'])
	{
		document.getElementById('outputarea_bin').innerHTML = 'Old format detected. Please update format.'
		return false
	}
	else
	{
		return true
	}
}
function check_layout()
{
	if (!Lay_Table['bla'])
	{
		document.getElementById('outputarea_lay').innerHTML = 'Old format detected. Please update format.';
		return false
	}
	else
	{
		return true
	}
}
function start_binding()
{
	if (check_binding() == false)
		return
	print_binding_js(Gam_Table[sLay]['key'])
}
function start_layout()
{
	if (check_layout() == false)
		return
	print_layout_js(Lay_Table['key'])
}
function update_binding()
{
	// old format: class, norm_txt, shift_txt, ctrl_txt, alt_txt, altgr_txt, extra_txt, image file, image URI
	// new_format: norm_txt, norm_grp, shift_txt, shift_grp, ctrl_txt, ctrl_grp, alt_txt, alt_grp, altgr_txt, altgr_grp, extra_txt, extra_grp, image file, image uri
	if (check_binding() == true)
	{
		alert('Binding already updated. No need to update again.')
		return
	}
	var old_binding = Gam_Table['key'][sLay]
	var new_binding = []
	// skip the first line since it is used for the sample key only
	for (var i = 1, n = old_binding.length; i < n; i++)
	{
		var old_row = old_binding[i]
		var new_row = []
		if (old_row)
		{
			new_row[0] = old_row[1] ? old_row[1] : ''	// norm_txt, class
			new_row[1] = old_row[0] ? old_row[0] : ''	// norm_grp, norm_txt
			new_row[2] = old_row[2] ? old_row[2] : ''	// shift_txt, shift_txt
			new_row[3] = ''								// shift_grp
			new_row[4] = old_row[3] ? old_row[3] : ''	// ctrl_txt, ctrl_txt
			new_row[5] = ''								// ctrl_grp
			new_row[6] = old_row[4] ? old_row[4] : ''	// alt_txt, alt_txt
			new_row[7] = ''								// alt_grp
			new_row[8] = old_row[5] ? old_row[5] : ''	// altgr_txt, altgr_txt
			new_row[9] = ''								// altgr_grp
			new_row[10] = old_row[6] ? old_row[6] : ''	// extra_txt, extra_txt
			new_row[11] = ''							// extra_grp
			new_row[12] = old_row[7] ? old_row[7] : ''	// image file, image file
			new_row[13] = old_row[8] ? old_row[8] : ''	// image URI, image URI
			new_binding[i-1] = new_row
		}
		else
		{
			new_binding[i-1] = null
		}
	}
	print_binding_js(new_binding)
}
function update_layout()
{
	// old format: left, top, width, height, norm_upp, norm_low, altgr_low
	// new format: left, top, width, height, norm_upp, norm_low, altgr_upp, altgr_low
	if (check_layout() == true)
	{
		alert('Layout already updated. No need to update again.')
		return
	}
	var old_layout = Lay_Table['key']
	var new_layout = []
	// skip the first line since it is used for the sample key only
	for (var i = 1, n = old_layout.length; i < n; i++)
	{
		var old_row = old_layout[i]
		var new_row = []
		if (old_row)
		{
			new_row[0] = old_row[0]
			new_row[1] = old_row[1]
			new_row[2] = old_row[2]
			new_row[3] = old_row[3]
			new_row[4] = old_row[4] ? old_row[4] : ''
			new_row[5] = old_row[5] ? old_row[5] : ''
			new_row[6] = ''			// skipped a column here on purpose
			new_row[7] = old_row[6] ? old_row[6] : ''
			new_layout[i-1] = new_row
		}
	}
	print_layout_js(new_layout)
}
function print_binding_tsv()
{
	if (check_binding() == false)
		return
	var out_string = ''
	var thisTable = Gam_Table[sLay]['key']
	for (var i = 0, n = thisTable.length; i < n; i++)
	{
		// inp: norm_txt, norm_grp, shift_txt, shift_grp, ctrl_txt, ctrl_grp, alt_txt, alt_grp, altgr_txt, altgr_grp, extra_txt, extra_grp, image file, image uri
		// out: binding_id, record_id, key_number, normal_action, normal_group, shift_action, shift_group, ctrl_action, ctrl_group, alt_action, alt_group, altgr_action, altgr_group, extra_action, extra_group, image_file, image_uri
		var thisRow = thisTable[i]
		if (thisRow)
		{
			out_string	+= '\\N\t\\N\t' + (i+1) + '\t'
						+ ValTextTSV(thisRow[0])  + '\t' + ValNumPlusTSV(thisRow[1])  + '\t'
						+ ValTextTSV(thisRow[2])  + '\t' + ValNumPlusTSV(thisRow[3])  + '\t'
						+ ValTextTSV(thisRow[4])  + '\t' + ValNumPlusTSV(thisRow[5])  + '\t'
						+ ValTextTSV(thisRow[6])  + '\t' + ValNumPlusTSV(thisRow[7])  + '\t'
						+ ValTextTSV(thisRow[8])  + '\t' + ValNumPlusTSV(thisRow[9])  + '\t'
						+ ValTextTSV(thisRow[10]) + '\t' + ValNumPlusTSV(thisRow[11]) + '\t'
						+ thisRow[12] + '\t' + thisRow[13] + '\n'
		}
	}
	document.getElementById('outputarea_bin').innerHTML = out_string
}
function print_layout_tsv()
{
	if (check_layout() == false)
		return
	var out_string = ''
	var thisTable = Lay_Table['key']
	for (var i = 0, n = thisTable.length; i < n; i++)
	{
		// inp: left, top, width, height, norm_upp, norm_low, altgr_upp, altgr_low
		// out: position_id, layout_id, key_number, position_left, position_top, position_width, position_height, symbol_norm_cap, symbol_norm_low, symbol_altgr_cap, symbol_altgr_low, lowcap_optional, numpad
		var thisRow = thisTable[i]
		if (thisRow)
		{
			out_string	+= '\\N\t\\N\t' + (i+1) + '\t'
						+ ValNumTSV(thisRow[0])  + '\t' + ValNumTSV(thisRow[1])  + '\t'
						+ ValNumTSV(thisRow[2])  + '\t' + ValNumTSV(thisRow[3])  + '\t'
						+ ValTextTSV(thisRow[4])  + '\t' + ValTextTSV(thisRow[5])  + '\t'
						+ ValTextTSV(thisRow[6])  + '\t' + ValTextTSV(thisRow[7])  + '\t\n'
		}
	}
	document.getElementById('outputarea_lay').innerHTML = out_string
}
function print_binding_sql()
{
	if (check_binding() == false)
		return
	var out_string = 'insert into bindings ('
	for (var i = 0, n = GamColumns.length; i < n; i++)
	{
		out_string += GamColumns[i]
		if (i < (n-1))
			out_string += ','
	}
	out_string += ') values\n'
	var thisTable = Gam_Table[sLay]['key']
	for (var i = 0, n = thisTable.length; i < n; i++)
	{
		var thisRow = thisTable[i]
		if (thisRow)
		{
			out_string	+= '(NULL,NULL,' + (i+1) + ','
						+ ValTextSQL(thisRow[0])  + ',' + ValNumPlusSQL(thisRow[1])  + ','
						+ ValTextSQL(thisRow[2])  + ',' + ValNumPlusSQL(thisRow[3])  + ','
						+ ValTextSQL(thisRow[4])  + ',' + ValNumPlusSQL(thisRow[5])  + ','
						+ ValTextSQL(thisRow[6])  + ',' + ValNumPlusSQL(thisRow[7])  + ','
						+ ValTextSQL(thisRow[8])  + ',' + ValNumPlusSQL(thisRow[9])  + ','
						+ ValTextSQL(thisRow[10]) + ',' + ValNumPlusSQL(thisRow[11]) + ','
						+ ValTextSQL(thisRow[12]) + ',' + ValTextSQL(thisRow[13]) + '),\n'		// need to replace the very last comma with a semicolon
		}
	}
	document.getElementById('outputarea_bin').innerHTML = out_string
}
function print_layout_sql()
{
	if (check_layout() == false)
		return
	var out_string = 'insert into positions ('
	for (var i = 0, n = LayColumns.length; i < n; i++)
	{
		out_string += LayColumns[i]
		if (i < (n-1))
			out_string += ','
	}
	out_string += ') values\n'
	var thisTable = Lay_Table['key']
	for (var i = 0, n = thisTable.length; i < n; i++)
	{
		var thisRow = thisTable[i]
		if (thisRow)
		{
			out_string	+= '(NULL,NULL,' + (i+1) + ','
						+ ValNumSQL(thisRow[0])  + ',' + ValNumSQL(thisRow[1])  + ','
						+ ValNumSQL(thisRow[2])  + ',' + ValNumSQL(thisRow[3])  + ','
						+ ValTextSQL(thisRow[4]) + ',' + ValTextSQL(thisRow[5]) + ','
						+ ValTextSQL(thisRow[6]) + ',' + ValTextSQL(thisRow[7]) + '),\n'
		}
	}
	document.getElementById('outputarea_lay').innerHTML = out_string
}
function ValTextTSV(inVal)
{
	if (!inVal)
		inVal = '\\N'
	else
		inVal = inVal.replaceAll('\n','\\n').replaceAll('\t','\\t')
	return inVal
}
function ValNumTSV(inVal)
{
	if ((!inVal) && (inVal !== 0))
		inVal = '\\N'
	return inVal
}
function ValNumPlusTSV(inVal)
{
	if ((!inVal) && (inVal !== 0))
		inVal = '\\N'
	else
		inVal += 1
	return inVal
}
function ValTextSQL(inVal)
{
	if (!inVal)
		inVal = 'NULL'
	else
	{
		inVal = inVal.replaceAll('\\','\\\\').replaceAll('\'','\\\'').replaceAll('\n','\\n').replaceAll('\t','\\t')
		inVal = '\'' + inVal + '\''
	}
	return inVal
}
function ValNumSQL(inVal)
{
	if ((!inVal) && (inVal !== 0))
		inVal = 'NULL'
	return inVal
}
function ValNumPlusSQL(inVal)
{
	if ((!inVal) && (inVal !== 0))
		inVal = 'NULL'
	else
		inVal += 1
	return inVal
}
function print_binding_js(in_binding)
{
	// convert finished table into a string
	var out_string = JSON.stringify(in_binding)
	// convert color name strings to color array integers
	for (var i in GroupColors)
		out_string = out_string.replaceAll('"' + i + '"', GroupColors[i])
	// put each item on its own line
	out_string = out_string.replace(/\]\,/g, '],\n').replace(/null,/g, 'null,\n')
	// break double brackets onto new lines
	out_string = out_string.replace(/\[\[/g,'[\n[').replace(/\]\]/g,']\n]')
	// indent each line
	out_string = out_string.replace(/(^\[\")/gm,'\t$1').replace(/^null/gm,'\tnull')
	// replace double quotes with single quotes
	out_string = out_string.replace(/"/g,'\'').replace(/\'\\\'\'/g,'\'\"\'').replace(/\'\'\'/g,'\'\\\'\'')
	// put table definition in front
	out_string = 'Gam_Table[sLay][\'key\'] =\n' + out_string
	// add a table description
	out_string = '// norm_txt, norm_grp, shift_txt, shift_grp, ctrl_txt, ctrl_grp, alt_txt, alt_grp, altgr_txt, altgr_grp, extra_txt, extra_grp, image file, image uri\n' + out_string
	document.getElementById('outputarea_bin').innerHTML = out_string
}
function print_layout_js(in_layout)
{
	// convert finished table into a string
	var out_string = JSON.stringify(in_layout)
	// put each item on its own line
	out_string = out_string.replace(/\]\,/g, '],\n').replace(/null\,/g, 'null,\n')
	// break double brackets onto new lines
	out_string = out_string.replace(/\[\[/g,'[\n[').replace(/\]\]/g,']\n]')
	// indent each line
	out_string = out_string.replace(/(^\[\w)/gm,'\t$1').replace(/^null/gm,'\tnull')
	// replace double quotes with single quotes
	out_string = out_string.replace(/"/g,'\'').replace(/\'\\\'\'/g,'\'\"\'').replace(/\'\'\'/g,'\'\\\'\'')
	// put table definition in front
	out_string = 'Lay_Table[\'key\'] =\n' + out_string
	// add a table description
	out_string = '// left, top, width, height, norm_upp, norm_low, altgr_upp, altgr_low\n' + out_string
	document.getElementById('outputarea_lay').innerHTML = out_string
}
function clear_binding()
{
	document.getElementById('outputarea_bin').innerHTML = ''
}
function clear_layout()
{
	document.getElementById('outputarea_lay').innerHTML = ''
}
function copy_binding()
{
	document.getElementById('outputarea_bin').select();
	document.execCommand('copy');
}
function copy_layout()
{
	document.getElementById('outputarea_lay').select();
	document.execCommand('copy');
}
