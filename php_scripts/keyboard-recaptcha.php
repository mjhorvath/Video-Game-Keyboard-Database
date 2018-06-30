<?php
	// https://stackoverflow.com/questions/30006081/recaptcha-2-0-with-ajax
	$path_root = "../";
	include($path_root . 'ssi/recaptchakey.php');

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

	// handling the captcha and checking if it's ok
	$secret = writeRecaptchaSecret();
	$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);

	// if the captcha is cleared with google, send the mail and echo ok.
	if ($response["success"] != false)
	{
		// send the actual mail
		mail
		(
			"mikh2161@gmail.com",
			"VGKD Bindings Submission",
			"NAME:\t\t"	. $name		. "\n" .
			"EMAIL:\t\t"	. $email	. "\n" .
			"MESSAGE:\t"	. $message	. "\n" .
			"GAME TITLE:\t"	. $titletxt	. "\n" .
			"GAME URL:\t"	. $titleurl	. "\n" .
			"LAYOUT:\t\t"	. $layout	. "\n\n" .
			"LEGENDS:\n"	. $legend	. "\n\n" .
			"COMMANDS:\n"	. $command	. "\n\n" .
			"BINDINGS:\n"	. $binding	. "\n\n"
		);

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
