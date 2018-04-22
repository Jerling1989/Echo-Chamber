<?php

	// CREATE CONNECTION VARIABLES
	$servername = 'kavfu5f7pido12mr.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
	$username = 'eft7l9l9x0wp4xbq';
	$password = 'qmuz44u8it1qzuvd';
	$dbname = 'rdewxrnxjxxfdwfq';

	// TURN ON OUTPUT BUFFERING
	ob_start();
	// START SESSION
	session_start();

	// SET DEFAULT TIMEZONE
	$timezone = date_default_timezone_set('America/New_York');

	// CONNECTTION VARIABLE
	$connection = mysqli_connect($servername, $username, $password, $dbname);

	// CONNECTION ERROR
	if(mysqli_connect_errno()) {
		echo 'Failed to connect: ' . mysqli_connect_errno();
	}

?>