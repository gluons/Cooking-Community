<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

if(!empty($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$cn = connect();
	$emailPattern = <<<'EMAIL'
/(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/
EMAIL;
	$valid = true;
	$isEmailEdited = false;
	$message = "";
	// Checking form data.
	if(!empty($_POST['editProfileEmail'])) {
		$email = $_POST['editProfileEmail'];
		if(!preg_match($emailPattern, $email)) {
			$valid = false;
			$message .= "Invalid email address.<br>";
		}
	} else {
		$valid = false;
		$message .= "No email received.<br>";
	}
	if(!empty($_POST['editProfileConfirmEmail'])) {
		$cemail = $_POST['editProfileConfirmEmail'];
		$isEmailEdited = true;
	} else {
		$isEmailEdited = false;
	}
	if((!empty($email) && !empty($cemail)) && ($email != $cemail)) {
		$valid = false;
		$message .= "Email and confirm email is mismatch.<br>";
	}
	if(!empty($_POST['editProfileFirstname'])) {
		$firstname = $_POST['editProfileFirstname'];
	} else {
		$valid = false;
		$message .= "No firstname received.<br>";
	}
	if(!empty($_POST['editProfileLastname'])) {
		$lastname = $_POST['editProfileLastname'];
	} else {
		$valid = false;
		$message .= "No lastname received.";
	}
	
	if($valid) {
		$query = <<<QUERY
UPDATE `user` SET 
QUERY;
		// Checking the email is edited or not.
		if($isEmailEdited) {
			$query .= <<<QUERY
`email`='$email', 
QUERY;
		}
		$query .= <<<QUERY
`firstname`='$firstname', `lastname`='$lastname' WHERE `username`='$username';
QUERY;
		// Perform update profile.
		if(@mysql_query($query, $cn)) {
			$result = array(
				"result" => "success",
				"fullname" => $firstname . " " . $lastname
			);
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