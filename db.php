<?php
require_once("config.php");
function connect() {
	global $host, $user, $pass, $dbname;
	$cn = @mysql_connect($host, $user, $pass);
	@mysql_select_db($dbname, $cn);
	@mysql_query("SET TIME_ZONE='+07:00';", $cn);
	return $cn;
}
function disconnect($link) {
	@mysql_close($link);
}
?>