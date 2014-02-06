<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$cn = connect();
	// Getting current session state.
	$rs = @mysql_query("SELECT `firstname`, `lastname` FROM `user` WHERE `username`='" . $username . "';", $cn);
	if(@mysql_num_rows($rs) == 1) {
		$firstname = @mysql_result($rs, 0, 0);
		$lastname = @mysql_result($rs, 0, 1);
		$result = array(
			"status" => "on",
			"fullname" => $firstname . " " . $lastname,
			"sid" => session_id()
		);
	} else {
		@session_unset();
		@session_destroy();
		$result = array(
			"status" => "off"
		);
	}
	disconnect($cn);
} else {
	$result = array(
		"status" => "off"
	);
}
echo(json_encode($result));
?>