<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_POST['loginUsername']) && !empty($_POST['loginPassword'])) {
	$username = $_POST['loginUsername'];
	$password = $_POST['loginPassword'];
	$encPassword = md5($password);
	
	$cn = connect();
	// Checking username.
	$query = <<<QUERY
SELECT `username` FROM `user` WHERE `username`='$username';
QUERY;
	$rs = @mysql_query($query, $cn);
	if(@mysql_num_rows($rs) == 0) {
		$result = array(
			"result" => "incorrect_user"
		);
	} else {
		// Checking username and password.
		$query = <<<QUERY
SELECT `firstname`, `lastname` FROM `user` WHERE `username`='$username' AND `password`='$encPassword';
QUERY;
		$rs = @mysql_query($query, $cn);
		if(@mysql_num_rows($rs) == 0) {
			$result = array(
				"result" => "incorrect_pass"
			);
		} else {
			// When login success, store username in session.
			$_SESSION['username'] = $username;
			$firstname = @mysql_result($rs, 0, 0);
			$lastname = @mysql_result($rs, 0, 1);
			$result = array(
				"result" => "correct",
				"fullname" => $firstname . " " . $lastname,
				"sid" => session_id()
			);
		}
	}
	disconnect($cn);
} else {
	$result = array(
		"result" => "incomplete"
	);
}
echo(json_encode($result));
?>