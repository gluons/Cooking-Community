<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$cn = connect();
	$valid = true;
	$isPasswordCorrect = false;
	$message = "";
	// Checking form data.
	if(!empty($_POST['changePasswordNewPassword'])) {
		$newPassword = $_POST['changePasswordNewPassword'];
	} else {
		$valid = false;
		$message .= "No new password received.<br>";
	}
	if(!empty($_POST['changePasswordConfirmPassword'])) {
		$cpassword = $_POST['changePasswordConfirmPassword'];
	} else {
		$valid = false;
		$message .= "No confirm password received.<br>";
	}
	if((!empty($newPassword) && !empty($cpassword)) && ($newPassword != $cpassword)) {
		$valid = false;
		$message .= "New password and confirm password is mismatch.<br>";
	}
	if(!empty($_POST['changePasswordOldPassword'])) {
		$oldPassword = $_POST['changePasswordOldPassword'];
		$rs = @mysql_query("SELECT COUNT(*) FROM `user` WHERE `username`='" . $username . "' AND `password`='" . md5($oldPassword) . "';", $cn);
		$n = (int) @mysql_result($rs, 0, 0);
		if($n == 1) {
			$isPasswordCorrect = true;
		}
	} else {
		$valid = false;
		$message .= "No old password received.<br>";
	}
	if($valid) {
		if($isPasswordCorrect) {
			// Changing password.
			$encNewPassword = md5($newPassword);
			$encOldPassword = md5($oldPassword);
			$query = <<<QUERY
UPDATE `user` SET `password`='$encNewPassword' WHERE `username`='$username' AND `password`='$encOldPassword';
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
				"result" => "incorrect_pass"
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