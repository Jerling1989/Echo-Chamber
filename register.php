<?php

	// CONNECTTION VARIABLE
	$connection = mysqli_connect('localhost', 'root', 'root', 'echo_chamber_db');

	// CONNECTION ERROR
	if(mysqli_connect_errno()) {
		echo 'Failed to connect: ' . mysqli_connect_errno();
	}

	// DECLARING VARIABLES TO PREVENT ERRORS
	$first_name = ''; // FIRST NAME
	$last_name = ''; // LAST NAME
	$email = ''; // EMAIL
	$email2 = ''; // EMAIL 2
	$password = ''; // PASSWORD
	$password2 = ''; // PASSWORD 2
	$date = ''; // SIGN UP DATE
	$error_array = ''; // HOLD ERROR MESSAGES


	// IF REGISTER BUTTON IS PRESSED
	if (isset($_POST['register_button'])) {

		// ASSIGNING REG_FNAME FORM VALUE TO $FIRST_NAME VARIABLE
		$first_name = strip_tags($_POST['reg_fname']); // REMOVE HTML TAGS
		$first_name = str_replace(' ', '', $fname); // REMOVE SPACES
		$first_name = ucfirst(strtolower($fname)); // CAPITALIZE FIRST LETTER ONLY

		// ASSIGNING REG_LNAME FORM VALUE TO $LAST_NAME VARIABLE
		$last_name = strip_tags($_POST['reg_lname']); // REMOVE HTML TAGS
		$last_name = str_replace(' ', '', $lname); // REMOVE SPACES
		$last_name = ucfirst(strtolower($lname)); // CAPITALIZE FIRST LETTER ONLY

		// ASSIGNING REG_EMAIL FORM VALUE TO $EMAIL VARIABLE
		$email = strip_tags($_POST['reg_email']); // REMOVE HTML TAGS
		$email = str_replace(' ', '', $email); // REMOVE SPACES
		$email = ucfirst(strtolower($email)); // CAPITALIZE FIRST LETTER ONLY

		// ASSIGNING REG_EMAIL2 FORM VALUE TO $EMAIL2 VARIABLE
		$email2 = strip_tags($_POST['reg_email2']); // REMOVE HTML TAGS
		$email2 = str_replace(' ', '', $email2); // REMOVE SPACES
		$email2 = ucfirst(strtolower($email2)); // CAPITALIZE FIRST LETTER ONLY

		// ASSIGNING REG_PASSWORD FORM VALUE TO $PASSWORD VARIABLE
		$password = strip_tags($_POST['reg_password']); // REMOVE HTML TAGS

		// ASSIGNING REG_PASSWORD2 FORM VALUE TO $PASSWORD2 VARIABLE
		$password2 = strip_tags($_POST['reg_password2']); // REMOVE HTML TAGS

		// ASSIGNING USER CREATION DATE (EX. FEB-18-2018)
		$date = date('M-d-Y');

		// CHECK IF EMAIL AND EMAIL2 MATCH
		if ($email == $email2) {
			// CHECK IF EMAIL IS IN PROPER FORMAT
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$email = filter_var($email, FILTER_VALIDATE_EMAIL);
			} else {
				echo 'Invalid format';
			}

			// UNMATCHING EMAIL ERROR
		} else {
			echo "Emails don't match";
		}

	}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Echo Chamber | Welcome</title>
</head>
<body>

	<form action="register.php" method="POST">
		
		<input type="text" name="reg_fname" placeholder="First Name" required />
		<br />
		<input type="text" name="reg_lname" placeholder="Last Name" required />
		<br />
		<input type="email" name="reg_email" placeholder="Email" required />
		<br />
		<input type="email" name="reg_email2" placeholder="Confirm Email" required />
		<br />
		<input type="password" name="reg_password" placeholder="Password" required />
		<br />
		<input type="password" name="reg_password2" placeholder="Confirm Password" required />
		<br />
		<input type="submit" name="register_button" value="Register" />


	</form>

</body>
</html>