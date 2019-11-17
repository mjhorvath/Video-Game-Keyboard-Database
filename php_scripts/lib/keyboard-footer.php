<?php
	switch ($format_id)
	{
		case 0:
			$display_style = "table-row";
			$display_tenkey = "table-row";
		break;
		case 1:
			// this format does not display a footer
		break;
		case 2:
			$display_style = "none";
			$display_tenkey = "none";
		break;
		case 3:
			$display_style = "table-row";
			$display_tenkey = "none";
		break;
	}
	echo
"			<script src=\"" . $path_lib . "keyboard-footer.js\"></script>
			<div class=\"boxdiv\">
				<p><a target=\"_blank\" rel=\"license\" href=\"https://www.gnu.org/licenses/lgpl-3.0.en.html\"><img alt=\"GNU LGPLv3 icon\" src=\"" . $path_root . "images/license_lgpl_88x31.png\" /></a><a rel=\"license\" href=\"http://creativecommons.org/licenses/by-sa/3.0/\"><img alt=\"CC BY-SA 3.0 icon\" style=\"border-width:0;\" src=\"" . $path_root . "images/license_cc-by-sa_88x31.png\" /></a></p>
				<p>&quot;Video Game Keyboard Diagrams&quot; software was created by Michael Horvath and is licensed under <a target=\"_blank\" rel=\"license\" href=\"https://www.gnu.org/licenses/lgpl-3.0.en.html\">GNU LGPLv3</a> or later license. Content is licensed under <a target=\"_blank\" href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC BY-SA 3.0</a> or later license. You can find this project on <a target=\"_blank\" href=\"https://github.com/mjhorvath/vgkd\">GitHub</a>.</p>
				<p>Return to <a href=\"./keyboard.php\">Video Game Keyboard Diagrams</a>. View the <a href=\"./keyboard-list.php\">master list</a>. Having trouble printing? Take a look at <a href=\"./keyboard.php#print_tips\">these printing tips</a>.</p>\n";
	// display toggles
	// need to replace table with non-table markup
	echo
"				<form name=\"VisualStyleSwitch\">
					<table id=\"footswitch\">
						<tr style=\"display:" . $display_style . ";\">
							<th>Theme:</th>
							<td>
								<select class=\"stylechange\" id=\"stylesel\" name=\"style\">\n";
	// do a tiny bit of validity checking so that I get my preferred default style if a style ID is missing or out of bounds
	$selected_style_id = $style_name ? $style_id : $default_style_id;
	for ($i = 0; $i < count($style_table); $i++)
	{
		$stylegroup_box = $style_table[$i];
		$stylegroup_nam = cleantextHTML($stylegroup_table[$i][1]);
		echo
"									<optgroup label=\"" . $stylegroup_nam . "\">\n";
		for ($j = 0; $j < count($stylegroup_box); $j++)
		{
			$style_row = $stylegroup_box[$j];
			$style_idx = $style_row[0];
			$style_nam = cleantextHTML($style_row[1]);
			if ($style_idx == $default_style_id)
			{
				$style_nam .= " &#10022;";
			}
			if ($style_idx == $selected_style_id)
			{
				echo
"										<option value=\"" . $style_idx . "\" selected>" . $style_nam . "</option>\n";
			}
			else
			{
				echo
"										<option value=\"" . $style_idx . "\">" . $style_nam . "</option>\n";
			}
		}
		echo
"									</optgroup>\n";
	}
	echo
"								</select>
							</td>
						</tr>\n";
	echo
"						<tr>
							<th>Format:</th>
							<td>
								<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"formrad0\" value=\"0\" " . ($format_id == 0 ? "checked" : "") . ">&nbsp;<label for=\"formrad0\">HTML/SVG</label>
								<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"formrad1\" value=\"1\" " . ($format_id == 1 ? "checked" : "") . ">&nbsp;<label for=\"formrad1\">SVG only</label>
								<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"formrad2\" value=\"2\" " . ($format_id == 2 ? "checked" : "") . ">&nbsp;<label for=\"formrad2\">MediaWiki</label>
								<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"formrad3\" value=\"3\" " . ($format_id == 3 ? "checked" : "") . ">&nbsp;<label for=\"formrad3\">Editor</label>
								<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"formrad4\" value=\"4\" disabled>&nbsp;<label for=\"rad4\"><s>PDF</s></label>
							</td>
						</tr>
						<tr style=\"display:" . $display_tenkey . ";\">
							<th>Numpad:</th>
							<td>
								<input class=\"stylechange\" type=\"radio\" name=\"tkey\" id=\"tkeyrad1\" value=\"1\" " . ($ten_bool == 1 ? "checked" : "") . ">&nbsp;<label for=\"tkeyrad1\">Show</label>
								<input class=\"stylechange\" type=\"radio\" name=\"tkey\" id=\"tkeyrad0\" value=\"0\" " . ($ten_bool == 0 ? "checked" : "") . ">&nbsp;<label for=\"tkeyrad0\">Hide</label>
							</td>
						</tr>
						<tr>
							<th>Submit:</th>
							<td>
								<input class=\"stylechange\" type=\"button\" value=\"Update\" onclick=\"reloadThisPage('" . $game_id . "', '" . $layout_id . "', '" . $game_seo . "');\" />
							</td>
						</tr>
					</table>
				</form>
				<p>Items marked with a star (&#10022;) are the &quot;default&quot; or most common options.</p>
				<p>\n";
	// having someone's name repeated here three times bothers me
	// is there a way to have someone's name listed here only once?
	echo "Binding scheme created by: ";
	$count_authors = count($gamesrecord_authors);
	for ($i = 0; $i < $count_authors; $i++)
	{
		echo $gamesrecord_authors[$i];
		if ($i < $count_authors - 1)
			echo ", ";
		else
			echo ".\n";
	}
	echo "Keyboard layout created by: ";
	$count_authors = count($layout_authors);
	for ($i = 0; $i < $count_authors; $i++)
	{
		echo $layout_authors[$i];
		if ($i < $count_authors - 1)
			echo ", ";
		else
			echo ".\n";
	}
	echo "Visual theme created by: ";
	$count_authors = count($stylesrecord_authors);
	for ($i = 0; $i < $count_authors; $i++)
	{
		echo $stylesrecord_authors[$i];
		if ($i < $count_authors - 1)
			echo ", ";
		else
			echo ".\n";
	}
	echo
"				</p>
				<p>" . getFileTime($path_file) . " GRID: " . $gamesrecord_id . "</p>
			</div>\n";
?>
