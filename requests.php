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


				if (isset($_POST['accept_request'] . $user_from)) {

				}

				if (isset($_POST['ignore_request'] . $user_from)) {

				}
			}
		}

	?>
	
</div>


</body>
</html>