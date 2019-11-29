<?php
	// ---------------------------------------------------------------------
	// SVG
	function selContribsGamesSVG($context)
	{
		selContribsGames($context);
	}
	function selContribsStylesSVG($context)
	{
		selContribsStyles($context);
	}
	function selContribsLayoutsSVG($context)
	{
		selContribsLayouts($context);
	}
	function selThisGameSVG_ID($context)
	{
		selThisGames_ID($context);
	}
	function selThisGameSVG_SEO($context)
	{
		selThisGame_SEO($context);
	}
	function selAuthorsSVG($context)
	{
		selAuthors($context);
	}
	function selStyleGroupsSVG($context)
	{
		selStyleGroups($context);
	}
	function selStylesSVG($context)
	{
		selStyles($context);
	}
	function selThisStyleSVG($context)
	{
		selThisStyle($context);
	}
	function selThisFormatSVG($context)
	{
		selThisFormat($context);
	}
	function selPositionsSVG($context)
	{
		selPositions($context);
	}
	function selThisLayoutSVG($context)
	{
		selThisLayout($context);
	}
	function selThisPlatformSVG()
	{
		global $con, $platform_id;
		$selectString = "SELECT p.platform_name FROM platforms AS p WHERE p.platform_id = " . $platform_id . ";";
		selectQuery($con, $selectString, "resThisPlatformSVG", $context);
	}
	function resThisPlatformSVG($in_result, $context)
	{
		global $platform_name;
		$platform_row = mysqli_fetch_row($in_result);
		// platform_name
		$platform_name = cleantextSVG($platform_row[0], $context);
	}
	function selThisGamesRecordSVG()
	{
		global $con, $layout_id, $game_id;
		$selectString = "SELECT r.record_id FROM records_games AS r WHERE r.layout_id = " . $layout_id . " AND r.game_id = " . $game_id . ";";
		selectQuery($con, $selectString, "resThisGamesRecordSVG", $context);
	}
	function resThisGamesRecordSVG($in_result, $context)
	{
		global $gamesrecord_id;
		$gamesrecord_row = mysqli_fetch_row($in_result);
		// record_id
		$gamesrecord_id = $gamesrecord_row[0];
	}
	function selThisStylesRecordSVG()
	{
		global $con, $layout_id, $style_id;
		$selectString = "SELECT r.record_id FROM records_styles AS r WHERE r.layout_id = " . $layout_id . " AND r.style_id = " . $style_id . ";";
		selectQuery($con, $selectString, "resThisStylesRecordSVG", $context);
	}
	function resThisStylesRecordSVG($in_result, $context)
	{
		global $stylesrecord_id;
		$stylesrecord_row = mysqli_fetch_row($in_result);
		// record_id
		$stylesrecord_id = $stylesrecord_row[0];
	}
	function selBindingsSVG()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT b.normal_group, b.normal_action, b.shift_group, b.shift_action, b.ctrl_group, b.ctrl_action, b.alt_group, b.alt_action, b.altgr_group, b.altgr_action, b.extra_group, b.extra_action, b.image_file, b.image_uri, b.key_number FROM bindings AS b WHERE b.record_id = " . $gamesrecord_id . ";";
		selectQuery($con, $selectString, "resBindingsSVG");
	}
	function resBindingsSVG($in_result, $context)
	{
		global $binding_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// normal_group, normal_action, shift_group, shift_action, ctrl_group, ctrl_action, alt_group, alt_action, altgr_group, altgr_action, extra_group, extra_action, image_file, image_uri, key_number
			$binding_table[$temp_row[14]-1] = $temp_row;
		}
	}
	function selLegendsSVG()
	{
		global $con, $gamesrecord_id;
		$selectString = "SELECT l.keygroup_id, l.legend_description FROM legends AS l WHERE l.record_id = " . $gamesrecord_id . " ORDER BY l.keygroup_id;";
		selectQuery($con, $selectString, "resLegendsSVG");
	}
	function resLegendsSVG($in_result, $context)
	{
		global $legend_table;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// keygroup_id, legend_description
			$legend_table[] = $temp_row;
		}
	}
	function selLanguageStringsSVG()
	{
		global $con, $layout_language;
		$selectString = "SELECT l.language_code, l.language_title, l.language_description, l.language_keywords, l.language_legend, l.language_mouse, l.language_joystick, l.language_keyboard, l.language_notes, l.language_cheats, l.language_console, l.language_emote FROM languages AS l WHERE l.language_id = " . $layout_language . ";";
		selectQuery($con, $selectString, "resLanguageStringsSVG");
	}
	function resLanguageStringsSVG($in_result, $context)
	{
		global $language_code, $language_title, $language_description, $language_keywords, $language_legend, $language_mouse, $language_joystick, $language_keyboard, $language_note, $language_cheat, $language_console, $language_emote;
		while ($temp_row = mysqli_fetch_row($in_result))
		{
			// language_code, language_title, language_description, language_keywords, language_legend, language_mouse, language_joystick, language_keyboard, language_notes, language_cheats, language_console, language_emote
			$language_code		= cleantextSVG($temp_row[0]);
			$language_title		= cleantextSVG($temp_row[1]);
			$language_description	= cleantextSVG($temp_row[2]);
			$language_keywords	= cleantextSVG($temp_row[3]);
			$language_legend	= cleantextSVG($temp_row[4]);
			$language_mouse		= cleantextSVG($temp_row[5]);
			$language_joystick	= cleantextSVG($temp_row[6]);
			$language_keyboard	= cleantextSVG($temp_row[7]);
			$language_note		= cleantextSVG($temp_row[8]);
			$language_cheat		= cleantextSVG($temp_row[9]);
			$language_console	= cleantextSVG($temp_row[10]);
			$language_emote		= cleantextSVG($temp_row[11]);
		}
	}
?>
