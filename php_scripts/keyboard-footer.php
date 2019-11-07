<?php
	echo
"			<div class=\"boxdiv\">
				<p><a target=\"_blank\" rel=\"license\" href=\"https://www.gnu.org/licenses/lgpl-3.0.en.html\"><img alt=\"GNU LGPLv3 icon\" src=\"" . $path_root . "images/license_lgpl_88x31.png\" /></a><a rel=\"license\" href=\"http://creativecommons.org/licenses/by-sa/3.0/\"><img alt=\"CC BY-SA 3.0 icon\" style=\"border-width:0;\" src=\"" . $path_root . "images/license_cc-by-sa_88x31.png\" /></a></p>
				<p>&quot;Video Game Keyboard Diagrams&quot; software was created by Michael Horvath and is licensed under <a target=\"_blank\" rel=\"license\" href=\"https://www.gnu.org/licenses/lgpl-3.0.en.html\">GNU LGPLv3</a> or later license. Content is licensed under <a target=\"_blank\" href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC BY-SA 3.0</a> or later license. You can find this project on <a target=\"_blank\" href=\"https://github.com/mjhorvath/vgkd\">GitHub</a>.</p>
				<p>\n";
	if (($gamesrecord_author) && ($gamesrecord_author != "Michael Horvath"))
	{
		echo
"Binding scheme created by: " . $gamesrecord_author . ". ";
	}
	if (($layout_author) && ($layout_author != "Michael Horvath"))
	{
		echo
"Keyboard layout created by: " . $layout_author . ". ";
	}
	if (($stylesrecord_author) && ($stylesrecord_author != "Michael Horvath"))
	{
		echo
"Style design created by: " . $stylesrecord_author . ". ";
	}
	echo
"				</p>
				<p>Return to <a href=\"keyboard.php\">Video Game Keyboard Diagrams</a>. View the <a href=\"keyboard-list.php\">master list</a>. Having trouble printing? Take a look at <a href=\"keyboard.php#print_tips\">these printing tips</a>.</p>
";
	// display toggles
	// need to replace table with non-table markup
	echo
"				<form name=\"VisualStyleSwitch\">
					<table id=\"footswitch\">
						<tr>
							<th>Theme:</th>
							<td>
								<select class=\"stylechange\" id=\"stylesel\" name=\"style\">\n";
	for ($i = 0; $i < count($style_table); $i++)
	{
		$stylegroup_box = $style_table[$i];
		$stylegroup_nam = cleantextHTML($style_group_table[$i][1]);
		echo
"									<optgroup label=\"" . $stylegroup_nam . "\">\n";
		for ($j = 0; $j < count($stylegroup_box); $j++)
		{
			$style_row = $stylegroup_box[$j];
			if ($style_row[0])		// is this condition necessary?
			{
				$style_idx = $style_row[0];
				$style_nam = cleantextHTML($style_row[1]);
				if ($style_id == $style_idx)
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
		}
		echo
"									</optgroup>\n";
	}
	echo
	"							</select>
							</td>
						</tr>
						<tr>
							<th>Format:</th>
							<td>
								<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"formrad0\" value=\"0\" " . ($format_id == 0 ? "checked" : "") . ">&nbsp;<label for=\"formrad0\">HTML/SVG</label>
								<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"formrad2\" value=\"2\" " . ($format_id == 2 ? "checked" : "") . ">&nbsp;<label for=\"formrad2\">MediaWiki</label>
								<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"formrad3\" value=\"3\" " . ($format_id == 3 ? "checked" : "") . ">&nbsp;<label for=\"formrad3\">Editor</label>
								<input class=\"stylechange\" type=\"radio\" name=\"tech\" id=\"formrad4\" value=\"4\" disabled>&nbsp;<label for=\"rad4\"><s>PDF</s></label>
							</td>
						</tr>
						<tr>
							<th>Tenkey:</th>
							<td>
								<input class=\"stylechange\" type=\"radio\" name=\"tkey\" id=\"tkeyrad0\" value=\"0\" disabled " . ($tenkey_id == 0 ? "checked" : "") . ">&nbsp;<label for=\"tkeyrad0\">Show</label>
								<input class=\"stylechange\" type=\"radio\" name=\"tkey\" id=\"tkeyrad1\" value=\"1\" disabled " . ($tenkey_id == 1 ? "checked" : "") . ">&nbsp;<label for=\"tkeyrad1\">Hide</label>
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
				<p>" . getFileTime($path_file) . "</p>
			</div>\n";
?>
