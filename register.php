<?php
	
	// START SESSION
	session_start();

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
		$first_name = str_replace(' ', '', $first_name); // REMOVE SPACES
		$first_name = ucfirst(strtolower($first_name)); // CAPITALIZE FIRST LETTER ONLY
		$_SESSION['reg_fname'] = $first_name; // STORES FIRST NAME INTO SESSION VARIABLE

		// ASSIGNING REG_LNAME FORM VALUE TO $LAST_NAME VARIABLE
		$last_name = strip_tags($_POST['reg_lname']); // REMOVE HTML TAGS
		$last_name = str_replace(' ', '', $last_name); // REMOVE SPACES
		$last_name = ucfirst(strtolower($last_name)); // CAPITALIZE FIRST LETTER ONLY
		$_SESSION['reg_lname'] = $last_name; // STORES LAST NAME INTO SESSION VARIABLE

		// ASSIGNING REG_EMAIL FORM VALUE TO $EMAIL VARIABLE
		$email = strip_tags($_POST['reg_email']); // REMOVE HTML TAGS
		$email = str_replace(' ', '', $email); // REMOVE SPACES
		$email = strtolower($email); // LOWERCASE ALL EMAIL LETTERS
		$_SESSION['reg_email'] = $email; // STORES EMAIL INTO SESSION VARIABLE

		// ASSIGNING REG_EMAIL2 FORM VALUE TO $EMAIL2 VARIABLE
		$email2 = strip_tags($_POST['reg_email2']); // REMOVE HTML TAGS
		$email2 = str_replace(' ', '', $email2); // REMOVE SPACES
		$email2 = strtolower($email2); // LOWERCASE ALL EMAIL LETTERS
		$_SESSION['reg_email2'] = $email2; // STORES EMAIL2 INTO SESSION VARIABLE

		// ASSIGNING REG_PASSWORD FORM VALUE TO $PASSWORD VARIABLE
		$password = strip_tags($_POST['reg_password']); // REMOVE HTML TAGS

		// ASSIGNING REG_PASSWORD2 FORM VALUE TO $PASSWORD2 VARIABLE
		$password2 = strip_tags($_POST['reg_password2']); // REMOVE HTML TAGS

		// ASSIGNING USER CREATION DATE (EX. 2018-10-31)
		$date = date('Y-m-d');

		// CHECK IF EMAIL AND EMAIL2 MATCH
		if ($email == $email2) {
			// CHECK IF EMAIL IS IN PROPER FORMAT
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				// ASSIGN PROPERLY FORMATTED EMAIL TO $EMAIL VARIABLE
				$email = filter_var($email, FILTER_VALIDATE_EMAIL);

				// CHECK IF EMAIL IS ALREADY REGISTERED
				$e_check = mysqli_query($connection, "SELECT email FROM users WHERE email='$email'");
				// COUNT THE NUMBER OF ROWS RETURNED
				$num_rows = mysqli_num_rows($e_check);

				// CHECK IF QUERY RETURNS ANY ROWS (EMAIL TAKEN)
				if($num_rows > 0) {
					echo 'Email already in use';
				}

				// INPROPER FORMAT ERROR
			} else {
				echo 'Invalid format';
			}

			// UNMATCHING EMAIL ERROR
		} else {
			echo "Your emails do not match";
		}

		
		// CHECK FIRST NAME LENGTH
		if (strlen($first_name) > 25 || strlen($first_name) < 2) {
			echo 'Your first name must be between 2 and 25 characters';
		}
		// CHECK LAST NAME LENGTH
		if (strlen($last_name) > 25 || strlen($last_name) < 2) {
			echo 'Your last name must be between 2 and 25 characters';
		}
		// CHECK FOR MATCHING PASSWORDS
		if ($password != $password2) {
			echo 'Your passwords do not match';
		} else {
			// CHECK IF PASSWORD USES ENGLISH LETTERS
			if (preg_match('/[^A-Za-z0-9]/', $password)) {
				echo 'Your password can only contain english characters or numbers';
			}
		}
		// CHECK PASSWORD LENGTH
		if (strlen($password) > 30 || strlen($password) < 5) {
			echo 'Your password must be between 5 and 30 characters';
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
		<!-- FIRST NAME INPUT -->
		<input type="text" name="reg_fname" placeholder="First Name" value="<?php
			if (isset($_SESSION['reg_fname'])) {
				echo $_SESSION['reg_fname'];
			}
		?>" required />
		<br />
		<!-- LAST NAME INPUT -->
		<input type="text" name="reg_lname" placeholder="Last Name" value="<?php
			if (isset($_SESSION['reg_lname'])) {
				echo $_SESSION['reg_lname'];
			}
		?>" required />
		<br />
		<!-- EMAIL INPUT -->
		<input type="email" name="reg_email" placeholder="Email" value="<?php
			if (isset($_SESSION['reg_email'])) {
				echo $_SESSION['reg_email'];
			}
		?>" required />
		<br />
		<!-- EMAIL 2 INPUT -->
		<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php
			if (isset($_SESSION['reg_email2'])) {
				echo $_SESSION['reg_email2'];
			}
		?>" required />
		<br />
		<!-- PASSWORD INPUT -->
		<input type="password" name="reg_password" placeholder="Password" required />
		<br />
		<!-- PASSWORD 2 INPUT -->
		<input type="password" name="reg_password2" placeholder="Confirm Password" required />
		<br />
		<!-- SUBMIT BUTTON -->
		<input type="submit" name="register_button" value="Register" />


	</form>

</body>
</html>