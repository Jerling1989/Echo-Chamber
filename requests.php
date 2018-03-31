<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');

?>


<div class="main-column column" id="main-column">

	<h4>Friend Requests</h4>

	<?php
		// DATABASE QUERY TO CHECK FRIEND REQUESTS TO LOGGED IN USER
		$query = mysqli_query($connection, "SELECT * FROM friend_requests WHERE user_to='$userLoggedIn'");

		// IF THERE ARE NO FRIEND REQUESTS
		if (mysqli_num_rows($query) == 0) {
			echo 'You have no friend requests at this time.';
			// IF THERE ARE FRIEND REQUESTS
		} else {
			// LOOP WHILE THERE ARE REQUESTS
			while ($row = mysqli_fetch_array($query)) {
				// $USER_FROM VARIABLE
				$user_from = $row['user_from'];
				// CREATE NEW USER OBJECT FOR USER REQUEST IS FROM
				$user_from_obj = new User($connection, $user_from);

				// DISPLAY NAME OF USER REQUEST IS FROM
				echo $user_from_obj->getUsername() . 'sent you a friend request!';
				// VARIABLE FOR FRIEND ARRAY FROM USER REQUEST IS FROM
				$user_from_friend_array = $user_from_obj->getFriendArray();

				// IF USER HITS ACCEPT REQUEST BUTTON
				if (isset($_POST['accept_request' . $user_from ])) {
					// DATABASE QUERY TO ADD NEW FRIEND TO USER LOGGED IN FRIEND ARRAY
					$add_friend_query = mysqli_query($connection, "UPDATE users SET friend_array=CONCAT(friend_array, '$user_from,') WHERE username='$userLoggedIn'");
					// DATABASE QUERY TO ADD NEW FRIEND TO FRIEND ARRAY OF USER REQUEST IS FROM
					$add_friend_query = mysqli_query($connection, "UPDATE users SET friend_array=CONCAT(friend_array, '$userLoggedIn,') WHERE username='$user_from'");

					// DATABASE QUERY TO DELETE INFO FROM FRIEND REQUEST TABLE
					$delete_query = mysqli_query($connection, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");

					// DISPLAY ACCEPTNANCE MESSAGE AND RELOAD PAGE
					echo 'You are now friends!';
					header('Location: requests.php');
				}


				// IF USER HITS IGNORE REQUEST BUTTON
				if (isset($_POST['ignore_request' . $user_from ])) {
					// DATABASE QUERY TO DELETE INFO FROM FRIEND REQUEST TABLE
					$delete_query = mysqli_query($connection, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");

					// DISPLAY REQUEST IGNORED MESSAGE AND RELOAD PAGE
					echo 'Request ignored!';
					header('Location: requests.php');
				}

				?>

				<!-- REQUEST BUTTON FORM -->
				<form action="requests.php" method="POST">
					<!-- ACCEPT REQUEST BUTTON -->
					<input type="submit" name="accept_request<?php echo $user_from; ?>" id="accept-button" value="Accept" />
					<!-- IGNORE REQUEST BUTTON -->
					<input type="submit" name="ignore_request<?php echo $user_from; ?>" id="ignore-button" value="Ignore" />
				</form>
				<!-- END REQUEST BUTTON FORM -->

				<?php

			}

		}

	?>


	
</div>


</body>
</html>