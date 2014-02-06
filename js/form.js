// Login Form
function validateLoginForm() {
	var valid = true;
	var $username = $("#loginUsername");
	var $password = $("#loginPassword");
	var $usernameHelper = $("#loginUsernameHelper");
	var $passwordHelper = $("#loginPasswordHelper");
	$usernameHelper.empty();
	$passwordHelper.empty();
	
	if($username.val().length == 0) {
		$username.parent("div.form-group").addClass("has-error");
		$usernameHelper.text("Please enter username.");
		$usernameHelper.show();
		valid = false;
	} else {
		$username.parent("div.form-group").removeClass("has-error");
		$usernameHelper.empty();
		$usernameHelper.hide();
	}
	if($password.val().length == 0) {
		$password.parent("div.form-group").addClass("has-error");
		$passwordHelper.text("Please enter password.");
		$passwordHelper.show();
		valid = false;
	} else {
		$password.parent("div.form-group").removeClass("has-error");
		$passwordHelper.empty();
		$passwordHelper.hide();
	}
	return valid;
}
function clearLoginFormError() {
	var $username = $("#loginUsername");
	var $password = $("#loginPassword");
	var $usernameHelper = $("#loginUsernameHelper");
	var $passwordHelper = $("#loginPasswordHelper");
	$username.parent("div.form-group").removeClass("has-error");
	$password.parent("div.form-group").removeClass("has-error");
	$usernameHelper.empty();
	$passwordHelper.empty();
	$usernameHelper.hide();
	$passwordHelper.hide();
}
function resetLoginForm() {
	$("#loginForm")[0].reset();
}

