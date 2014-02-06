<?php
if(isset($_POST['sid'])) {
	@session_id($_POST['sid']);
}
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$cn = connect();
	$valid = true;
	$message = "";
	// Checking form data.
	if(!empty($_POST['recipeName'])) {
		$recipeName = $_POST['recipeName'];
	} else {
		$valid = false;
		$message .= "No recipe name received.<br>";
	}
	if(!empty($_POST['recipeDescription'])) {
		$recipeDescription = htmlspecialchars($_POST['recipeDescription']);
	} else {
		$valid = false;
		$message .= "No recipe description received.<br>";
	}
	if(!empty($_FILES['Filedata'])) {
		$recipePicture = $_FILES['Filedata'];
	} else {
		$valid = false;
		$message .= "No recipe picture received.<br>";
	}
	if((!empty($_POST['recipeStepList'])) || (count($_POST['recipeStepList']) == 0)) {
		$recipeStepList = $_POST['recipeStepList'];
	} else {
		$valid = false;
		$message .= "No recipe step list.";
	}
	
	if($valid) {
		// Getting user id from username.
		$query = <<<QUERY
SELECT `id` FROM `user` WHERE `username`='$username';
QUERY;
		$rs = @mysql_query($query, $cn);
		$userId = (int) @mysql_result($rs, 0, 0);
		$tempFile = $recipePicture['tmp_name'];
		$size = @getimagesize($tempFile);
		$type = $size['mime'];
		$fp = @fopen($tempFile, "rb");
		if($fp) {
			$data = @fread($fp, filesize($tempFile));
			$data = @addslashes($data);
			@fclose($data);
			// Adding new recipe menu.
			$query = <<<QUERY
INSERT INTO `menu` SET `name`='$recipeName', `description`='$recipeDescription', `image`='$data', `image_type`='$type', `create_time`=NOW(), `owner_id`=$userId;
QUERY;
			@mysql_query($query, $cn);
			$rs = @mysql_query("SELECT LAST_INSERT_ID();", $cn);
			$recipeId = (int) @mysql_result($rs, 0, 0);
			if($recipeId) {
				$stepQueries = array();
				foreach($recipeStepList as $k => $v) {
					$stepQueries[] = "(" . $recipeId . ", " . ($k + 1) . ", '" . htmlspecialchars(str_replace("<br>", "\n", $v)) . "')";
				}
				$stepQuery = implode(", ", $stepQueries);
				// Adding the recipe menu sequence.
				$query = <<<QUERY
INSERT INTO `menu_sequence` (`menu_id`, `sequence`, `sequence_description`) VALUES $stepQuery;
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
		} else {
			$result = array(
				"result" => "fail"
			);
		}
	} else {
		$result = array(
			"result" => $message
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