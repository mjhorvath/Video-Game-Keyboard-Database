<?php
	echo
"<div class=\"boxdiv\">
	<p>
		<a target=\"_blank\" rel=\"license\" href=\"https://www.gnu.org/licenses/lgpl-3.0.en.html\"><img alt=\"GNU LGPLv3 icon\" style=\"border-width:0;\" src=\"data:image/png;base64,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\"/></a>
		<a target=\"_blank\" rel=\"license\" href=\"http://creativecommons.org/licenses/by-sa/3.0/\"><img alt=\"CC BY-SA 3.0 icon\" style=\"border-width:0;\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFgAAAAfCAMAAABUFvrSAAABv1BMVEX///////////8AAAD///8QEBDQ08/LzsrGysXIzMcjHyDR1NDBxcBAQEDO0c1gYGBQUFDMz8u/v78wMDAoKShwcHDM0MzM0MsNDg28wbvT1tLLz8ogICDEyMNDREORk5HJzci5vrjDx8KAgIDf39+1urTv7++ssquvta4bGxu9wry3vLZQUVCzuLKfn595fHm6v7mvr6+xt7DAxb/S1dLS1dHLz8vJzcnDyMPPz8+EgYK5vrnIx8eRlJG/w77N0c3x8fGsq6uts6y+wr3R1NEpKSm2u7W/xL6wtq8+Pz6utK28wLy+w76yt7HEx8Nwcm/KzcnEycS7wLqTmJPj4+OjoaEODg6vtK60urOEhoR8f3zP08/W1dXFycSus62yuLGEhYTAxL+JjImeoJ20ubO6ubnO0s5dXl24vbfHy8e9wr3Q1NAqJyeYlpfAxcDFycW1tbU/NzljX2GChYKRj4/JyMg1NjWflprN0Mx2c3Tg3+AtLS3o5+ewta/Lysu8wbwxLS4oKCh9f3yfo566v7rCx8KenZ2WmZbIzMirsaq7wLuPj4/GysaOh4syMzGvsq+ws69ubGwkJCTN0cyxtrCa2VaiAAAAA3RSTlMADgqEIABOAAADTklEQVR4XrWVVY/sOBCF+245DM3MPMzMzMxwmZkZlpnpB2853WmPkmik6dWcF0fH0qfKsavsugDnIpcLoNicja6oA6LUL3hDIW98QxKT6lh0uLnY2vVpaWqq/NPZhWTkdjRdy4mZuM4rPbFYzwyv92XEXHdTByU3LE2V6yK7ALmPk/cEXbmaigAqkjpUbselTfU+kq8sNFw2S26fBdRsOwM4+QycRa4U5tONUFPjKB/KXFKbhptvri5US94OAGjBoAYQ2GYAu8/A0WtJKaS0II9z532+vJtDdMvaP5nN7mh2DsMwSv5mDzwFgip4YI8R7D4Dr+TuhSmXy5dIRW2IDqyFpNzY1t0ilkzBAXAjFYBuQ4ABDH9nZGTH9BlYFQUeuf4Cqankx5r5uKg2Ycn/7mMW7eC5aIKJB2p5Gv6ujLpV9Rl44Ec9jdxquYw8ejuTnN9qPlhtuFwuz0KB7RZg1gRQf0R+9vydLL+s+AwsxpVG4AYNICNz0Kj0ieoX2eIVmgVo1HWDEQjRwASgP/FG/o2Q7+UvKz4DS+NXAfLEojaAQ13KTXdgFhQcpCag6Bpk4CCZ+Fv+CmOW5Y+Gz8D9xyngMAifR9PyleUXhHCQ4jdoFje79k8Fk6cffAb4vQUsKBFwIxCotE5jcdPfjszEL3X/fPegq+HUKMit3VeETMqyNQpvLwCW6IdEwZdo8+P16Ey0GVn0eEU1ihcOwdXDA7AfHsYrDw09lB9ZDy8cA8B/MXKuLcYfxEK/mmC8VsT5uqE/JKP+mqD+mcEnGyRoa5DPJycvMt8eRWkwkcco6GKN4swtzQ6vemrVhdYVUYTa4SEhAOB3B/3WIWT32XVrAY7UrlunZiwkASm+Pzn/beW61TM2pfE0ZuHcIN9ND8+10s6rZ9A/MVq6cEpL1wnGIXTDaQil9czAGDYeHUJ1geFI4O8geZBxC8bYFF5A/aJgWPc+ODnoS3kO4LUSPoL/C4avQw/uWJ4m5e0PFkClP+iH4waxgynZy984+Zim+bCVCxUssXPZph0M64KupM3nf1TRBXsO5th02DA2ncHw+x/x8WOlNxbrVXi9789FsIkFYUc7Vmxq8fqy4A2HvcLydYZ1rNjuO4LPSa7Pzon7HyRw4D8G7CZGAAAAAElFTkSuQmCC\"/></a>
	</p>
	<p>&quot;Video Game Keyboard Diagrams&quot; software was created by <a target=\"_blank\" href=\"http://isometricland.net\">Michael Horvath</a> and is licensed under <a target=\"_blank\" rel=\"license\" href=\"https://www.gnu.org/licenses/lgpl-3.0.en.html\">GNU LGPLv3</a> or later. Content is licensed under <a target=\"_blank\" href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC BY-SA 3.0</a> or later. You can find this project on <a target=\"_blank\" href=\"https://github.com/mjhorvath/vgkd\">GitHub</a>.</p>
	<p>Return to <a href=\"" . $path_vgkb . "keyboard.php\">Video Game Keyboard Diagrams</a>. View the <a href=\"" . $path_vgkb . "keyboard-list.php\">master table</a> or <a href=\"" . $path_vgkb . "keyboard-log.php\">change log</a>. Here is a <a href=\"" . $path_vgkb . "keyboard-links.php\">list of links</a>. Having trouble printing? Take a look at <a href=\"" . $path_vgkb . "keyboard.php#print_tips\">these printing tips</a>.</p>
	<p>\n";
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
"	</p>
	<p>" . getFileTime() . " GRID:" . $gamesrecord_id . ".</p>
</div>\n";
?>
