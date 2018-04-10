<?php

	// IF USER CLICKS THE UPDATE DETAILS BUTTON
	if (isset($_POST['update_details'])) {
		// CREATE VARIABLE FROM FORM VALUES
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$username = $_POST['username'];

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

?>