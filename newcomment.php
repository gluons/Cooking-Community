<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_SESSION['username']) && !empty($_POST['id'])) {
	$username = $_SESSION['username'];
	$cn = connect();
	$id = (int) @mysql_real_escape_string($_POST['id']);
	if(!empty($_POST['comment'])) {
		$comment = htmlspecialchars(@mysql_real_escape_string($_POST['comment']));
		// Getting user id from username.
		$query = <<<QUERY
SELECT `id` FROM `user` WHERE `username`='$username';
QUERY;
		$rs = @mysql_query($query, $cn);
		$userId = (int) @mysql_result($rs, 0, 0);
		
		$query = <<<QUERY
INSERT INTO `menu_comment` SET `user_id`=$userId, `menu_id`=$id, `comment`='$comment', `create_time`=NOW();
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
			"result" => "No comment received."
		);
	}
} else {
	$result = array(
		"result" => "fail"
	);
}
echo(json_encode($result));
?>