<?php
	$path_root = "../";
	$page_title = "Submission Failure";
	$foot_array = array("copyright");
	include($path_root . 'ssi/normalpage.php');
	print($page_top);
?>
<p style="color:red;">Invalid security code. Please go back and try again.</p>
<?php
	print($page_bot);
?>
