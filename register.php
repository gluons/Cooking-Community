<?php
@session_start();
@require_once("db.php");
@header("Content-Type: application/json; charset=utf8");

$cn = connect();
$emailPattern = <<<'EMAIL'
/(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/
EMAIL;

$valid = true;
$message = "";
// Checking form data.
if(!empty($_POST['registerUsername'])) {
	$username = $_POST['registerUsername'];
	$rs = @mysql_query("SELECT COUNT(*) FROM `user` WHERE `username`='" . $username . "';", $cn);
	$n = (int) @mysql_result($rs, 0, 0);
	if($n != 0) {
		$message .= "Username is not available.<br>";
		$valid = false;
	}
} else {
	$valid = false;
	$message .= "No username received.<br>";
}
if(!empty($_POST['registerPassword'])) {
	$password = $_POST['registerPassword'];
} else {
	$valid = false;
	$message .= "No password received.<br>";
}
if(!empty($_POST['registerConfirmPassword'])) {
	$cpassword = $_POST['registerConfirmPassword'];
} else {
	$valid = false;
	$message .= "No confirm password received.<br>";
}
if((!empty($password) && !empty($cpassword)) && ($password != $cpassword)) {
	$valid = false;
	$message .= "Password and confirm password is mismatch.<br>";
}
if(!empty($_POST['registerEmail'])) {
	$email = $_POST['registerEmail'];
	if(!preg_match($emailPattern, $email)) {
		$valid = false;
		$message .= "Invalid email address.<br>";
	}
} else {
	$valid = false;
	$message .= "No email received.<br>";
}
if(!empty($_POST['registerConfirmEmail'])) {
	$cemail = $_POST['registerConfirmEmail'];
} else {
	$valid = false;
	$message .= "No confirm email received.<br>";
}
if((!empty($email) && !empty($cemail)) && ($email != $cemail)) {
	$valid = false;
	$message .= "Email and confirm email is mismatch.<br>";
}
if(!empty($_POST['registerFirstname'])) {
	$firstname = $_POST['registerFirstname'];
} else {
	$valid = false;
	$message .= "No firstname received.<br>";
}
if(!empty($_POST['registerLastname'])) {
	$lastname = $_POST['registerLastname'];
} else {
	$valid = false;
	$message .= "No lastname received.";
}

if($valid) {
	// Creating a new user.
	$encPassword = md5($password);
	$query = <<<QUERY
INSERT INTO `user` SET `username`='$username', `password`='$encPassword', `email`='$email', `firstname`='$firstname', `lastname`='$lastname', `create_time`=NOW();
QUERY;
	if(@mysql_query($query, $cn)) {
		$_SESSION['username'] = $username;
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
echo(json_encode($result));
?>