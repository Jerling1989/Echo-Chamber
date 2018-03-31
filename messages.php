<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');

	// CREATE NEW MESSAGE OBJECT
	$message_obj = new Message($connection, $userLoggedIn);

	// IF USERNAME IS SET IN URL
	if (isset($_GET['u'])) {
		// SET $USER_TO TO USERNAME IN URL
		$user_to = $_GET['u'];
		// ELSE SET $USER_TO TO MOST RECENT USER MESSAGED
	} else {
		$user_to = $message_obj->getMostRecentUser();
		// IF THERE IS NO MOST RECENT USER, START NEW MESSAGE
		if ($user_to == false) {
			$user_to = 'new';
		}
	}

?>