// Register Form
function checkRegisterUsername() {
	var $username = $("#registerUsername");
	var $usernameHelper = $("#registerUsernameHelper");
	if(($username.val().length >= 4) && (/^[a-zA-Z][a-zA-Z0-9_\-]*$/.test($username.val()))) {
		$.get("checkusername.php", {username: $username.val()}, function(data) {
			if(data == "available") {
				$username.parent("div.form-group").removeClass("has-error");
				$username.parent("div.form-group").addClass("has-success");
				$usernameHelper.html("Username is available");
				$usernameHelper.show();
				return true;
			} else {
				$username.parent("div.form-group").removeClass("has-success");
				$username.parent("div.form-group").addClass("has-error");
				$usernameHelper.html("Username is not available.");
				$usernameHelper.show();
				return false;
			}
		});
	} else {
		$username.parent("div.form-group").removeClass("has-error");
		$username.parent("div.form-group").removeClass("has-success");
		$usernameHelper.empty();
		$usernameHelper.hide();
	}
}
function validateRegisterForm() {
	var valid = true;
	var $username = $("#registerUsername");
	var $password = $("#registerPassword");
	var $cpassword = $("#registerConfirmPassword");
	var $email = $("#registerEmail");
	var $cemail = $("#registerConfirmEmail");
	var $fname = $("#registerFirstname");
	var $lname = $("#registerLastname");
	var $usernameHelper = $("#registerUsernameHelper");
	var $passwordHelper = $("#registerPasswordHelper");
	var $cpasswordHelper = $("#registerCPasswordHelper");
	var $emailHelper = $("#registerEmailHelper");
	var $cemailHelper = $("#registerCEmailHelper");
	var $fnameHelper = $("#registerFirstnameHelper");
	var $lnameHelper = $("#registerLastnameHelper");
	
	var usernameError = false;
	var usernameErrorMessage = "";
	if($username.val().length < 4) {
		usernameError = true;
		usernameErrorMessage += "Username must contain at least 4 letters.";
	}
	if(!/^[a-zA-Z][a-zA-Z0-9_\-]*$/.test($username.val())) {
		usernameError = true;
		if(usernameErrorMessage.length > 0) {
			usernameErrorMessage += "<br>";
		}
		usernameErrorMessage += "Username must begin with letter and be composed of letters, digits, underscores or hyphens.";
	}
	if(usernameError) {
		$username.parent("div.form-group").removeClass("has-success");
		$username.parent("div.form-group").addClass("has-error");
		$usernameHelper.html(usernameErrorMessage);
		$usernameHelper.show();
		valid = false;
	} else if(checkRegisterUsername()) {
		valid = true;
	} else {
		$username.parent("div.form-group").removeClass("has-error");
		$usernameHelper.empty();
		$usernameHelper.hide();
	}
	if($password.val().length < 6) {
		$password.parent("div.form-group").addClass("has-error");
		$cpassword.parent("div.form-group").removeClass("has-error");
		$passwordHelper.html("Password must contain at least 6 letters.");
		$passwordHelper.show();
		$cpasswordHelper.empty();
		$cpasswordHelper.hide();
		valid = false;
	} else if($password.val() != $cpassword.val()) {
		$password.parent("div.form-group").addClass("has-error");
		$cpassword.parent("div.form-group").addClass("has-error");
		$passwordHelper.empty();
		$passwordHelper.hide();
		$cpasswordHelper.html("Password mismatch.");
		$cpasswordHelper.show();
		valid = false;
	} else {
		$password.parent("div.form-group").removeClass("has-error");
		$cpassword.parent("div.form-group").removeClass("has-error");
		$passwordHelper.empty();
		$cpasswordHelper.empty();
		$passwordHelper.hide();
		$cpasswordHelper.hide();
	}
	var emailPattern = "(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|\"(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\])";
	var emailRegEx = new RegExp(emailPattern);
	if($email.val().length == 0) {
		$email.parent("div.form-group").addClass("has-error");
		$cemail.parent("div.form-group").removeClass("has-error");
		$emailHelper.html("Please enter email address.");
		$emailHelper.show();
		$cemailHelper.empty();
		$cemailHelper.hide();
		valid = false;
	} else if(!emailRegEx.test($email.val())) {
		$email.parent("div.form-group").addClass("has-error");
		$cemail.parent("div.form-group").removeClass("has-error");
		$emailHelper.html("Invalid email address.");
		$emailHelper.show();
		$cemailHelper.empty();
		$cemailHelper.hide();
		valid = false;
	} else if($email.val() != $cemail.val()) {
		$email.parent("div.form-group").addClass("has-error");
		$cemail.parent("div.form-group").addClass("has-error");
		$emailHelper.empty();
		$emailHelper.hide();
		$cemailHelper.html("Email address mismatch.");
		$cemailHelper.show();
		valid = false;
	} else {
		$email.parent("div.form-group").removeClass("has-error");
		$cemail.parent("div.form-group").removeClass("has-error");
		$emailHelper.empty();
		$cemailHelper.empty();
		$emailHelper.hide();
		$cemailHelper.hide();
	}
	if($fname.val().length == 0) {
		$fname.parent("div.form-group").addClass("has-error");
		$fnameHelper.html("Please enter firstname.");
		$fnameHelper.show();
		valid = false;
	} else {
		$fname.parent("div.form-group").removeClass("has-error");
		$fnameHelper.empty();
		$fnameHelper.hide();
	}
	if($lname.val().length == 0) {
		$lname.parent("div.form-group").addClass("has-error");
		$lnameHelper.html("Please enter lastname.");
		$lnameHelper.show();
		valid = false;
	} else {
		$lname.parent("div.form-group").removeClass("has-error");
		$lnameHelper.empty();
		$lnameHelper.hide();
	}
	return valid;
}
function clearRegisterFormError() {
	var $username = $("#registerUsername");
	var $password = $("#registerPassword");
	var $cpassword = $("#registerConfirmPassword");
	var $email = $("#registerEmail");
	var $cemail = $("#registerConfirmEmail");
	var $fname = $("#registerFirstname");
	var $lname = $("#registerLastname");
	var $usernameHelper = $("#registerUsernameHelper");
	var $passwordHelper = $("#registerPasswordHelper");
	var $cpasswordHelper = $("#registerCPasswordHelper");
	var $emailHelper = $("#registerEmailHelper");
	var $cemailHelper = $("#registerCEmailHelper");
	var $fnameHelper = $("#registerFirstnameHelper");
	var $lnameHelper = $("#registerLastnameHelper");
	$username.parent("div.form-group").removeClass("has-success");
	$username.parent("div.form-group").removeClass("has-error");
	$password.parent("div.form-group").removeClass("has-error");
	$cpassword.parent("div.form-group").removeClass("has-error");
	$email.parent("div.form-group").removeClass("has-error");
	$cemail.parent("div.form-group").removeClass("has-error");
	$fname.parent("div.form-group").removeClass("has-error");
	$lname.parent("div.form-group").removeClass("has-error");
	$usernameHelper.empty();
	$passwordHelper.empty();
	$cpasswordHelper.empty();
	$emailHelper.empty();
	$cemailHelper.empty();
	$fnameHelper.empty();
	$lnameHelper.empty();
	$usernameHelper.hide();
	$passwordHelper.hide();
	$cpasswordHelper.hide();
	$emailHelper.hide();
	$cemailHelper.hide();
	$fnameHelper.hide();
	$lnameHelper.hide();
}
function resetRegisterForm() {
	$("#registerForm")[0].reset();
}

