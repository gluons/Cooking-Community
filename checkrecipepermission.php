<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_GET['id'])) {
	$cn = connect();
	$id = (int) mysql_real_escape_string($_GET['id']);
	if(!empty($_SESSION['username'])) {
		$username = $_SESSION['username'];
		// Getting user id.
		$query = <<<QUERY
SELECT `id` FROM `user` WHERE `username`='$username';
QUERY;
		$rs = @mysql_query($query, $cn);
		$userId = (int) @mysql_result($rs, 0, 0);
		// Getting nummer of current recipe that own by this user.
		$query = <<<QUERY
SELECT COUNT(*) FROM `menu` WHERE `id`=$id AND `owner_id`=$userId;
QUERY;
		$rs = @mysql_query($query, $cn);
		$n = (int) @mysql_result($rs, 0, 0);
		if($n == 1) {
			$result = array(
				"result" => true
			);
		} else {
			$result = array(
				"result" => false
			);
		}
	} else {
		$result = array(
			"result" => false
		);
	}
	disconnect($cn);
} else {
	$result = array(
		"result" => false
	);
}
echo(json_encode($result));
?>