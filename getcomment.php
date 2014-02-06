<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_GET['id'])) {
	$cn = connect();
	$id = (int) @mysql_real_escape_string($_GET['id']);
	// Getting comment with owner's name.
	$query = <<<QUERY
SELECT `user`.`firstname`, `user`.`lastname`, `menu_comment`.`comment` FROM `menu_comment` INNER JOIN `user` ON `menu_comment`.`user_id`=`user`.`id` WHERE `menu_comment`.`menu_id`=$id ORDER BY `menu_comment`.`create_time`;
QUERY;
	$rs = @mysql_query($query, $cn);
	if($rs) {
		$result = array(
			"comments" => array()
		);
		while($row = @mysql_fetch_array($rs)) {
			$fullname = $row['firstname'] . " " . $row['lastname'];
			$comment = $row['comment'];
			$comment = nl2br($comment, false);
			$result['comments'][] = array(
				"fullname" => $fullname,
				"comment" => $comment
			);
		}
		$result['result'] = "success";
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
echo(json_encode($result));
?>