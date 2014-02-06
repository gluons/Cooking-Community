<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$cn = connect();
	// Loading profile to display.
	$query = <<<QUERY
SELECT `id`, `email`, `firstname`, `lastname`, `create_time` FROM `user` WHERE `username`='$username';
QUERY;
	$rs = @mysql_query($query, $cn);
	if(@mysql_num_rows($rs) == 1) {
		$id = (int) @mysql_result($rs, 0, 'id');
		$email = @mysql_result($rs, 0, 'email');
		$firstname = @mysql_result($rs, 0, 'firstname');
		$lastname = @mysql_result($rs, 0, 'lastname');
		$registerTimeDT = new DateTime(@mysql_result($rs, 0, 'create_time'), new DateTimeZone('Asia/Bangkok'));
		$registerTime = $registerTimeDT->format("l j F Y, G:i:s");
		$result = array(
			"result" => "success",
			"profile" => array(
				"id" => $id,
				"username" => $username,
				"email" => $email,
				"firstname" => $firstname,
				"lastname" => $lastname,
				"registertime" => $registerTime
			)
		);
	} else {
		$result = array(
			"result" => "fail"
		);
	}
	disconnect($cn);
} else {
	$result = array(
		"result" => "fail"
	);
}
echo(json_encode($result));
?>