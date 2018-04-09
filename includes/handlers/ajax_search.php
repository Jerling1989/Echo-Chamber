<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	require '../../config/config.php';
	include('../../includes/classes/User.php');

	// CREATE $QUERY AND $USERLOGGEDIN VARIABLE
	$query = $_POST['query'];
	$userLoggedIn = $_POST['userLoggedIn'];

	// SPLIT SEARCH QUERY INTO ARRAY
	$names = explode(' ', $query);

?>