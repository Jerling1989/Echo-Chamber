<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	require '../../config/config.php';
	include('../../includes/classes/User.php');

	// CREATE $QUERY AND $USERLOGGEDIN VARIABLE
	$query = $_POST['query'];
	$userLoggedIn = $_POST['userLoggedIn'];

	// SPLIT SEARCH QUERY INTO ARRAY
	$names = explode(' ', $query);

	// IF QUERY CONTAINS AN UNDERSCORE, ASSUME USER IS SEARCHING FOR USERNAMES
	if (strpos($query, '_') !== false) {
		$usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");

		// IF QUERY CONTAINS TWO WORDS, ASSUME USER IS SEARCHING FOR FIRST AND LAST NAME
	} else if (count($names) == 2) {
		$usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no' LIMIT 8");

		// IF QUERY CONTAINS ONE WORD ONLY, SEARCH FIRST NAMES OR LAST NAMES
	} else {
		$usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 8");
	}



	// IF USER SEARCH IS NOT BLANK
	if ($query != '') {
		// LOOP WHILE DATABASE QUERY YEILDS RESULTS
		while ($row = mysqli_fetch_array($usersReturnedQuery)) {
			// CREATE NEW USER OBJECT
			$user = new User($connection, $userLoggedIn);

			// IF QUERY RESULT IS NOT THE USER LOGGED IN CALCULATE NUMBER OF MUTUAL FRIENDS
			if ($row['username'] != $userLoggedIn) {
				$mutual_friends = $user->getMutualFriends($row['username']) . ' friends in common';
				// IF QUERY RESULT IS USER LOGGED IN LEAVE MUTUAL FRIENDS BLANK
			} else {
				$mutual_friends = '';
			}

			// ECHO STRING OF USER INFO FROM DATABASE QUERY
			echo '<div class="resultDisplay">
							<a href="'.$row['username'].'" style="color: #1485BD;">
								<div class="liveSearchProfilePic">
									<img src="'.$row['profile_pic'].'" />
								</div>

								<div class="liveSearchText">
									'.$row['first_name'].' '.$row['last_name'].'
									<p>'.$row['username'].'<br />
									<span class="grey-font">'.$mutual_friends.'</span></p>
									
								</div>
							</a>
						</div>';

		}

	}



?>