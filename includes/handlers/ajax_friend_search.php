<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('../config/config.php');
	include('../classes/User.php');

	// CREATE $QUERY AND $USERLOGGEDIN VARIABLES
	$query = $_POST['query'];
	$userLoggedIn = $_POST['userLoggedIn'];

	// SPLIT USER SEARCH QUERY AT SPACES
	$names = explode(' ', $query);

?>