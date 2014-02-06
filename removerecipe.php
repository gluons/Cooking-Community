<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_SESSION['username']) && !empty($_POST['id'])) {
	$username = $_SESSION['username'];
	$id = $_POST['id'];
	$cn = connect();
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
	if($n == 1) { // This user has permission.
		$query = <<<QUERY
DELETE FROM `menu_comment` WHERE `menu_id`=$id;
QUERY;
		@mysql_query($query, $cn);
		$query = <<<QUERY
DELETE FROM `menu_sequence` WHERE `menu_id`=$id;
QUERY;
		@mysql_query($query, $cn);
		$query = <<<QUERY
DELETE FROM `menu` WHERE `id`=$id;
QUERY;
		if(@mysql_query($query, $cn)) {
			$result = array(
				"result" => "success"
			);
		} else {
			$result = array(
				"result" => "fail"
			);
		}
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