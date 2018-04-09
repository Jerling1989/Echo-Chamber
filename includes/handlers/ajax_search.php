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



?>