// Edit Profile Form
function loadProfile() {
	$loadingModal = $("#loadingModal");
	$loadingModal.find("#loadingMsg").text("Loading profile...");
	$loadingModal.modal("show");
	$.get("getprofile.php", function(data) {
		$("#loadingModal").modal("hide");
		if(data.result == "success") {
			$("#editProfileUsername").text(data.profile.username);
			$("#editProfileRegisterTime").text(data.profile.registertime);
			$("#editProfileEmail").val(data.profile.email);
			$("#editProfileFirstname").val(data.profile.firstname);
			$("#editProfileLastname").val(data.profile.lastname);
			$("#editProfileModal").modal({
				backdrop: "static",
				show: true
			});
		} else {
			$("#resultTitle").html("<font color=\"red\">Error</font>");
			$("#resultBody").html("Cannot retrive user profile data.");
			$("#resultModal").modal({
				backdrop: "static",
				show: true
			});
		}
	});
}
function validateEditProfileForm() {
	var valid = true;
	var $email = $("#editProfileEmail");
	var $cemail = $("#editProfileConfirmEmail");
	var $fname = $("#editProfileFirstname");
	var $lname = $("#editProfileLastname");
	var $emailHelper = $("#editProfileEmailHelper");
	var $cemailHelper = $("#editProfileCEmailHelper");
	var $fnameHelper = $("#editProfileFirstnameHelper");
	var $lnameHelper = $("#editProfileLastnameHelper");
	var emailPattern = "(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|\"(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\])";
	var emailRegEx = new RegExp(emailPattern);
	if($email.val().length == 0) {
		$email.parent("div.form-group").addClass("has-error");
		$cemail.parent("div.form-group").removeClass("has-error");
		$emailHelper.html("Please enter email address.");
		$emailHelper.show();
		$cemailHelper.empty();
		$cemailHelper.hide();
		valid = false;
	} else if(!emailRegEx.test($email.val())) {
		$email.parent("div.form-group").addClass("has-error");
		$cemail.parent("div.form-group").removeClass("has-error");
		$emailHelper.html("Invalid email address.");
		$emailHelper.show();
		$cemailHelper.empty();
		$cemailHelper.hide();
		valid = false;
	} else if(($cemail.val().length > 0) &&($email.val() != $cemail.val())) {
		$email.parent("div.form-group").addClass("has-error");
		$cemail.parent("div.form-group").addClass("has-error");
		$emailHelper.empty();
		$emailHelper.hide();
		$cemailHelper.html("Email address mismatch.");
		$cemailHelper.show();
		valid = false;
	} else {
		$email.parent("div.form-group").removeClass("has-error");
		$cemail.parent("div.form-group").removeClass("has-error");
		$emailHelper.empty();
		$cemailHelper.empty();
		$emailHelper.hide();
		$cemailHelper.hide();
	}
	if($fname.val().length == 0) {
		$fname.parent("div.form-group").addClass("has-error");
		$fnameHelper.html("Please enter firstname.");
		$fnameHelper.show();
		valid = false;
	} else {
		$fname.parent("div.form-group").removeClass("has-error");
		$fnameHelper.empty();
		$fnameHelper.hide();
	}
	if($lname.val().length == 0) {
		$lname.parent("div.form-group").addClass("has-error");
		$lnameHelper.html("Please enter lastname.");
		$lnameHelper.show();
		valid = false;
	} else {
		$lname.parent("div.form-group").removeClass("has-error");
		$lnameHelper.empty();
		$lnameHelper.hide();
	}
	return valid;
}
function clearEditProfileFormError() {
	var $email = $("#editProfileEmail");
	var $cemail = $("#editProfileConfirmEmail");
	var $fname = $("#editProfileFirstname");
	var $lname = $("#editProfileLastname");
	var $emailHelper = $("#editProfileEmailHelper");
	var $cemailHelper = $("#editProfileCEmailHelper");
	var $fnameHelper = $("#editProfileFirstnameHelper");
	var $lnameHelper = $("#editProfileLastnameHelper");
	$email.parent("div.form-group").removeClass("has-error");
	$cemail.parent("div.form-group").removeClass("has-error");
	$fname.parent("div.form-group").removeClass("has-error");
	$lname.parent("div.form-group").removeClass("has-error");
	$emailHelper.empty();
	$cemailHelper.empty();
	$fnameHelper.empty();
	$lnameHelper.empty();
	$emailHelper.hide();
	$cemailHelper.hide();
	$fnameHelper.hide();
	$lnameHelper.hide();
}
function resetEditProfileForm() {
	$("#editProfileForm")[0].reset();
}

