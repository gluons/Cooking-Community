<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_GET['id'])) {
	$cn = connect();
	$id = (int) @mysql_real_escape_string($_GET['id']);
	if(!empty($_GET['isEditing']) && $_GET['isEditing']) {
		$isEditing = true;
	} else {
		$isEditing = false;
	}
	// Getting a recipe menu by id.
	$query = <<<QUERY
SELECT `name`, `description` FROM `menu` WHERE `id`=$id;
QUERY;
	$rs = @mysql_query($query, $cn);
	if(@mysql_num_rows($rs) == 1) {
		$name = @mysql_result($rs, 0, "name");
		$description = @mysql_result($rs, 0, "description");
		if($isEditing) {
			$description = htmlspecialchars_decode($description);
		} else {
			$description = nl2br($description, false);
		}
		$result = array(
			"name" => $name,
			"description" => $description,
			"steps" => array()
		);
		// Getting the recipe menu sequence.
		$query = <<<QUERY
SELECT `sequence_description` FROM `menu_sequence` WHERE `menu_id`=$id ORDER BY `sequence`;
QUERY;
		$rs = @mysql_query($query, $cn);
		while($row = @mysql_fetch_array($rs)) {
			$sequenceDescription = $row['sequence_description'];
			$result['steps'][] = str_replace("\n", "", nl2br($sequenceDescription, false));
		}
		$result['result'] = "success";
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