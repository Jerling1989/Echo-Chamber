<?php

	// IF LOGIN BUTTON IS PRESSED
	if (isset($_POST['login_button'])) {
		
		$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); // SANITIZE EMAIL
		$_SESSION['log_email'] = $email; // STORE EMAIL INTO SESSION VARIABLE
		$password = md5(md5($email).$_POST['log_password']); // ENCRYPT PASSWORD

		// RUN QUERY TO CHECK FOR MATCHING EMAIL AND PASSWORD 
		$check_database_query = mysqli_query($connection, "SELECT * FROM users WHERE email='$email' AND password='$password'");
		// CHECK NUMBER OF RESULTS FROM QUERY
		$check_login_query = mysqli_num_rows($check_database_query);

		// IF THERE IS A RESULT FROM THE QUERY (CORRECT USER LOG IN)
		if ($check_login_query == 1) {
			
			$row = mysqli_fetch_array($check_database_query); // STORE ARRAY IN $ROW VARIABLE
			$username = $row['username']; // SET $USERNAME TO DATABASE USERNAME

			// RUN QUERY TO CHECK IF USER ACCOUNT IS CLOSED
			$user_closed_query = mysqli_query($connection, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
			// IF USER ACCOUNT HAS BEEN CLOSED RE-OPEN ACCOUNT
			if (mysqli_num_rows($user_closed_query) == 1) {
				$reopen_account = mysqli_query($connection, "UPDATE users SET user_closed='no' WHERE email='$email'");
			}

			// SET USERNAME SESSION VARIABLE
			$_SESSION['username'] = $username;
			// REDIRECT PAGE TO INDEX.PHP
			header('Location: index.php');
			exit();

			// NO RESULT FROM QUERY (INCORRECT USER LOG IN)
		} else {
			// CREATE LOGIN ERROR MESSAGE
			array_push($error_array, 'email/password error');
		}
	}
	
?>