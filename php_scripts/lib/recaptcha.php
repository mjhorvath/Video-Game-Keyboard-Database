<?php
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

	// https://stackoverflow.com/questions/30006081/recaptcha-2-0-with-ajax

	header("Content-Type: text/html; charset=utf8");

	$path_ssi2 = "../../ssi/";
	include($path_ssi2 . "recaptchakey.php");

	// assemble the message from the POST fields

	// getting the captcha
	$captcha = null;
	if (isset($_POST["captcha"]))
	{
		$captcha = $_POST["captcha"];
	}
	if (!$captcha)
	{
		echo "not ok";
		return;
	}

	$name		= $_POST["name"];
	$email		= $_POST["email"];
	$message	= $_POST["message"];
	$titletxt	= $_POST["titletxt"];
	$titleurl	= $_POST["titleurl"];
	$legend		= $_POST["legend"];
	$command	= $_POST["command"];
	$binding	= $_POST["binding"];
	$layout		= $_POST["layout"];
	$record		= $_POST["record"];
	$newsub		= $_POST["newsub"];
	$timeraw	= time();
	$timeform	= date("l jS \of F Y h:i:s A", $timeraw);
	$at		= "@";
	$dot		= ".";
	$gap		= "";
	$admin		= "mikh" . $gap . "2161" . $at . "gmail" . $dot . "com";
	$newsub		= $newsub == "on" ? "true" : "false";

	// handling the captcha and checking if it's ok
	$secret = writeRecaptchaSecret();
	$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);

	// if the captcha is cleared with google, send the mail and echo ok.
	if ($response["success"] != false)
	{
		// Send to admin
		$mail = $admin;
		$subj = "VGKD Bindings Submission: " . $titletxt;
		$body = "NAME:\t\t"	. $name		. "\r\n" .
			"EMAIL:\t\t"	. $email	. "\r\n" .
			"MESSAGE:\t"	. $message	. "\r\n" .
			"GAME TITLE:\t"	. $titletxt	. "\r\n" .
			"GAME URL:\t"	. $titleurl	. "\r\n" .
			"LAYOUT:\t\t"	. $layout	. "\r\n" .
			"RECORD:\t\t"	. $record	. "\r\n" .
			"NEWSUB:\t\t"	. $newsub	. "\r\n" .
			"TIMERAW:\t"	. $timeraw	. "\r\n" .
			"TIMEFORM:\t"	. $timeform	. "\r\n\r\n" .
			"LEGENDS:\r\n"	. $legend	. "\r\n\r\n" .
			"COMMANDS:\r\n"	. $command	. "\r\n\r\n" .
			"BINDINGS:\r\n"	. $binding	. "\r\n\r\n";
		$head = "MIME-Version: 1.0\r\n" .
			"Content-type:text/plain;charset=UTF-8\r\n" .
			"From: "	. $email . "\r\n" .
			"Reply-To: "	. $email . "\r\n";
		mail($mail, $subj, $body, $head);

		// Send to submitter
		$mail = $email;
		$subj = "VGKD Bindings Submission: " . $titletxt;
		$body = "Thank you for submitting a binding scheme to Video Game Keyboard Diagrams. Below is a copy of the data you submitted.\r\n\r\n" .
			"NAME:\t\t"	. $name		. "\r\n" .
			"EMAIL:\t\t"	. $email	. "\r\n" .
			"MESSAGE:\t"	. $message	. "\r\n" .
			"GAME TITLE:\t"	. $titletxt	. "\r\n" .
			"GAME URL:\t"	. $titleurl	. "\r\n" .
			"LAYOUT:\t\t"	. $layout	. "\r\n" .
			"RECORD:\t\t"	. $record	. "\r\n" .
			"NEWSUB:\t\t"	. $newsub	. "\r\n" .
			"TIMERAW:\t"	. $timeraw	. "\r\n" .
			"TIMEFORM:\t"	. $timeform	. "\r\n\r\n" .
			"LEGENDS:\r\n"	. $legend	. "\r\n\r\n" .
			"COMMANDS:\r\n"	. $command	. "\r\n\r\n" .
			"BINDINGS:\r\n"	. $binding	. "\r\n\r\n";
		$head = "MIME-Version: 1.0\r\n" .
			"Content-type:text/plain;charset=UTF-8\r\n" .
			"From: "	. $admin . "\r\n" .
			"Reply-To: "	. $admin . "\r\n";
		mail($mail, $subj, $body, $head);

		// the echo goes back to the ajax, so the user can know if everything is ok
		echo "ok";
		return;
	}
	else
	{
		echo "not ok";
		return;
	}
?>