// Change Password Form
function validateChangePasswordForm() {
	var valid = true;
	var $newPassword = $("#changePasswordNewPassword");
	var $cpassword = $("#changePasswordConfirmPassword");
	var $oldPassword = $("#changePasswordOldPassword");
	var $newPasswordHelper = $("#changePasswordNewPasswordHelper");
	var $cpasswordHelper = $("#changePasswordCPasswordHelper");
	var $oldPasswordHelper = $("#changePasswordOldPasswordHelper");
	if($newPassword.val().length < 6) {
		$newPassword.parent("div.form-group").addClass("has-error");
		$cpassword.parent("div.form-group").removeClass("has-error");
		$newPasswordHelper.html("Password must contain at least 6 letters.");
		$newPasswordHelper.show();
		$cpasswordHelper.empty();
		$cpasswordHelper.hide();
		valid = false;
	} else if($newPassword.val() != $cpassword.val()) {
		$newPassword.parent("div.form-group").addClass("has-error");
		$cpassword.parent("div.form-group").addClass("has-error");
		$newPasswordHelper.empty();
		$newPasswordHelper.hide();
		$cpasswordHelper.html("Password mismatch.");
		$cpasswordHelper.show();
		valid = false;
	} else {
		$newPassword.parent("div.form-group").removeClass("has-error");
		$cpassword.parent("div.form-group").removeClass("has-error");
		$newPasswordHelper.empty();
		$cpasswordHelper.empty();
		$newPasswordHelper.hide();
		$cpasswordHelper.hide();
	}
	if($oldPassword.val().length == 0) {
		$oldPassword.parent("div.form-group").addClass("has-error");
		$oldPasswordHelper.html("Please enter old password.");
		$oldPasswordHelper.show();
		valid = false;
	} else {
		$oldPassword.parent("div.form-group").removeClass("has-error");
		$oldPasswordHelper.empty();
		$oldPasswordHelper.hide();
	}
	return valid;
}
function clearChangePasswordFormError() {
	var $newPassword = $("#changePasswordNewPassword");
	var $cpassword = $("#changePasswordConfirmPassword");
	var $oldPassword = $("#changePasswordOldPassword");
	var $newPasswordHelper = $("#changePasswordNewPasswordHelper");
	var $cpasswordHelper = $("#changePasswordCPasswordHelper");
	var $oldPasswordHelper = $("#changePasswordOldPasswordHelper");
	$newPassword.parent("div.form-group").removeClass("has-error");
	$cpassword.parent("div.form-group").removeClass("has-error");
	$oldPassword.parent("div.form-group").removeClass("has-error");
	$newPasswordHelper.empty();
	$cpasswordHelper.empty();
	$oldPasswordHelper.empty();
	$newPasswordHelper.hide();
	$cpasswordHelper.hide();
	$oldPasswordHelper.hide();
}
function resetChangePasswordForm() {
	$("#changePasswordForm")[0].reset();
}

