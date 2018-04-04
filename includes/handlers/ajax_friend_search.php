<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('../../config/config.php');
	include('../classes/User.php');

	// CREATE $QUERY AND $USERLOGGEDIN VARIABLES
	$query = $_POST['query'];
	$userLoggedIn = $_POST['userLoggedIn'];

	// SPLIT USER SEARCH QUERY AT SPACES
	$names = explode(' ', $query);

	// IF UNDERSCORE IS IN QUERY STRING
	if (strpos($query, '_') !== false) {
		// DATABASE QUERY (FIND USERS SIMILAR TO $QUERY STRING)
		$usersReturned = mysqli_query($connection, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
		// IF THERE ARE TWO ELEMENTS IN THE $NAMES ARRAY
	} else if (count($names) == 2) {
		// DATABASE QUERY (FIND USERS SIMILAR TO $NAMES ARRAY ELEMENTS)
		$usersReturned = mysqli_query($connection, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[1]%') AND user_closed='no' LIMIT 8");
		// IF THERE IS ONLY ONE ELEMENT IN THE $NAMES ARRAY
	} else {
		// DATABASE QUERY (FIND USERS SIMILAR TO $NAMES ARRAY ELEMENT)
		$usersReturned = mysqli_query($connection, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' OR last_name LIKE '%$names[0]%') AND user_closed='no' LIMIT 8");
	}

	// IF USER QUERY IS NOT EMPTY
	if ($query != '') {
		// LOOP WHILE DATABASE QUERY YEILDS RESULTS
		while ($row = mysqli_fetch_array($usersReturned)) {
			// CREATE NEW USER OBJECT
			$user = new User($connection, $userLoggedIn);

			// IF USERNAME IS NOT $USERLOGGEDIN
			if ($row['username'] != $userLoggedIn) {
				// CALCULATE NUMBER OF MUTUAL FRIENDS
				$mutual_friends = $user->getMutualFriends($row['username']) . ' friends in common';
				// ELSE...
			} else {
				// LEAVE MUTUAL FRIENDS BLANK
				$mutual_friends = '';
			}

			// IF QUERY RESULTS YEILD FRIENDS OF USER
			// CREATE RESULTS DIV OF QUERY RESULTS
			if ($user->isFriend($row['username'])) {
				echo '<div class="resultDisplay">
								<a href="messages.php?u='.$row['username'].'" style="color: #000;">
									<div class="liveSearchProfilePic">
										<img src="'.$row['profile_pic'].'" />
									</div>
									<div class="liveSearchText">
										'.$row['first_name'].' '.$row['last_name'].'
										<p style="margin: 0px;">'.$row['username'].'</p>
										<p style="color: #8C8C8C;">'.$mutual_friends.'</p>
									</div>
								</a>
							</div>';

			}
		}
	}











?>