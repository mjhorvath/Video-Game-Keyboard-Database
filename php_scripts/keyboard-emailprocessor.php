<?php
	// need a way to move this code into the main submission form itself and spawn popup messages instead of loading external pages
	$path_root = "./";
	$where_form_is = "http://" . $_SERVER['SERVER_NAME'] . strrev(strstr(strrev($_SERVER['PHP_SELF']),"/"));
	session_start();
	if (($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_POST['security_code'])))
	{ 
		mail
		(
			"mikh2161@gmail.com",
			"VGKD Bindings Submission",
			"NAME:\n"	. $_POST['email_1'] . "\n\n" .
			"EMAIL:\n"	. $_POST['email_2'] . "\n\n" .
			"MESSAGE:\n"	. $_POST['email_3'] . "\n\n" .
			"GAME TITLE:\n"	. $_POST['email_4'] . "\n\n" .
			"LEGENDS:\n"	. $_POST['email_5'] . "\n\n" .
			"COMMANDS:\n"	. $_POST['email_6'] . "\n\n" .
			"BINDINGS:\n"	. $_POST['email_7'] . "\n\n"
		);
		include($path_root . "captchaconfirmed.php");
	}
	else
	{
//		echo "Invalid security code. Please hit the 'Back' button and try again.";
		include($path_root . "captchafailed.php");
	}
?>
