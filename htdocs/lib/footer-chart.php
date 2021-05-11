<?php
	// should maybe link to "style_footer.css" from in here too instead of from the main pages
	switch ($format_id)
	{
		// HTML/SVG
		case 0:
			$display_styles = "table-row";
			$display_tenkey = "table-row";
			$display_format = "table-row";
			$display_orient = "table-row";
			$display_update = "table-row";
			$display_keycap = "table-row";
			$display_export = "table-row";
		break;
		// SVG ONLY
		case 1:
			// this format does not display a footer
		break;
		// MEDIA WIKI
		case 2:
			$display_styles = "none";
			$display_tenkey = "none";
			$display_format = "table-row";
			$display_orient = "none";
			$display_update = "table-row";
			$display_keycap = "table-row";
			$display_export = "none";
		break;
		// EDITOR
		case 3:
			$display_styles = "table-row";
			$display_tenkey = "table-row";
			$display_format = "table-row";
			$display_orient = "table-row";
			$display_keycap = "table-row";
			$display_update = "table-row";
			$display_export = "none";
		break;
	}
	echo
'<div class="boxdiv">
	<p>
		<a target="_blank" rel="license" href="https://www.gnu.org/licenses/lgpl-3.0.en.html"><img alt="GNU LGPLv3 icon" style="border-width:0;" src="' . $path_lib1 . 'license-lgpl-88x31.png"/></a>
		<a target="_blank" rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="CC BY-SA 3.0 icon" style="border-width:0;" src="' . $path_lib1 . 'license-cc-by-sa-88x31.png"/></a>
	</p>
	<p>&quot;Video Game Keyboard Diagrams&quot; software was created by <a target="_blank" href="https://isometricland.net">Michael Horvath</a> and is licensed under <a target="_blank" rel="license" href="https://www.gnu.org/licenses/lgpl-3.0.en.html">GNU LGPLv3</a> or later. Content is licensed under <a target="_blank" href="https://creativecommons.org/licenses/by-sa/3.0/">CC BY-SA 3.0</a> or later. You can find this project on <a target="_blank" href="https://github.com/mjhorvath/vgkd">GitHub</a>.</p>
	<p>Return to <a href="' . $path_vgkd . 'keyboard.php">Video Game Keyboard Diagrams</a>. View the <a href="' . $path_vgkd . 'keyboard-list.php">master table</a>, <a href="' . $path_vgkd . 'keyboard-credits.php">credits</a> or <a href="' . $path_vgkd . 'keyboard-log.php">change log</a>. Here is a <a href="' . $path_vgkd . 'keyboard-links.php">list of links</a>. Having trouble printing? Take a look at <a href="' . $path_vgkd . 'keyboard.php#print_tips">these printing tips</a>.</p>
';

	// display toggles
	// need to replace table with non-table markup
	echo
'	<form name="VisualStyleSwitch" autocomplete="off">
		<table id="footswitch">
			<tr style="display:' . $display_styles . ';">
				<th>Theme:</th>
				<td>
					<select class="stylechange" id="stylsel" name="styl">
';

	// do a tiny bit of validity checking so that I get my preferred default style if a style ID is missing or out of bounds
	$selected_style_id = $style_name ? $style_id : $default_style_id;
	foreach ($style_table as $i => $style_value)
	{
		$stylegroup_box = $style_table[$i];
		$stylegroup_nam = $stylegroup_table[$i][1];
		echo
"						<optgroup label=\"" . cleantextHTML($stylegroup_nam) . "\">\n";
		foreach ($stylegroup_box as $j => $stylegroup_val)
		{
			$style_idx = $stylegroup_val[0];
			$style_nam = $stylegroup_val[1];
			if ($style_idx == $default_style_id)
			{
				$style_nam .= " &#10022;";
			}
			if ($style_idx == $selected_style_id)
			{
				echo
"							<option value=\"" . $style_idx . "\" selected>" . cleantextHTML($style_nam) . "</option>\n";
			}
			else
			{
				echo
"							<option value=\"" . $style_idx . "\">" . cleantextHTML($style_nam) . "</option>\n";
			}
		}
		echo
"						</optgroup>\n";
	}
	echo
"					</select>
				</td>
			</tr>\n";
	echo
