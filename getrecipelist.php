<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_GET['list'])) {
	$list = $_GET['list'];
	$cn = connect();
	$result = array();
	switch($list) {
		case "all":
			// Retriving all recipe menu.
			$query = <<<QUERY
			SELECT `id`,`name`, `description` FROM `menu` ORDER BY `create_time` DESC;
QUERY;
			$rs = @mysql_query($query, $cn);
			while($row = @mysql_fetch_array($rs)) {
				$id = $row['id'];
				$name = $row['name'];
				$description = $row['description'];
				$description = nl2br($description, false);
				$result[] = array(
					"id" => $id,
					"name" => $name,
					"description" => $description
				);
			}
			break;
		case "mine":
			if(!empty($_SESSION['username'])) {
				$username = $_SESSION['username'];
				// Getting user id from username.
				$query = <<<QUERY
SELECT `id` FROM `user` WHERE `username`='$username';
QUERY;
				$rs = @mysql_query($query, $cn);
				$userId = (int) @mysql_result($rs, 0, 0);
				// Retriving my recipe menu.
				$query = <<<QUERY
SELECT `id`,`name`, `description` FROM `menu` WHERE `owner_id`=$userId ORDER BY `create_time` DESC;
QUERY;
				$rs = @mysql_query($query, $cn);
				while($row = @mysql_fetch_array($rs)) {
					$id = $row['id'];
					$name = $row['name'];
					$description = $row['description'];
					$description = nl2br($description, false);
					$result[] = array(
						"id" => $id,
						"name" => $name,
						"description" => $description
					);
				}
			}
			break;
		default:
		$result = array();
	}
	disconnect($cn);
} else {
	$result = array();
}
echo(json_encode($result));
?>