<?php
require_once("db.php");
if(!empty($_GET['username'])) {
	// Checking username is available or not.
	$cn = connect();
	$username = @mysql_real_escape_string($_GET['username']);
	$rs = @mysql_query("SELECT COUNT(*) FROM `user` WHERE `username`='" . $username . "';", $cn);
	$n = (int) @mysql_result($rs, 0, 0);
	if($n == 0) {
		echo("available");
	} else {
		echo("unavailable");
	}
	disconnect($cn);
} else {
	print_r($_GET);
}
?>