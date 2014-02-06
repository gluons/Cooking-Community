<?php
@require_once("db.php");

if(strtolower($_SERVER['REQUEST_METHOD']) == "get") {
	@header("Content-Type: application/atom+xml; charset=utf8");
	
	$id = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	if(substr($id, -1) == "/") {
		$id = substr($id, 0, strlen($id) - 1);
	}
	$updated = new DateTime();
	$updated = $updated->format(DateTime::RFC3339);
	$self = $id . "/" . basename($_SERVER['PHP_SELF']);
	
	$atom = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
	$atom .= '<feed xmlns="http://www.w3.org/2005/Atom">' . PHP_EOL;
	$atom .= <<<ATOM
	<id>$id</id>
	<title>Cooking Community Atom Feed</title>
	<updated>$updated</updated>
	<author>
		<name>Saran Tanpituckpong</name>
	</author>
	<link rel="self" href="$self" />
	<link href="$id" />

ATOM;
	$cn = connect();
	$query = <<<QUERY
SELECT `menu`.`id`, `menu`.`name`, `menu`.`create_time`, `user`.`firstname`, `user`.`lastname`, `user`.`email` FROM `menu` INNER JOIN `user` ON `menu`.`owner_id` = `user`.`id`;
QUERY;
	$rs = @mysql_query($query, $cn);
	if(@mysql_num_rows($rs) > 0) {
		while($row = @mysql_fetch_array($rs)) {
			$entryId = $id . "/recipe.html?id=" . $row['id'];
			$entryName = $row['name'];
			$entryUpdated = new DateTime($row['create_time']);
			$entryUpdated = $entryUpdated->format(DateTime::RFC3339);
			$entryAuthor = $row['firstname'] . " " . $row['lastname'];
			$entryAuthorEmail = $row['email'];
			
			
			$atom .= <<<ATOM
	<entry>
		<id>$entryId</id>
		<title>$entryName</title>
		<updated>$entryUpdated</updated>
		<author>
			<name>$entryAuthor</name>
			<email>$entryAuthorEmail</email>
		</author>
		<link rel="alternate" href="$entryId" />
	</entry>

ATOM;
		}
	}
	$atom .= "</feed>";
	disconnect($cn);
	echo($atom);
} else {
	@header("HTTP/1.0 405 Method Not Allowed");
}
?>