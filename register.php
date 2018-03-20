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
	$error_array = array(); // HOLD ERROR MESSAGES


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
					array_push($error_array, 'Email already in use<br />');
				}

				// INPROPER FORMAT ERROR
			} else {
				array_push($error_array, 'Invalid email format<br />');
			}

			// UNMATCHING EMAIL ERROR
		} else {
			array_push($error_array, 'Your emails do not match<br />');
		}


		
		// CHECK FIRST NAME LENGTH
		if (strlen($first_name) > 25 || strlen($first_name) < 2) {
			array_push($error_array, 'Your first name must be between 2 and 25 characters<br />');
		}
		// CHECK LAST NAME LENGTH
		if (strlen($last_name) > 25 || strlen($last_name) < 2) {
			array_push($error_array, 'Your last name must be between 2 and 25 characters<br />');
		}
		// CHECK FOR MATCHING PASSWORDS
		if ($password != $password2) {
			array_push($error_array, 'Your passwords do not match<br />');
		} else {
			// CHECK IF PASSWORD USES ENGLISH LETTERS (ADDED SPECIAL CHARACTERS)
			if (preg_match('/[^A-Za-z0-9\.\+!@#$%^&*()]/', $password)) {
				array_push($error_array, 'Your password can only contain english characters or numbers<br />');
			}
		}
		// CHECK PASSWORD LENGTH
		if (strlen($password) > 30 || strlen($password) < 5) {
			array_push($error_array, 'Your password must be between 5 and 30 characters<br />');
		}
		
		

		// IF THERE ARE NO ERRORS IN USER SIGN UP DETAILS...
		if (empty($error_array)) {

			// ENCRYPT PASSWORD BEFORE SENT TO DATABASE
			$password = md5($password);
			// GENERATE USERNAME BY CONCATENATING FIRST AND LAST NAME
			$username = strtolower($first_name . '_' . $last_name);
			// QUERY TO CHECK IF USERNAME IS ALREADY TAKEN
			$check_username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$username'");

			$i = 0;
			// IF USERNAME ALREADY EXISTS ADD NUMBER TO CREATE NEW USERNAME
			while (mysqli_num_rows($check_username_query) != 0) {
				$i++; // ADD ONE TO $I AND CONCATENATE TO USERNAME
				$username = $username . '_' . $i;
				// QUERY TO CHECK USERNAME EXISTENCE AGAIN
				$check_username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$username'");
			}

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
			} ?>" required />
		<br />
		<!-- FIRST NAME ERROR MESSAGE -->
		<?php if (in_array('Your first name must be between 2 and 25 characters<br />', $error_array)) {
			echo 'Your first name must be between 2 and 25 characters<br />';
		} ?>

		<!-- LAST NAME INPUT -->
		<input type="text" name="reg_lname" placeholder="Last Name" value="<?php
			if (isset($_SESSION['reg_lname'])) {
				echo $_SESSION['reg_lname'];
			} ?>" required />
		<br />
		<!-- LAST NAME ERROR MESSAGE -->
		<?php if (in_array('Your last name must be between 2 and 25 characters<br />', $error_array)) {
			echo 'Your last name must be between 2 and 25 characters<br />';
		} ?>

		<!-- EMAIL INPUT -->
		<input type="email" name="reg_email" placeholder="Email" value="<?php
			if (isset($_SESSION['reg_email'])) {
				echo $_SESSION['reg_email'];
			} ?>" required />
		<br />

		<!-- EMAIL 2 INPUT -->
		<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php
			if (isset($_SESSION['reg_email2'])) {
				echo $_SESSION['reg_email2'];
			} ?>" required />
		<br />
		<!-- EMAIL ERROR MESSAGES -->
		<?php if (in_array('Email already in use<br />', $error_array)) {
			echo 'Email already in use<br />';
		} else if (in_array('Invalid email format<br />', $error_array)) {
			echo 'Invalid email format<br />';
		} else if (in_array('Your emails do not match<br />', $error_array)) {
			echo 'Your emails do not match<br />';
		} ?>

		<!-- PASSWORD INPUT -->
		<input type="password" name="reg_password" placeholder="Password" required />
		<br />

		<!-- PASSWORD 2 INPUT -->
		<input type="password" name="reg_password2" placeholder="Confirm Password" required />
		<br />
		<!-- PASSWORD ERROR MESSAGES -->
		<?php if (in_array('Your password can only contain english characters or numbers<br />', $error_array)) {
			echo 'Your password can only contain english characters or numbers<br />';
		} else if (in_array('Your password must be between 5 and 30 characters<br />', $error_array)) {
			echo 'Your password must be between 5 and 30 characters<br />';
		} else if (in_array('Your passwords do not match<br />', $error_array)) {
			echo 'Your passwords do not match<br />';
		} ?>

		<!-- SUBMIT BUTTON -->
		<input type="submit" name="register_button" value="Register" />

	</form>

</body>
</html>