// New Recipe Form
function validateNewRecipeForm() {
	var valid = true;
	var $recipeName = $("#recipeName");
	var $recipePicture = $("#recipePicture");
	var $recipeDescription = $("#recipeDescription");
	var $recipeStepList = $("#recipeStepList");
	var $recipeNameHelper = $("#recipeNameHelper");
	var $recipePictureHelper = $("#recipePictureHelper");
	var $recipeDescriptionHelper = $("#recipeDescriptionHelper");
	var $recipeStepListHelper = $("#recipeStepListHelper");
	var hasRecipePicture = $("#recipePicture-queue > div").size() == 1;
	if($recipeName.val().length == 0) {
		$recipeName.parent("div.form-group").addClass("has-error");
		$recipeNameHelper.text("Please enter recipe name.");
		$recipeNameHelper.show();
		valid = false;
	} else {
		$recipeName.parent("div.form-group").removeClass("has-error");
		$recipeNameHelper.empty();
		$recipeNameHelper.hide();
	}
	if(!hasRecipePicture) {
		$recipePictureHelper.css("color", "#B94A48");
		$recipePictureHelper.text("Please select recipe picture.");
		$recipePictureHelper.show();
		valid = false;
	} else {
		$recipePictureHelper.css("color", "");
		$recipePictureHelper.empty();
		$recipePictureHelper.hide();
	}
	if($recipeDescription.val().length == 0) {
		$recipeDescription.parent("div.form-group").addClass("has-error");
		$recipeDescriptionHelper.text("Please enter recipe description.");
		$recipeDescriptionHelper.show();
		valid = false;
	} else {
		$recipeDescription.parent("div.form-group").removeClass("has-error");
		$recipeDescriptionHelper.empty();
		$recipeDescriptionHelper.hide();
	}
	if($recipeStepList.children("li").size() == 0) {
		$recipeStepList.parent("div.form-group").addClass("has-error");
		$recipeStepListHelper.text("Please add recipe step.");
		$recipeStepListHelper.show();
		valid = false;
	} else {
		$recipeStepList.parent("div.form-group").removeClass("has-error");
		$recipeStepListHelper.empty();
		$recipeStepListHelper.hide();
	}
	return valid;
}
function clearNewRecipeFormError() {
	var $recipeName = $("#recipeName");
	var $recipePicture = $("#recipePicture");
	var $recipeDescription = $("#recipeDescription");
	var $recipeStep = $("#recipeStep");
	var $recipeStepList = $("#recipeStepList");
	var $recipeNameHelper = $("#recipeNameHelper");
	var $recipePictureHelper = $("#recipePictureHelper");
	var $recipeDescriptionHelper = $("#recipeDescriptionHelper");
	var $recipeStepHelper = $("#recipeStepHelper");
	var $recipeStepListHelper = $("#recipeStepListHelper");
	$recipeName.parent("div.form-group").removeClass("has-error");
	$recipePictureHelper.css("color", "");
	$recipeDescription.parent("div.form-group").removeClass("has-error");
	$recipeStep.parent("div.form-group").removeClass("has-error");
	$recipeStepList.parent("div.form-group").removeClass("has-error");
	$recipeNameHelper.empty();
	$recipePictureHelper.empty();
	$recipeDescriptionHelper.empty();
	$recipeStepHelper.empty();
	$recipeStepListHelper.empty();
	$recipeNameHelper.hide();
	$recipePictureHelper.hide();
	$recipeDescriptionHelper.hide();
	$recipeStepHelper.hide();
	$recipeStepListHelper.hide();
}
function resetNewRecipeForm() {
	$("#newRecipeForm")[0].reset();
	$("#recipeStepList").empty();
	$("#recipeStepEmpty").show();
}

