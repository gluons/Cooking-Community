<?php
@require_once("db.php");
if(!empty($_GET['id'])) {
	$cn = connect();
	$id = (int) @mysql_real_escape_string($_GET['id']);
	// Display the recipe image.
	$rs = @mysql_query("SELECT `image`, `image_type` FROM `menu` WHERE `id`=" . $id . ";", $cn);
	if(@mysql_num_rows($rs) == 1) {
		$arr = @mysql_fetch_array($rs);
		$image = $arr['image'];
		$type = $arr['image_type'];
		header("Content-Type: " . $type);
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Expires: Thu, 1 Jan 1970 00:00:00 GMT");
		echo($image);
	} else {
		header("HTTP/1.0 404 Not Found");
	}
	disconnect($cn);
} else {
	header("HTTP/1.0 404 Not Found");
}
?>