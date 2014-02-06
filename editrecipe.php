<?php
if(isset($_POST['sid'])) {
	@session_id($_POST['sid']);
}
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_SESSION['username']) && !empty($_POST['id'])) {
	$username = $_SESSION['username'];
	$id = $_POST['id'];
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
	if((!empty($_POST['recipeStepList'])) || (count($_POST['recipeStepList']) == 0)) {
		$recipeStepList = $_POST['recipeStepList'];
	} else {
		$valid = false;
		$message .= "No recipe step list.";
	}
	
	if($valid) {
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
			$isSuccessful = false;
			// Edit recipe menu name and description.
			$query = <<<QUERY
UPDATE `menu` SET `name`='$recipeName', `description`='$recipeDescription' WHERE `id`=$id;
QUERY;
			if(@mysql_query($query, $cn)) {
				// Edit recipe picture.
				if(isset($_FILES['Filedata'])) {
					$recipePicture = $_FILES['Filedata'];
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
UPDATE `menu` SET `image`='$data', `image_type`='$type' WHERE `id`=$id;
QUERY;
						@mysql_query($query, $cn);
					}
				}
				// Remove old all recipe menu sequence.
				$query = <<<QUERY
DELETE FROM `menu_sequence` WHERE `menu_id`=$id;
QUERY;
				if(@mysql_query($query, $cn)) {
					$stepQueries = array();
					foreach($recipeStepList as $k => $v) {
						$stepQueries[] = "(" . $id . ", " . ($k + 1) . ", '" . htmlspecialchars(str_replace("<br>", "\n", $v)) . "')";
					}
					$stepQuery = implode(", ", $stepQueries);
					// Adding new recipe menu sequence.
					$query = <<<QUERY
INSERT INTO `menu_sequence` (`menu_id`, `sequence`, `sequence_description`) VALUES $stepQuery;
QUERY;
					if(@mysql_query($query, $cn)) {
						$isSuccessful = true;
					}
				}
			}
			if($isSuccessful) {
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