// Edit Recipe Form
function validateEditRecipeForm() {
	var valid = true;
	var $recipeName = $("#recipeName");
	var $recipePicture = $("#recipePicture");
	var $recipeDescription = $("#recipeDescription");
	var $recipeStepList = $("#recipeStepList");
	var $recipeNameHelper = $("#recipeNameHelper");
	var $recipePictureHelper = $("#recipePictureHelper");
	var $recipeDescriptionHelper = $("#recipeDescriptionHelper");
	var $recipeStepListHelper = $("#recipeStepListHelper");
	if($recipeName.val().length == 0) {
		$recipeName.parent("div.form-group").addClass("has-error");
		$recipeNameHelper.text("Please enter recipe name.");
		$recipeNameHelper.show();
		valid = false;
	} else {
		$recipeName.parent("div.form-group").removeClass("has-error");
		$recipeNameHelper.empty();
		$recipeNameHelper.hide();
	}
	if($recipeDescription.val().length == 0) {
		$recipeDescription.parent("div.form-group").addClass("has-error");
		$recipeDescriptionHelper.text("Please enter recipe description.");
		$recipeDescriptionHelper.show();
		valid = false;
	} else {
		$recipeDescription.parent("div.form-group").removeClass("has-error");
		$recipeDescriptionHelper.empty();
		$recipeDescriptionHelper.hide();
	}
	if($recipeStepList.children("li").size() == 0) {
		$recipeStepList.parent("div.form-group").addClass("has-error");
		$recipeStepListHelper.text("Please add recipe step.");
		$recipeStepListHelper.show();
		valid = false;
	} else {
		$recipeStepList.parent("div.form-group").removeClass("has-error");
		$recipeStepListHelper.empty();
		$recipeStepListHelper.hide();
	}
	return valid;
}
function clearEditRecipeFormError() {
	var $recipeName = $("#recipeName");
	var $recipePicture = $("#recipePicture");
	var $recipeDescription = $("#recipeDescription");
	var $recipeStep = $("#recipeStep");
	var $recipeStepList = $("#recipeStepList");
	var $recipeNameHelper = $("#recipeNameHelper");
	var $recipePictureHelper = $("#recipePictureHelper");
	var $recipeDescriptionHelper = $("#recipeDescriptionHelper");
	var $recipeStepHelper = $("#recipeStepHelper");
	var $recipeStepListHelper = $("#recipeStepListHelper");
	$recipeName.parent("div.form-group").removeClass("has-error");
	$recipePictureHelper.css("color", "");
	$recipeDescription.parent("div.form-group").removeClass("has-error");
	$recipeStep.parent("div.form-group").removeClass("has-error");
	$recipeStepList.parent("div.form-group").removeClass("has-error");
	$recipeNameHelper.empty();
	$recipePictureHelper.empty();
	$recipeDescriptionHelper.empty();
	$recipeStepHelper.empty();
	$recipeStepListHelper.empty();
	$recipeNameHelper.hide();
	$recipePictureHelper.hide();
	$recipeDescriptionHelper.hide();
	$recipeStepHelper.hide();
	$recipeStepListHelper.hide();
}
function resetEditRecipeForm() {
	$("#editRecipeForm")[0].reset();
	$("#recipeStepList").empty();
	$("#recipeStepEmpty").show();
}