'			<tr style="display:' . $display_format . ';">
				<th>Format:</th>
				<td>
					<input class="stylechange" type="radio" name="form" id="formrad0" value="0"' . ($format_id == 0 ? ' checked' : '') . '/><label for="formrad0">HTML/SVG &#10022;</label>
					<input class="stylechange" type="radio" name="form" id="formrad1" value="1"' . ($format_id == 1 ? ' checked' : '') . '/><label for="formrad1">SVG only</label>
					<input class="stylechange" type="radio" name="form" id="formrad2" value="2"' . ($format_id == 2 ? ' checked' : '') . '/><label for="formrad2">MediaWiki</label>
					<input class="stylechange" type="radio" name="form" id="formrad3" value="3"' . ($format_id == 3 ? ' checked' : '') . '/><label for="formrad3">Editor</label>
					<input class="stylechange" type="radio" name="form" id="formrad4" value="4"' . ($format_id == 4 ? ' checked' : '') . ' disabled/><label for="formrad4"><s>PDF</s></label>
				</td>
			</tr>
			<tr style="display:' . $display_tenkey . ';">
				<th>Numpad:</th>
				<td>
					<input class="stylechange" type="radio" name="tkey" id="tkeyrad1" value="1"' . ($tenk_flag == 1 ? ' checked' : '') . '/><label for="tkeyrad1">Show &#10022;</label>
					<input class="stylechange" type="radio" name="tkey" id="tkeyrad0" value="0"' . ($tenk_flag == 0 ? ' checked' : '') . '/><label for="tkeyrad0">Hide</label>
				</td>
			</tr>
			<tr style="display:' . $display_orient . ';">
				<th>Orientation:</th>
				<td>
					<input class="stylechange" type="radio" name="vert" id="vertrad0" value="0"' . ($vert_flag == 0 ? ' checked' : '') . '/><label for="vertrad0">Horizontal &#10022;</label>
					<input class="stylechange" type="radio" name="vert" id="vertrad1" value="1"' . ($vert_flag == 1 ? ' checked' : '') . '/><label for="vertrad1">Vertical</label>
				</td>
			</tr>
			<tr style="display:' . $display_keycap . ';">
				<th>Key caps:</th>
				<td>
					<input class="stylechange" type="radio" name="kcap" id="kcaprad0" value="0"' . ($kcap_flag == 0 ? ' checked' : '') . '/><label for="kcaprad0">Strong &#10022;</label>
					<input class="stylechange" type="radio" name="kcap" id="kcaprad1" value="1"' . ($kcap_flag == 1 ? ' checked' : '') . '/><label for="kcaprad1">Dim</label>
					<input class="stylechange" type="radio" name="kcap" id="kcaprad2" value="2"' . ($kcap_flag == 2 ? ' checked' : '') . '/><label for="kcaprad2">Blurred</label>
					<input class="stylechange" type="radio" name="kcap" id="kcaprad3" value="3"' . ($kcap_flag == 3 ? ' checked' : '') . '/><label for="kcaprad3">Hidden</label>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input class="stylechange" type="button" style="display:' . $display_update . ';" value="Update" onclick="reload_this_page();"/>
					<input class="stylechange" type="button" style="display:' . $display_export . ';" value="Export HTML" onclick="document_export_main_ajax();"/>
				</td>
			</tr>
		</table>
	</form>
	<p>Items marked with a star (&#10022;) are the &quot;default&quot; or most common options.</p>
	<p>
';

	// having someone's name repeated here three times bothers me. is there a way to have someone's name listed here only once?
	// something like the following would be very easy to do if there were only one contributer
	// "Binding scheme, keyboard layout and visual theme created by: Michael Horvath."
	// something like this would be a little harder
	// "Binding scheme created by Michael Horvath and John Smith. Keyboard layout and visual theme created by: Michael Horvath."
	// it may only be worthwhile to pursue this if the number of contributers remains very small
	// duplicated code here could be converted into a function
	echo "Binding scheme created by: ";
	$count_authors = 0;
	$total_authors = count($gamesrecord_authors);
	foreach ($gamesrecord_authors as $i => $gamesrecord_value)
	{
		echo $gamesrecord_value;
		if ($count_authors < $total_authors - 1)
			echo ", ";
		else
			echo ".\n";
		$count_authors += 1;
	}
	echo "Keyboard layout created by: ";
	$count_authors = 0;
	$total_authors = count($layout_authors);
	foreach ($layout_authors as $i => $layout_value)
	{
		echo $layout_value;
		if ($count_authors < $total_authors - 1)
			echo ", ";
		else
			echo ".\n";
		$count_authors += 1;
	}
	echo "Visual theme created by: ";
	$count_authors = 0;
	$total_authors = count($stylesrecord_authors);
	foreach ($stylesrecord_authors as $i => $stylesrecords_value)
	{
		echo $stylesrecords_value;
		if ($count_authors < $total_authors - 1)
			echo ", ";
		else
			echo ".\n";
		$count_authors += 1;
	}
	echo
'	</p>
	<p>' . getFileTime() . ' GRID:' . $gamesrecord_id . '.</p>
</div>
';
?>
