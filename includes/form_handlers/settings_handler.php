<?php

	// IF USER CLICKS THE UPDATE DETAILS BUTTON
	if (isset($_POST['update_details'])) {
		// CREATE VARIABLES FROM FORM VALUES
		$first_name = strip_tags($_POST['first_name']);
		$first_name = str_replace(' ', '', $first_name);
		$first_name = ucfirst(strtolower($first_name));

		$last_name = strip_tags($_POST['last_name']);
		$last_name = str_replace(' ', '', $last_name);
		$last_name = ucfirst(strtolower($last_name)); 

		$email = strip_tags($_POST['email']);
		$email = str_replace(' ', '', $email);
		$email = strtolower($email);

		$username = strip_tags($_POST['username']);
		$username = str_replace(' ', '', $username);

		// DATABASE QUERY TO CHECK IF EMAIL IS TAKEN
		$email_check = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'");
		$row = mysqli_fetch_array($email_check);
		$matched_user = $row['username'];

		// IF EMAIL IS NOT TAKEN OR ALREADY BELONGS TO LOGGED IN USER
		if ($matched_user == '' || $matched_user == $userLoggedIn) {
			// CREATE UPDATED DETAILS MESSAGE
			$message = 'Details Updated!<br /><br />';
			// DATABASE QUERY (UPDATE USER'S INFO)
			$query = mysqli_query($connection, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', username='$username' WHERE username='$userLoggedIn'");

			// DATABASE QUERY (SELECT USER INFO FROM USER EMAIL)
			$new_username_query = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'");
			$row = mysqli_fetch_array($new_username_query);
			// CREATE NEW USERNAME VARIABLE
			$new_username = $row['username'];

			// IF NEW USERNAME VARIABLE DOES NOT MATCH CURRENT $USERLOGGEDIN VARIABLE
			if ($new_username != $userLoggedIn) {
				// UPDATE USERNAME IN POSTS TABLE
				$update_posts_one = mysqli_query($connection, "UPDATE posts SET added_by='$new_username' WHERE added_by='$userLoggedIn'");
				$update_posts_two = mysqli_query($connection, "UPDATE posts SET user_to='$new_username' WHERE user_to='$userLoggedIn'");
				// UPDATE USERNAME IN NOTIFICATIONS TABLE
				$update_notifications_one = mysqli_query($connection, "UPDATE notifications SET user_from='$new_username' WHERE user_from='$userLoggedIn'");
				$update_notifications_two = mysqli_query($connection, "UPDATE notifications SET user_to='$new_username' WHERE user_to='$userLoggedIn'");
				// UPDATE USERNAME IN MESSAGES TABLE
				$update_messages_one = mysqli_query($connection, "UPDATE messages SET user_from='$new_username' WHERE user_from='$userLoggedIn'");
				$update_messages_two = mysqli_query($connection, "UPDATE messages SET user_to='$new_username' WHERE user_to='$userLoggedIn'");
				// UPDATE USERNAME IN LIKES TABLE
				$update_likes = mysqli_query($connection, "UPDATE likes SET username='$new_username' WHERE username='$userLoggedIn'");
				// UPDATE USERNAME IN FRIEND_REQUESTS TABLE
				$update_requests_one = mysqli_query($connection, "UPDATE friend_requests SET user_from='$new_username' WHERE user_from='$userLoggedIn'");
				$update_requests_two = mysqli_query($connection, "UPDATE friend_requests SET user_to='$new_username' WHERE user_to='$userLoggedIn'");
				// UPDATE USERNAME IN COMMENTS TABLE
				$update_comments_one = mysqli_query($connection, "UPDATE comments SET posted_by='$new_username' WHERE posted_by='$userLoggedIn'");
				$update_comments_two = mysqli_query($connection, "UPDATE comments SET posted_to='$new_username' WHERE posted_to='$userLoggedIn'");

				// FIND FRIEND ARRAYS WITH CURRENT $USERLOGGEDIN USERNAME
				$friend_array_query = mysqli_query($connection, "SELECT friend_array FROM users WHERE friend_array LIKE '%$userLoggedIn%'");
				$friend_array_row = mysqli_fetch_array($friend_array_query);
				$friend_array_username = $friend_array_row['friend_array'];
				// REPLACE AND UPDATE USERNAME IN FRIEND ARRAYS
				$new_friend_array = str_replace($userLoggedIn.',', $new_username.',', $friend_array_username);
				$remove_friend = mysqli_query($connection, "UPDATE users SET friend_array='$new_friend_array' WHERE friend_array LIKE '%$userLoggedIn%'");

				// REPLACE $USERLOGGEDIN AND $_SESSION['USERNAME']
				// VARIABLES WITH NEW USERNAME VALUE
				$userLoggedIn = $new_username;
				$_SESSION['username'] = $new_username;
			}

			// IF EMAIL IS ALREADY TAKEN BY SOMEBODY ELSE
		} else {
			$message = 'That email is already in use!<br /><br />';
		}

	} else {
		$message = '';
	}



	// IF USER CLICKS UPDATE PASSWORD BUTTON
	if (isset($_POST['update_password'])) {
		// CREATE NEW PASSWORD VARIABLES
		$old_password = strip_tags($_POST['old_password']);
		$new_password_1 = strip_tags($_POST['new_password_1']);
		$new_password_2 = strip_tags($_POST['new_password_2']);

		// DATABASE QUERY (FIND EMAIL AND PASSWORD OF USER)
		$password_query = mysqli_query($connection, "SELECT password, email FROM users WHERE username='$userLoggedIn'");
		$row = mysqli_fetch_array($password_query);
		$db_password = $row['password'];
		$db_email = $row['email'];

		// IF THE OLD PASSWORD MATCHES PASSWORD IN DATABASE
		if (md5($old_password) == $db_password) {
			// IF THE TWO NEW PASSWORDS MATCH
			if ($new_password_1 == $new_password_2) {
				// IF THE TWO NEW PASSWORD ARE NOT PROPER LENGTH DISPLAY ERROR
				if (strlen($new_password_1) > 30 || strlen($new_password_1) < 5) {
					$password_message = 'Your password must be between 5 and 30 characters<br /><br />';
					// IF TWO NEW PASSWORDS ARE PROPER LENGTH
				} else {
					// ENCRYPT NEW PASSWORD (ADD EMAIL SALT LATER)
					$new_password_md5 = md5($new_password_1);
					// DATABASE QUERY (UPDATE NEW PASSWORD)
					$password_query = mysqli_query($connection, "UPDATE users SET password='$new_password_md5' WHERE username='$userLoggedIn'");
					$password_message = 'Password has been changed<br /><br />';
				}
				// ERROR MESSAGE
			} else {
				$password_message = 'Your two new passwords need to match!<br /><br />';
			}
			// ERROR MESSAGE
		} else {
			$password_message = 'The old password is incorrect!<br /><br />';
		}
		// INITIALIZE MESSAGE VARIABLE
	} else {
		$password_message = '';
	}




	// IF USER CLICKS CLOSE ACCOUNT BUTTON
	if (isset($_POST['close_account'])) {
		header('Location: close_account.php');
	}



?>